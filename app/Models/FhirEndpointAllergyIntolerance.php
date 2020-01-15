<?php
namespace REDCap\FhirDataTool\App\Models
{
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;

    class FhirEndpointAllergyIntolerance extends FhirEndpoint
    {
        const RESOURCE_TYPE = 'AllergyIntolerance';

        public function search($params)
        {
            $accepted_keys = array(

            );
            $filtered_params = $this->filterParams($params, $accepted_keys);
            return parent::search($filtered_params);
        }

    }
}
