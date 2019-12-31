/**
 * API interface
 */
import axios from 'axios'
import qs from 'qs'

class API {
    route = 'FhirDataToolController'; // route name for the DataMart API
    requests = {} // store ajax requests 

    constructor() {
        // 'app_path_webroot' is a global variable instantiated in REDCap
        const app_path_webroot = window.app_path_webroot || '';
        
        /* const query_params = qs.parse(location.search, { ignoreQueryPrefix: true })
        if(query_params.route) delete query_params.route
        const query = qs.stringify(query_params) */
        let base_url = `${app_path_webroot}api` // /redcap_v999.0.0/
        if(location.hostname==='localhost') base_url = '/backend/api'
        
        this.api_client= axios.create({
            baseURL: base_url,
            paramsSerializer: params => {
                /**
                 * set a serializer for the params
                 * FHIR endpoints like the repeat arrayFormat.
                 * available formats are indices, brackets, repeat, comma
                 */
                return qs.stringify(params, { arrayFormat: 'indices' })
            }
        })
    }

    getFhirResource(endpoint, mrn, params)
    {
        // https://redcap.test/API_DEV/?pid=104&route=FhirDataToolController:fhirTest&userid=delacqf"
        // const test = qs.parse('status=completed&status=stopped&status=on-hold')
        // const extra_params = qs.stringify(params)
        const route = 'FhirDataToolController:fetchFhirPatientResource'
        // get parameters from the current URL
        const query_params = qs.parse(location.search, { ignoreQueryPrefix: true })

        const request_params = {
            // route,
            endpoint: endpoint,
            mrn: mrn,
            params: params,
        }
        
        const url = '/resource'

        return this.api_client.get(url, {
            params: request_params,
        })
    }

    getTokens({user})
    {
        const route = 'FhirDataToolController:getTokens'
        // get parameters from the current URL
        const query_params = qs.parse(location.search, { ignoreQueryPrefix: true })
        const request_params = {
            route,
            user,
        }

        const url = '/tokens'
        return this.api_client.get(url, {
            params: request_params,
        })
    }

    getProjectInfo(project_id) {
        
        const request_params = {
            pid: project_id,
        }
        const url = '/project_info'
        return this.api_client.get(url, {
            params: request_params,
        })
    }

    getFhirMetadata() {
        const route = 'FhirDataToolController:getFhirMetadata'
        // get parameters from the current URL
        const query_params = qs.parse(location.search, { ignoreQueryPrefix: true })
        const request_params = {route,}

        const url = '/fhir_metadata'
        return this.api_client.get(url, {
            params: request_params,
        })
    }
}
export default API