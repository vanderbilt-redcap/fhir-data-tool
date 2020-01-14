const initialState = {
    mrn: '',
    params: {},
}

const module = {
    namespaced: true,
    state: {...initialState},
    mutations: {
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
        reset(context) {
            context.commit('SET_PARAMS', initialState.params)
        },
        setMRN(context, mrn) {
            context.commit('SET_MRN',mrn)
        },
        setParams(context, params) {
            context.commit('SET_PARAMS',params)
        },
    }
}

export default module;