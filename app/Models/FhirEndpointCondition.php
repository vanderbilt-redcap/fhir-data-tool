<?php
namespace REDCap\FhirDataTool\App\Models
{
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;

    class FhirEndpointCondition extends FhirEndpoint
    {
        const RESOURCE_TYPE = 'Condition';

        public function search($params)
        {
            $accepted_keys = array(
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
            
            $filtered_params = $this->filterParams($params, $accepted_keys);
            return parent::search($filtered_params);
        }
    }
}
