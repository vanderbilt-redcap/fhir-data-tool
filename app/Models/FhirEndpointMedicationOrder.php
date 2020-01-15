<?php
namespace REDCap\FhirDataTool\App\Models
{
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;

    class FhirEndpointMedicationOrder extends FhirEndpoint
    {
        const RESOURCE_TYPE = 'MedicationOrder';

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

    }
}
