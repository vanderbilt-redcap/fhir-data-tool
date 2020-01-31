<?php
namespace REDCap\FhirDataTool\App\Models
{
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;

    class FhirEndpointMedicationOrder extends FhirEndpoint
    {
        const RESOURCE_TYPE = 'MedicationOrder';

        /** 
         * Map the FHIR endpoint category to the name of the query string "code" parameter.
         * Note: Most categories do not have this structure.
         */
        const DATE_PARAMETER_NAME = 'dateWritten';

        public function search($params)
        {
            $accepted_keys = array(
                'patient',
                'status',
                'code',
                'datewritten',
                'encounter',
                'identifier',
                'medication',
                'prescriber',
            );

            $filtered_params = $this->filterParams($params, $accepted_keys);
            return parent::search($filtered_params);
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
            if(!empty($requested_status)) $params['status'] = $requested_status;
            return $params;
        }
    }
}
