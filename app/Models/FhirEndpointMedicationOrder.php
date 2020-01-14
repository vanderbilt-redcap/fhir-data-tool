<?php
namespace REDCap\FhirDataTool\App\Models
{
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;

    class FhirEndpointMedicationOrder extends FhirEndpoint
    {
        const NAME = 'MedicationOrder';

        public function search($params)
        {
            $valid_keys = array(
                'patient',
                'status',
                'code',
                'datewritten',
                'encounter',
                'identifier',
                'medication',
                'prescriber',
            );

            $query_params = $this->getQueryParams($params, $valid_keys);
            $url = "{$this->base_URL}".self::NAME."?{$query_params}";
            return $url;
        }

    }
}
