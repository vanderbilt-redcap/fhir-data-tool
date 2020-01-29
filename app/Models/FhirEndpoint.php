<?php
namespace REDCap\FhirDataTool\App\Models
{

    abstract class FhirEndpoint
    {

        /**
         * accepted prefixes for values or ranges
         *
         * @see https://www.hl7.org/fhir/search.html#modifiers
         * @var array
         */
        protected static $prefixes = array('eq', 'ne', 'gt', 'lt', 'ge', 'le', 'sa', 'eb', 'ap');

        /**
         * query parameters that can apply to all resources
         * 
         * @see https://www.hl7.org/fhir/DSTU2/search.html#all
         * @var array
         */
        protected static $common_parameters = array('_content', '_id', '_lastUpdated', '_profile', '_query', '_security', '_tag', '_text');

        /**
         * FHIR resource type
         * 
         * @see https://www.hl7.org/fhir/resourcelist.html
         */
        const RESOURCE_TYPE = null;

        /**
         * interaction types
         * 
         * @see https://www.hl7.org/fhir/overview-dev.html#Interactions
         */
        const INTERACTION_READ = 'read';
        const INTERACTION_UPDATE = 'update';
        const INTERACTION_DELETE = 'delete';
        const INTERACTION_CREATE = 'create';
        const INTERACTION_SEARCH = 'search';
        const INTERACTION_HISTORY = 'history';
        const INTERACTION_TRANSACTION = 'transaction';
        const INTERACTION_OPERATION = 'operation';

        /**
         * datetime in FHIR compatible format
         * 
         * @see https://www.hl7.org/fhir/datatypes.html#dateTime
         */
        const FHIR_DATETIME_FORMAT = "Y-m-d\TH:i:s\Z";
        
        /**
         * the enpoint URL
         *
         * @var string
         */
        protected $base_URL = '';

        /**
         * the full url to the resource
         * includes the base URL and the resource name
         *
         * @var string
         */
        protected $resource_url = '';

        /**
         * access token used to retrieve data from the FHIR endpoints
         *
         * @var string
         */
        protected $access_token = null;


        /**
         * create a FHIR endpoint
         *
         * @param object $field_info
         */
        public function __construct($base_URL, $access_token)
        {
            $this->base_URL = $base_URL;
            $this->resource_url = $this->base_URL.static::RESOURCE_TYPE;
            $this->access_token = $access_token;
        }

        /**
         * return the url for the read interaction
         * method: GET
         * 
         * @param string $id
         * @return string
         */
        public function read($id)
        {
           $url = "{$this->resource_url}/{$id}";
           $data = $this->getFhirData($url, $this->access_token);
           return json_decode($data);
        }

        /**
         * the query parameters in a GET request can have multiple formats.
         * in FHIR we usually use only comma and repeat.
         * the 'repeat' format appears to be the most compatible
         * 
         */
        const QUERY_ARRAY_FORMAT_REPEAT = 'repeat';
        const QUERY_ARRAY_FORMAT_COMMA = 'comma';
        const QUERY_ARRAY_FORMAT_BRACKETS = 'brackets';
        const QUERY_ARRAY_FORMAT_INDICES = 'indices';
        /**
         * alternative function for http_build_query.
         * uses the 'repeat' format instead of the 'brackets' format
         * provided by the standard http_build_query function
         *
         * @param array $params
         * @return string
         */
        public static function buildQuery($params, $array_format = self::QUERY_ARRAY_FORMAT_REPEAT)
        {
            $query_strings = array();
            foreach ($params as $key => $value) {
                if(is_array($value))
                {
                    switch ($array_format) {
                        case self::QUERY_ARRAY_FORMAT_COMMA:
                            $joined_value = implode(', ', $value); // trasform array in comma separated values (FHIR compatible)
                            $query_strings[] = "{$key}=$joined_value";
                            break;
                        case self::QUERY_ARRAY_FORMAT_REPEAT:
                            foreach ($value as $sub_value) $query_strings[] = "{$key}={$sub_value}";
                            break;
                        case self::QUERY_ARRAY_FORMAT_BRACKETS:
                            // 2020-01-28 TODO. not needed for now
                        case self::QUERY_ARRAY_FORMAT_INDICES:
                            // 2020-01-28 TODO. not needed for now
                        default:
                            break;
                    }
                }else
                {
                    $query_strings[] = "{$key}={$value}";
                }
            }
            $query_string = implode('&', $query_strings); // join all params
            return $query_string;
        }

