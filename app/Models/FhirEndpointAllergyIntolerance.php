<?php
namespace REDCap\FhirDataTool\App\Models
{
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;

    class FhirEndpointAllergyIntolerance extends FhirEndpoint
    {
        const NAME = 'AllergyIntolerance';

        public function search($params)
        {
            $valid_keys = array(

            );
            $query_params = $this->getQueryParams($params, $valid_keys);
            $url = "{$this->base_URL}".self::NAME."?{$query_params}";
            return $url;
        }

    }
}
