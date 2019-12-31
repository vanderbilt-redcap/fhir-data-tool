<?php
namespace REDCap\FhirDataTool\App\Models
{

    class FhirDataTool
    {
        
        private $allowedEndpoint = array(
            'Observation',
            'MedicationOrder',
            'Condition',
            'Patient',
            'AllergyIntolerance',
        );

        public function __construct()
        {

        }

        private static function getUiId()
        {
            global $userid;
            $userInfo = \User::getUserInfo($userid);
            return $userInfo['ui_id'];
        }

        private function getPatientIdFormMrn($mrn, $access_token)
        {
            $fhirEhr = new \FhirEhr();
            $fhirEhr->setUserId();
            $setUiId = $fhirEhr->setUiId();
            
            $record_identifier_external = $mrn;
            $patient_id = $fhirEhr->getPatientIdFromMrn($record_identifier_external, $access_token);
            return $patient_id;
        }

        private function getFhirEndpointURL($endpoint, $params=array(), $resource_id=null)
        {
            // Build Epic URL
            switch ($endpoint) {
                case 'Patient':
                    $url = \FhirResourcePatient::getReadUrl($resource_id);
                    break;
                case 'Binary':
                    $url = \FhirResourceBinary::getReadUrl($resource_id);
                    break;
                case 'Observation':
                    $params['patient'] = $resource_id;
                    $url = \FhirResourceObservation::getSearchUrl($params);
                    break;
                case 'MedicationOrder':
                    $params['patient'] = $resource_id;
                    $url = \FhirResourceMedicationOrder::getSearchUrl($params);
                    break;
                case 'Condition':
                    $params['patient'] = $resource_id;
                    $url = \FhirResourceCondition::getSearchUrl($params);
                    break;
                case 'AllergyIntolerance':
                    $params['patient'] = $resource_id;
                    $url = \FhirResourceAllergyIntolerance::getSearchUrl($params);
                    break;
                case 'DocumentReference':
                    $params['patient'] = $resource_id;
                    $url = \FhirResourceDocumentReference::getSearchUrl($params);
                    break;
                default:
                    // $url = $enpoint_base_URL."$endpoint?patient=".urlencode($resource_id);
                    $url = ''; //endpoint not enabled
                    break;
            }

            return $url;
            
        }

        public function getProjectInfo($project_id)
        {
            $project = new \Project($project_id);
            return $project;
        }

        /**
         * get the access token string
         *
         * @param string $mrn
         * @throws FhirException
         * @return string
         */
        public static function getAccessToken($mrn)
        {
            global $lang;
            // Obtain an active FHIR access token for this patient
            $ui_id = static::getUiId();
            $tokenManager = new \FhirTokenManager($ui_id, $mrn);
            $token = $tokenManager->getToken();
            if(!$token instanceof \FHIRToken)
            {
                $noTokenMessage = $lang['global_01'].$lang['colon']." ".$lang['data_entry_398'];
                throw new \FhirException($noTokenMessage, $code=400);
            }
            $access_token = $token->getAccessToken(); // get any valid token
            
            return $access_token;
        }

        public static function getTokens($user=null, $token_limit=10)
        {
            // make sure the user_id is a numeric value
            if(is_numeric($user)) $user_id = $user;
            else
            {
                $userInfo = \User::getUserInfo($user);
                $user_id = $userInfo['ui_id'];
            }
            $tokenManager = new \FhirTokenManager($user_id, null, $token_limit);
            return $tokenManager->getTokens($user_id);
        }

        /**
         * fetch data from a FHIR endpoint
         *
         * @param string $access_token FHIR access token
         * @param string $url url of the FHIR resource
         * @return object object with the data returned from the endpoint
         */
        public function fetchPatientDataFromEndpoint($access_token, $url)
        {
            global $fhir_client_id, $fhir_client_secret;

            try {
                $fhirEhr = new \FhirEhr();
                $fhirEhr->setUserId();
                $setUiId = $fhirEhr->setUiId();
                // Instantiate FHIR Services
                $fhirServices = new \FhirServices($fhirEhr->getFhirEndpointBaseUrl(), $fhir_client_id, $fhir_client_secret);
                $fhirDataRaw = $fhirServices->getFhirData($url, $access_token);
                return $fhirDataRaw;
            } catch (\Exception $e) {
                throw new \FhirException($message=$e->getMessage(), $code=$e->getCode());
            }
        }

