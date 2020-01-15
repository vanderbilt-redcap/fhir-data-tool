<?php
namespace REDCap\FhirDataTool\App\Models
{
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;

    class FhirEndpointObservation extends FhirEndpoint
    {
        const RESOURCE_TYPE = 'Observation';

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

    }
}
