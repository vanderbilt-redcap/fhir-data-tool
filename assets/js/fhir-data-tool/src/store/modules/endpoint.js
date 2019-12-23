import {observation_categories, endpoints} from '@/variables'

const initialState = {
    current: endpoints[0],
    mrn: '',
    params: {
        Observation: {
            category: observation_categories[0]
        }
    },
}

const module = {
    namespaced: true,
    state: {...initialState},
    mutations: {
        /* SET_ENDPOINT: function(state, payload) {
            if(!endpoints.includes(payload)) return
            state.current = payload
        }, */
        SET_MRN: function(state, payload) {
            state.mrn = payload
        },
        SET_PARAMS: function(state, payload) {
            state.params = payload
        },
    },
    actions: {
        /* setEndpoint(context, endpoint) {
            context.commit('SET_ENDPOINT',endpoint)
        }, */
        setMRN(context, mrn) {
            context.commit('SET_MRN',mrn)
        },
        setParam(context, {key, value}) {
            const params = Object.assign({}, context.state.params)
            params[key] = value
            context.commit('SET_PARAMS',params)
        },
    }
}

export default module;