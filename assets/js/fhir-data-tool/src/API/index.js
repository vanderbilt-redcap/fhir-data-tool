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
        this.base_url = `${app_path_webroot}api` // /redcap_v999.0.0/
        if(location.hostname==='localhost') this.base_url = 'https://localhost:8080/api'
    }

    getFhirResource(endpoint, mrn, params)
    {
        // https://redcap.test/API_DEV/?pid=104&route=FhirDataToolController:fhirTest&userid=delacqf"
        var url = `${this.base_url}`
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
        
        // extra params to be used in development mode
        if(location.hostname==='localhost') {
            request_params.userid = query_params.userid || 'delacqf'
            // ATTENTION: if the pid is not a real project the call will fail
            // request_params.pid = query_params.pid || 0 // add the project ID or the request will fail
        }
        url += '/resource'

        return axios.get(url, {
            params: request_params,
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

    getTokens({user})
    {
        var url = `${this.base_url}`
        const route = 'FhirDataToolController:getTokens'
        // get parameters from the current URL
        const query_params = qs.parse(location.search, { ignoreQueryPrefix: true })
        const request_params = {
            route,
            user,
        }
        // extra params to be used in development mode
        if(location.hostname==='localhost') {
            request_params.userid = query_params.userid || 'delacqf'
            // ATTENTION: if the pid is not a real project the call will fail
            // request_params.pid = query_params.pid || 0 // add the project ID or the request will fail
        }
        url += '/tokens'
        return axios.get(url, {
            params: request_params,
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
}
export default API