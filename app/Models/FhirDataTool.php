<?php
namespace REDCap\FhirDataTool\App\Models
{
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;
    class FhirDataTool
    {

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

        public function getProjectInfo($project_id)
        {
            $project = new \Project($project_id);
            return $project;
        }

        public function getDatamartRevision($project_id)
        {
            $project =$this->getProjectInfo($project_id);
            if($datamart_enabled = $project->project['datamart_enabled'])
            {
                if($active_revision = \DataMartRevision::getActive($project_id))
                {
                    return $active_revision;
                }
                return false;
            }else
            {
                return false;
            }
        }

        public function getClinicalDataPullMapping($project_id)
        {
            $query_string = sprintf(
                'SELECT * FROM redcap_ddp_mapping
                WHERE project_id = %u', $project_id
            );
            $result = db_query($query_string);
            $mapping = array();
            while($row = db_fetch_assoc($result))
            {
                $mapping[] = $row;
            }
            return $mapping;
        }

        /**
         * get the access token string
         *
         * @param string $mrn
         * @throws FhirException
         * @return string
         */
        public static function getAccessToken($mrn=null)
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
        public function fetchDataFromEndpoint($access_token, $url)
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

        /**
         * get the FHIR ID of a patient using the MRN
         *
         * @param string $mrn
         * @param string $access_token
         * @return void
         */
        public function getPatientId($mrn, $access_token) {
            try {
                $patient_id = $this->getPatientIdFormMrn($mrn, $access_token);
                if($patient_id===false)
                {
                    throw new \FhirException('no patient ID was found', $code=400);
                }
                return $patient_id;
            } catch (\Exception $e) {
                throw new \FhirException($message=$e->getMessage(), $code=$e->getCode(), $e);
            }
        }

        /**
         * get a FHIR resource
         *
         * @param string $resource_type Patient, Observation, MedicationOrder...
         * @param string $interaction seacrh,read...
         * @param mixed $params could by an ID for read interactions or an array of parameters
         * @return void
         */
        public function getResource($resource_type, $interaction, $params)
        {
            try {
                $access_token = self::getAccessToken();
                $base_URL = \FhirEhr::getFhirEndpointBaseUrl();
                $endpoint = FhirEndpoint::getInstance($resource_type, $base_URL, $access_token);
                
                switch (strtolower($interaction)) {
                    case FhirEndpoint::INTERACTION_READ:
                        $data = $endpoint->read($id=$params);
                        break;
                    case FhirEndpoint::INTERACTION_SEARCH:
                        $data = $endpoint->search($params);
                        break;
                    // this interactions are not available in REDCap
                    case FhirEndpoint::INTERACTION_UPDATE:
                    case FhirEndpoint::INTERACTION_DELETE:
                    case FhirEndpoint::INTERACTION_CREATE:
                    case FhirEndpoint::INTERACTION_HISTORY:
                    case FhirEndpoint::INTERACTION_TRANSACTION:
                    case FhirEndpoint::INTERACTION_OPERATION:
                        throw new \Exception("Interactions of type '{$interaction}' are not available in REDCap", 1);
                        break;
                    default:
                        throw new \Exception("Error: you must specify an interaction", 1);
                    break;
                }
                $resource = $this->parseData($data);
                return $resource;
            } catch (\Exception $e) {
                throw new \FhirException($message=$e->getMessage(), $code=$e->getCode(), $e);
            }
        }

        public function getResourceByMrn($mrn, $resource_type, $interaction, $params=array())
        {
            try {
                $access_token = self::getAccessToken($mrn);
                $patient_id = $this->getPatientId($mrn, $access_token);
                
                switch (strtolower($interaction)) {
                    case FhirEndpoint::INTERACTION_READ:
                        $params = $patient_id;
                    break;
                    case FhirEndpoint::INTERACTION_SEARCH:
                        $params['patient'] = $patient_id;
                    break;
                    default:
                    break;
                }
                $resource = $this->getResource($resource_type, $interaction, $params);
                return $resource;
            } catch (\Exception $e) {
                throw new \FhirException($message=$e->getMessage(), $code=$e->getCode(), $e);
            }
            
            /**
             * check for documentreference resources and save related files
             */
            /* if(is_a($resource, \FhirResourceBundle::class))
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
            return $resource; */
        }

        /**
         * group the FHIR source fields by category and subcategory
         *
         * @param array $fields external source fields
         * @return array
         */
        public function getGroupedSourceFields($fields)
        {
            $groups = array();
            foreach ($fields as $field) {
                $category = $field['category'];
                if(empty($category)) continue; //category must be set
                // make sure category is an array
                if(!is_array($groups[$category])) $groups[$category] = array();
                // priority to sub categories
                if($sub_category = $field['subcategory'])
                {
                    // make sure sub_category is an array
                    if(!is_array($groups[$category][$sub_category])) $groups[$category][$sub_category] = array();
                    $groups[$category][$sub_category][] = $field;
                }else
                {
                    $groups[$category][] = $field;
                }
            }
            return $groups;
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