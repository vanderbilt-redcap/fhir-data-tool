<?php
namespace REDCap\FhirDataTool\App\Models
{
    use REDCap\FhirDataTool\App\Models\FhirEndpoint;

    class FhirEndpointObservation extends FhirEndpoint
    {
        const NAME = 'Observation';

        public function search($params)
        {
            $valid_keys = array(
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

            $query_params = $this->getQueryParams($params, $valid_keys);
            $url = "{$this->base_URL}".self::NAME."?{$query_params}";
            return $url;
        }

    }
}
