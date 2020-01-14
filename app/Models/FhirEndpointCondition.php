<?php
namespace REDCap\FhirDataTool\App\Models
{
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;

    class FhirEndpointCondition extends FhirEndpoint
    {
        const NAME = 'Condition';

        public function search($params)
        {
            $valid_keys = array(
                'asserter',
                'body-site',
                'category',
                'clinicalstatus',
                'code',
                'date-recorded',
                'encounter',
                'evidence',
                'identifier',
                'onset',
                'onset-info',
                'patient',
                'severity',
                'stage',
            );
            
            $query_params = $this->getQueryParams($params, $valid_keys);
            $url = "{$this->base_URL}".self::NAME."?{$query_params}";
            return $url;
        }

    }
}
