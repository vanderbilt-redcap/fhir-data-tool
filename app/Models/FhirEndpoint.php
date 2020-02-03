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
         * list of the allowed interactions
         * for an endpoint
         *
         * @var array
         */
        public static $allowed_interactions = array(
            self::INTERACTION_READ,
            self::INTERACTION_UPDATE,
            self::INTERACTION_DELETE,
            self::INTERACTION_CREATE,
            self::INTERACTION_SEARCH,
            self::INTERACTION_HISTORY,
            self::INTERACTION_TRANSACTION,
            self::INTERACTION_OPERATION,
        );

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
        
        public function getReadUrl($id)
        {
            return "{$this->resource_url}/{$id}";
        }
        
        public function getSearchUrl($query_string)
        {
            return "{$this->resource_url}/?{$query_string}";
        }
        
        public function getUpdateUrl($id)
        {
            return "{$this->resource_url}/{$id}";
        }
        
        public function getDeleteUrl($id)
        {
             return "{$this->resource_url}/{$id}";
        }
        
        public function getCreateUrl()
        {
            return "{$this->resource_url}/";
        }
        
        public function getHistoryUrl($id)
        {
            return "{$this->resource_url}/{$id}/_history";
        }
        
        public function getTransactionUrl()
        {
            return $this->base_URL;
        }
        
        public function getOperationUrl($id, $operation_name)
        {
            return "{$this->resource_url}/{$id}/{$operation_name}";
        }

        /**
         * helper method to get the URL for an interaction
         *
         * @param string $interaction
         * @return string
         */
        public function getURL($interaction)
        {
            $params = func_get_args(); // get the params dynamically
            $url = '';

            switch ($interaction) {
                case self::INTERACTION_READ:
                    $id = $params[1] ?: null;
                    $url = $this->getReadUrl($id);
                    break;
                case self::INTERACTION_SEARCH:
                    $query_string = $params[1] ?: null;
                    $url = $this->getSearchUrl($query_string);
                    break;
                case self::INTERACTION_UPDATE:
                    $id = $params[1] ?: null;
                    $url = $this->getUpdateUrl($id);
                    break;
                case self::INTERACTION_DELETE:
                    $id = $params[1] ?: null;
                    $url = $this->getDeleteUrl($id);
                    break;
                case self::INTERACTION_CREATE:
                    $url = $this->getCreateUrl();
                    break;
                case self::INTERACTION_HISTORY:
                    $id = $params[1] ?: null;
                    $url = $this->getHistoryUrl($id);
                    break;
                case self::INTERACTION_TRANSACTION:
                    $url = $this->getTransactionUrl();
                    break;
                case self::INTERACTION_OPERATION:
                    $id = $params[1] ?: null;
                    $operation_name = $params[2] ?: null;
                    $url = $this->getOperationUrl($id, $operation_name);
                    break;
                default:
                    $url = $this->base_URL;
                    break;
            }
            return $url;
        }

        /**
         * alternative function for http_build_query.
         * uses the 'repeat' format instead of the 'brackets' format
         * provided by the standard http_build_query function
         *
         * @param array $params
         * @return string
         */
        /* public static function buildQuery($params)
        {
            $query_strings = array();
            foreach ($params as $settings) {
                $key = $settings['key'] ?: '';
                $value = $settings['value'] ?: '';
                $logic = $settings['logic'] ?: null;
                $array_format = in_array($key, $and_logic_keys) ? \UrlQueryBuilder::QUERY_ARRAY_FORMAT_REPEAT : \UrlQueryBuilder::QUERY_ARRAY_FORMAT_COMMA;
                $query_strings[] = \UrlQueryBuilder::getQueryString($key, $value, $array_format);
            }
            $query_string = implode('&', $query_strings); // join all params
            return $query_string;
        } */

        /**
         * return the url for the read interaction
         * method: GET
         * 
         * @param string $id
         * @return string
         */
        public function read($id)
        {
            $url = $this->getReadUrl($id);
            $data = $this->getFhirData($url, $this->access_token);
            return json_decode($data);
        }

        /**
         * return the url for the search interaction
         * method: GET
         * 
         * @param string $params
         * @return array
         */
        public function search($query_string)
        {
            $url = $this->getSearchUrl($query_string);
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
            $url = $this->getUpdateUrl($id);
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
            $url = $this->getDeleteUrl($id);
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
            $url = $this->getCreateUrl();
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
            $url = $this->getHistoryUrl($id);
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
            $url = $this->getTransactionUrl();
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
            $url = $this->getOperationUrl($id, $operation_name);
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
         * Get the min and max date parameters.
         * check the 'fhir_convert_timestamp_from_gmt' system setting and performs
         * additions/sottactions accordingly
         *
         * @param string $date_min
         * @param string $date_max
         * @return array
         */
        protected static function getDateRangeQueryParams($date_min, $date_max)
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

        public static function getQueryStringFromRedcapParameters($patient_id, $parameters)
        {
            $queryBuilder = new \UrlQueryBuilder();
            // set the patient ID
            $queryBuilder->where('patient',$patient_id);
            // manage dates (mostly conditions, labs and vitals)
            if($date_key = static::DATE_PARAMETER_NAME)
            {
                $date_min = $parameters['minDate'] ?: null;
                $date_max = $parameters['maxDate'] ?: null;
                $date_range = self::getDateRangeQueryParams($date_min, $date_max);
                $queryBuilder->where($key=$date_key, $value=$date_range, $logic=\UrlQueryBuilder::QUERY_ARRAY_FORMAT_REPEAT);
            }
            // manage codes (mostrly for labs and vitals)
            if($code_key = static::CODE_PARAMETER_NAME)
            {
                // add loinc indentificator
                array_walk($parameters['fields'], function(&$field) { $field = 'http://loinc.org|'.$field;});
                $fields = $parameters['fields'] ?: array();
                $queryBuilder->where($key=$code_key, $value=$fields, $logic=\UrlQueryBuilder::QUERY_ARRAY_FORMAT_COMMA);
            }
            return $queryBuilder->get();
        }
        
    }
}