        /**
         * return the url for the search interaction
         * method: GET
         * 
         * @param array $params
         * @return string
         */
        public function search($params)
        {
            // $query_params = http_build_query($params);
            $query_params = self::buildQuery($params);
            $url = "{$this->resource_url}/?{$query_params}";
            $data = $this->getFhirData($url, $this->access_token);
            return json_decode($data);
        }

        /**
         * return the url for the update interaction
         * method: PUT
         * 
         * @param string $id
         * @return string
         */
        public function update($id)
        {
            $url = "{$this->resource_url}/{$id}";
            return;
        }

        /**
         * return the url for the delete interaction
         * method: DELETE
         * 
         * @param string $id
         * @return string
         */
        public function delete($id)
        {
            $url = "{$this->resource_url}/{$id}";
            return;
        }

        /**
         * return the url for the history interaction
         * method: GET
         * 
         * @param string $id
         * @return string
         */
        public function history($id)
        {
            $url = "{$this->resource_url}/{$id}/_history";
            return;
        }

        /**
         * return the url for the create interaction
         * method: POST
         * 
         * @return string
         */
        public function create()
        {
            $url = "{$this->resource_url}/";
            return;
        }
        
        /**
         * return the url for the transaction interaction
         * method: POST
         * 
         * @return string
         */
        public function transaction()
        {
            $url = $this->base_URL;
            return;
        }

        /**
         * return the url for the operation interaction
         * method: GET
         * 
         * @param string $id
         * @param string $operation_name
         * @return string
         */
        public function operation($id, $operation_name)
        {
            $url = "{$this->resource_url}/{$id}/{$operation_name}";
            return;
        }

        /**
         * get the query params string
         * - params with keys not included in $valid_keys are ignored
         * - arrays are transformed in comma separated strings
         *
         * @param array $params 
         * @param array $accepted_keys additional accepted param keys
         * @return void
         */
        protected function filterParams($params, $accepted_keys)
        {
            $filtered_params = array();
            $accepted_params = array_merge($accepted_keys, self::$common_parameters);

            foreach ($params as $key => $value) {
                if(empty($value)) continue;
                if(!in_array($key,$accepted_params)) continue;
                $filtered_params[$key] = $value;
            }

            return $filtered_params;
        }

                /**
         * get data from a FHIR endpoint
         *
         * @param string $url
         * @param string $access_token
         * @param array $headers
         * @return string HTTP response body
         */
        protected function getFhirData($url=null, $access_token=null, $headers=array())
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
         * get an enpoint instance using a resource type
         *
         * @param string $resource_type
         * @param string $base_URL
         * @param string $access_token
         * @return FhirEndpoint
         */
        public static function getInstance($resource_type, $base_URL, $access_token)
        {
            $ClassName = __NAMESPACE__."\FhirEndpoint{$resource_type}";
            if(!class_exists($ClassName)) throw new \Exception("Error: cannot instantiate class {$ClassName}", 1);
            $instance = new $ClassName($base_URL, $access_token);
            return $instance;
        }