        public function getResource($mrn, $endpoint, $params)
        {
            $access_token = static::getAccessToken($mrn);
            try {
                $patient_id = $this->getPatientIdFormMrn($mrn, $access_token);
                if($patient_id===false)
                {
                    throw new \FhirException('no patient ID was found', $code=400);
                }
            } catch (\Exception $e) {
                throw new \FhirException($message=$e->getMessage(), $code=$e->getCode());
            }
            // Build Epic URL
            $endpoint_url = $this->getFhirEndpointURL($endpoint, $params, $patient_id);

            $data = $this->fetchPatientDataFromEndpoint($access_token, $endpoint_url);
            $resource = $this->parseData($data);
            
            /**
             * check for documentreference resources and save related files
             */
            if(is_a($resource, \FhirResourceBundle::class))
            {
                $entries = $resource->entries;
                $saveAttachment = function($mrn, $attachment)
                {
                    $save_path_root = EDOC_PATH."document_reference/__{$mrn}";
                    
                    $stylesheetPath = APP_PATH_DOCROOT.'Resources/misc/clinical_documents/'.'CDA.xsl';
                    $document_name = basename($attachment->url);
                    $html_string = $attachment->transformXML($stylesheetPath);
                    $text = $attachment->parseText($html_string);
                    // save path and relative data
                    $path_data_mapping = array(
                        "{$save_path_root}/HTML/{$mrn}-{$document_name}.html" => $html_string,
                        "{$save_path_root}/text/{$mrn}-{$document_name}.txt" => $text,
                    );
                    foreach ($path_data_mapping as $path => $data) {
                        $dir = dirname($path);
                        if(!file_exists($dir)) mkdir($dir, 0777, true); // create folders recursively
                        file_put_contents($path, $data);
                    }
                };

                foreach ($entries as $entry) {
                    if(is_a($entry, \FhirResourceDocumentReference::class))
                    {
                        foreach ($entry->attachments as $attachment) {
                            try {
                                $binary_data = $this->getAttachment($access_token, $mrn, $entry, $attachment);
                                $attachment->setBinaryData($binary_data);
                                $saveAttachment($mrn, $attachment);
                            } catch (\Exception $e) {
                                //throw $th;
                                $message = $e->getMessage();
                            }
                            // break; //temporary
                        }
                    }
                    // break; //temporary
                }
            }
            return $resource;
        }

        /**
         * save a file locally
         *
         * @param string $binary_data
         * @param string $path
         * @return void
         */
        private function saveFile($binary_data, $path)
        {
            $destination = dirname($path);
            if(!file_exists($destination)) mkdir($destination, 0777, true);
            $flags = FILE_APPEND;
            $flags = null; // overwrite
            return file_put_contents( $path , $binary_data , $flags );
        }

        /**
         * get the attachment data
         * if the data has already been saved locally then get the local file
         * otherwise get the remote binary data.
         * when the data is fetch from the remote FHIR endpoint it is also persisted locally.
         * download new data also if the file exists and is older than $max_age
         * 
         *
         * @param string $access_token
         * @param string $mrn
         * @param object $entry
         * @param object $attachment
         * @param integer $max_age 600 = 10 minutes
         * @return void
         */
        public function getAttachment($access_token, $mrn, $entry, $attachment, $max_age=600)
        {
            $contentType = $attachment->contentType;
            $url = $attachment->url;
            $file_extension = explode('/', $contentType )[1];
            $destination = EDOC_PATH.'document_reference';
            $document_id = $entry->id;
            $file_name = sprintf("%s_%s.%s", $mrn, $document_id, $file_extension);
            $file_path = sprintf("%s/%s", $destination, $file_name);
            $creation_date = filemtime($file_path);
            $now = time();
            if($creation_date===false || ($now - $creation_date)>=$max_age)
            {
                $binary_data = $this->getBinaryData($access_token, $url, $contentType);
                $success = $this->saveFile($binary_data, $file_path);
                if(!$success)
                {
                    $message = sprintf("Error Processing Request. Could not save the file to path %s", $file_path);
                    throw new \Exception($message, 1);
                }
            }else {
                $binary_data = file_get_contents($file_path);
            }
            return $binary_data;
        }

        /**
         * get the data from a binary file
         *
         * @param string $url
         * @param string $patient_id used to retreive the same access token of the patient owning the file
         * @return string
         */
        public function getBinaryData($access_token, $url, $contentType)
        {
            global $fhir_client_id, $fhir_client_secret;
            
            // Instantiate FHIR Services
            try {
                $headers = array(
                    'Accept' => null,
                    'Content-Type' => $contentType,
                );
                $fhirData = $this->getFhirData($url, $access_token, $headers);
                return $fhirData;
            } catch (\Exception $e) {
                throw new \FhirException($message=$e->getMessage(), $code=$e->getCode());
            }
        }

        /**
         * get data from a FHIR endpoint
         *
         * @param string $url
         * @param string $access_token
         * @param array $headers
         * @return string HTTP response body
         */
        public function getFhirData($url=null, $access_token=null, $headers=array())
        {
            if (empty($access_token)) throw new \Exception("No access token available.");
            $default_headers = array(
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => "Bearer {$access_token}",
            );
            $request_headers = array_merge($default_headers, $headers);
            $http_options = array(
                'headers' => $request_headers,
                'options' => array('timeout'=> 30),
            );

            $response = \HttpClient::request($method='GET', $url, $http_options);
            return $response->body;
        }

        /**
         * parse data and get a FHIR resource boundle
         *
         * @param object $raw_data
         * @return FhirResourceBundle
         */
        public function parseData($raw_data)
        {
            try {
                $resourceType = $raw_data->resourceType;
                switch ($resourceType) {
                    case 'Patient':
                        $resource = new \FhirResourcePatient($raw_data);
                        break;
                    case 'Bundle':
                        $resource = new \FhirResourceBundle($raw_data);
                        break;
                    default:
                        $resource = null;
                        break;
                }
                return $resource;
            } catch (\Exception $e) {
                throw $e;
            }
        }

    }
}