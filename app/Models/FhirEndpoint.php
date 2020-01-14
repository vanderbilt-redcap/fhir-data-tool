<?php
namespace REDCap\FhirDataTool\App\Models
{

    class FhirEndpoint
    {

        /**
         * prefixes for values or ranges
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
         * create a FHIR endpoint
         *
         * @param object $field_info
         */
        public function __construct()
        {
            $this->base_URL = \FhirEhr::getFhirEndpointBaseUrl();
        }

        /**
         * get the query params string
         * - params with keys not included in $valid_keys are ignored
         * - arrays are transformed in comma separated strings
         *
         * @param array $params 
         * @param array $valid_keys accepted param keys
         * @return void
         */
        protected function getQueryParams($params, $valid_keys)
        {
            $filtered_params = array();
            $valid_keys = array_merge($valid_keys, self::$common_parameters);

            foreach ($params as $key => $value) {
                if(empty($value)) continue;
                if(!in_array($key,$valid_keys)) continue;
                if(is_array($value)) $value = implode(', ', $value); // trasform array in comma separated values (FHIR compatible)
                $filtered_params[$key] = $value;
            }

            $query_params = http_build_query($filtered_params);
            return $query_params;
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