        /**
         * build the endpoint URL
         *
         * @param string $base_url
         * @param string $patient_id
         * @param array $properties
         * @return void
         */
        private function buildURL($base_url, $patient_id, $properties)
        {
            global $fhir_convert_timestamp_from_gmt;
            
            $url = '';

            switch ($this->name) {
                case 'Patient':
                    // Set URL
                    $url = $base_url . "$this->name/$patient_id";
                    break;
                case 'MedicationOrder':
                    // If "Medications" endpoint for "Active medications list", then set specific URL for it
                    // Set URL
                    $status_list = array(
                        \FhirResourceMedicationOrder::STATUS_ACTIVE,
                        \FhirResourceMedicationOrder::STATUS_COMPLETED,
                        \FhirResourceMedicationOrder::STATUS_ON_HOLD,
                        \FhirResourceMedicationOrder::STATUS_STOPPED,
                    );
                    $requested_status = array();
                    // build a regexp that matches all available medication status
                    $regExp = sprintf("/^(%s)-medications-list\$/i", implode('|', $status_list));
                    foreach ($properties['fields'] as $field) {
                        preg_match($regExp, $field, $matches);
                        if($matches)
                        {
                            $requested_status[] = $matches[1]; // get the matched status
                        }
                    }
                    $status = implode(',', $requested_status);
                    $url = $base_url . "$this->name?patient="  . urlencode($patient_id) . "&status=".$status;
                    break;
                case 'Condition':
                    // If "Condition" endpoint for "Problem list and health concerns", then set specific URL for it
                    // Set URL
                    $url = $base_url . "$this->name?patient=" . urlencode($patient_id); // . "&category=problem,complaint,symptom,finding,diagnosis,health-concern";
                    break;
                case 'AllergyIntolerance':
                    // If "AllergyIntolerance" endpoint for "Allergies", then set specific URL for it
                    // Set URL
                    $url = $base_url . "$this->name?patient=" . urlencode($patient_id);
                    break;
                case 'Observation':
                default:
                    if (isset(\FhirEhr::$fhirEndpointQueryStringCodeParameter[$this->name])) {
                        // Get param names, which may differ for endpoints
                        $dateParamName = \FhirEhr::$fhirEndpointQueryStringDateParameter[$this->name];
                        $codeParamName = \FhirEhr::$fhirEndpointQueryStringCodeParameter[$this->name];
                        // Set min/max dates
                        $minDate = $maxDate = "";
                        if (!($properties['minDate'] == '' || $dateParamName == '')) {
                            // If dealing with GMT conversion, open window of time by one extra day to compensate for local time offset from GMT				
                            if ($fhir_convert_timestamp_from_gmt == '1') {
                                $properties['minDate'] = date(self::FHIR_DATETIME_FORMAT, strtotime($properties['minDate'] . ' - 1 days'));
                            }
                            // Set param
                            // $minDate = "&{$dateParamName}=gt" . str_replace("-", "", $properties['minDate']); // TJ commented out
                            $minDate = "&{$dateParamName}=gt" . $properties['minDate']; // TJ according to the HL7 FHIR convention, date params SHOULD be hyphentated, no?
                        }
                        if (!($properties['maxDate'] == '' || $dateParamName == '')) {
                            // If dealing with GMT conversion, open window of time by one extra day to compensate for local time offset from GMT
                            if ($fhir_convert_timestamp_from_gmt == '1') {
                                $properties['maxDate'] = date(self::FHIR_DATETIME_FORMAT, strtotime($properties['maxDate'] . ' + 1 days'));
                            }
                            // Set param
                            // $maxDate = "&{$dateParamName}=lt" . str_replace("-", "", $properties['maxDate']); // TJ commented out - see comment for "gt" above
                            $maxDate = "&{$dateParamName}=lt" . $properties['maxDate'];
                        }
                        // add the coding system in front of the code. this is ignored by Epic but required in cerner
                        $properties['fields'] = array_map(function($val) { return 'http://loinc.org|'.$val;} , $properties['fields']);
                        // Set fields, codes, etc.
                        $fields = empty($properties['fields']) ? "" : "&{$codeParamName}=" . urlencode(implode(",", $properties['fields']));
                        // Set URL
                        $url = \FhirEhr::getFhirEndpointBaseUrl() . "$this->name?patient=" . urlencode($patient_id) . $minDate . $maxDate . $fields;
                    }
                    break;
            }
            return $url;
        }
        
    }
}