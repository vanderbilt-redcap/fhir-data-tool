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
         * Map the FHIR endpoint category to the name of the query string "code" parameter.
         * Note: Most categories do not have this structure.
         */
        const CODE_PARAMETER_NAME = null;

	    /**
         * Map the FHIR endpoint category to the name of the query string "date" parameter to limit the request's time period.
         * Note: The 'Patient', 'Device', and 'FamilyMemberHistory' categories do not have this structure.
         */
        const DATE_PARAMETER_NAME = null;

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
        public function __construct($base_URL, $access_token=null)
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
         * alternative function for http_build_query.
         * uses the 'repeat' format instead of the 'brackets' format
         * provided by the standard http_build_query function
         *
         * @param array $params
         * @return string
         */
        public static function buildQuery($params)
        {
            $query_strings = array();
            $and_logic_keys = array('dateWritten', 'date'); //dates need to use the format QUERY_ARRAY_FORMAT_REPEAT (AND logic)
            foreach ($params as $key => $value) {
                $array_format = in_array($key, $and_logic_keys) ? \UrlQueryBuilder::QUERY_ARRAY_FORMAT_REPEAT : \UrlQueryBuilder::QUERY_ARRAY_FORMAT_COMMA;
                $query_strings[] = \UrlQueryBuilder::getQueryString($key, $value, $array_format);
            }
            $query_string = implode('&', $query_strings); // join all params
            return $query_string;
        }

        /**
         * return the url for the search interaction
         * method: GET
         * 
         * @param array $params
         * @return array
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
         * @return void
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
         * @return void
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
         * @return void
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
         * @return void
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
         * @return void
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
         * @return void
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
         * @return array
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
         * set the access token for the endpoint
         *
         * @param string $access_token
         * @return void
         */
        public function setAccessToken($access_token)
        {
            $this->access_token = $access_token;
        }

        /**
         * get an enpoint instance using a resource type
         *
         * @param string $resource_type
         * @param string $base_URL
         * @param string $access_token
         * @return FhirEndpoint
         */
        public static function getInstance($resource_type, $base_URL, $access_token=null)
        {
            $ClassName = __NAMESPACE__."\FhirEndpoint{$resource_type}";
            if(!class_exists($ClassName)) throw new \Exception("Error: cannot instantiate class {$ClassName}", 1);
            $instance = new $ClassName($base_URL, $access_token);
            return $instance;
        }

        /**
         * build the endpoint URL
         *
         * @param string $resource_type FHIR resource type
         * @param array $fields REDCap fields
         * @return array
         */
        public function getQueryParams($patient_id, $properties)
        {   
            $params = array();
            $params['patient'] = urlencode($patient_id); // valid for all search requests
            if (static::DATE_PARAMETER_NAME !== null)
            {
                // Get param names, which may differ for endpoints
                $dateParamName = static::DATE_PARAMETER_NAME;
                $date_range = $this->getDateRangeQueryParams($properties['minDate'], $properties['maxDate']);
                if(!empty($date_range)) $params[$dateParamName] = $date_range;
            }
            return $params;
        }

        /**
         * Get the min and max date parameters.
         * check the 'fhir_convert_timestamp_from_gmt' system setting and performs
         * additions/sottactions accordingly
         *
         * @param string $date_min
         * @param string $date_max
         * @return array
         */
        protected function getDateRangeQueryParams($date_min, $date_max)
        {
            global $fhir_convert_timestamp_from_gmt;

            $params = array();
            if (!empty($date_min)) {
                // If dealing with GMT conversion, open window of time by one extra day to compensate for local time offset from GMT				
                if ($fhir_convert_timestamp_from_gmt == '1') {
                    $date_min = date(self::FHIR_DATETIME_FORMAT, strtotime($date_min . ' - 1 days'));
                }
                $params['date_min'] = "gt{$date_min}";
            }
            if (!empty($date_max)) {
                // If dealing with GMT conversion, open window of time by one extra day to compensate for local time offset from GMT
                if ($fhir_convert_timestamp_from_gmt == '1') {
                    $date_max = date(self::FHIR_DATETIME_FORMAT, strtotime($date_max . ' + 1 days'));
                }
                $params['date_max'] = "lt{$date_max}";
            }
            return $params;
        }
        
    }
}