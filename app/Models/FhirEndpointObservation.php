<?php
namespace REDCap\FhirDataTool\App\Models
{
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;

    class FhirEndpointObservation extends FhirEndpoint
    {
        const RESOURCE_TYPE = 'Observation';

        /**
         * Map the FHIR endpoint category to the name of the query string "date" parameter to limit the request's time period.
         * Note: The 'Patient', 'Device', and 'FamilyMemberHistory' categories do not have this structure.
         */
        const DATE_PARAMETER_NAME = 'date';

        /** 
         * Map the FHIR endpoint category to the name of the query string "code" parameter.
         * Note: Most categories do not have this structure.
         */
        const CODE_PARAMETER_NAME = 'code'; // or 'category', Vitals as 'category=vital-signs'

        public function search($params)
        {
            $accepted_keys = array(
                'category',
                'code',
                'code-value-[x]', // TODO: manage as regular expression
                'component-code',
                'component-code-value-[x]', // TODO: manage as regular expression
                'component-data-absent-reason',
                'component-value-concept',
                'component-value-quantity',
                'component-value-string',
                'data-absent-reason',
                'date',
                'device',
                'encounter',
                'identifier',
                'patient',
                'performer',
                'related',
                'related-target',
                'related-type',
                'specimen',
                'status',
                'subject',
                'value-concept',
                'value-date',
                'value-quantity',
                'value-string',
            );
            $filtered_params = $this->filterParams($params, $accepted_keys);
            return parent::search($filtered_params);
            
        }

        private function chunkFieldParameters($parameters, $chunk_size=50)
        {
            if(count($parameters)<=$chunk_size) return $parameters;
    
            // too many fields for this request; make chunks and create a separate endpoint for each one
            $chunks = array_chunk($parameters, $chunk_size);
            foreach ($chunks as $chunk) {
                $chunked = $parameters; // copy the original parameters
                $chunked_resource['fields'] = $chunked; // set the fields to the current chunk
            }
    
    
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
            $params = parent::getQueryParams($patient_id, $properties);
            // transform redcap code fields to fhir compatible
            $codes = $properties['fields'];
            if(!empty($codes)) {
                // add the coding system in front of the code. this is ignored by Epic but required in cerner
                array_walk($codes, function(&$field) { $field = 'http://loinc.org|'.$field;});
                $params[self::CODE_PARAMETER_NAME] = $codes;
            }
            return $params;
        }

    }
}
