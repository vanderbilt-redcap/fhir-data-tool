<?php
namespace REDCap\FhirDataTool\App\Models
{
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;

    class FhirEndpointPatient extends FhirEndpoint
    {
        public function read($ID)
        {
            $url = "{$this->base_URL}Patient/{$ID}";
            return $url;
        }

        public function search($params)
        {
            $valid_params = array(
                '_id',
                'identifier',
                'family',
                'given',
                'birthdate',
                'address',
                'gender',
                'telecom',
            );
            $filtered_params = array_intersect($params, $valid_params);
            $query_params = http_build_query($filtered_params);
            $url = "{$this->base_URL}Patient?{$query_params}";
            return $url;
        }

        /**
         * helper function to search a patient by SSN
         * @see https://www.hl7.org/fhir/identifier-registry.html
         *
         * @param [type] $ssn
         * @return void
         */
        public function searchBySsn($params)
        {
            $identifier_code = '2.16.840.1.113883.4.1'; // code for the SSN system
            $ssn = $params['identifier'];
            $identifier = sprintf("%s|%s", $identifier_code, $ssn);
            $params = compact('identifier');
            return $this->search($params);
        }
    }
}
