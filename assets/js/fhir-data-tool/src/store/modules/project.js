import Vue from 'vue'

const initialState = {
    info: {},
    datamart_revision: {},
    cdp_mapping: {},
}

const module = {
    namespaced: true,
    state: {...initialState},
    mutations: {
        SET_INFO: function(state, payload) {
            state.info = payload
        },
        SET_DATAMART_REVISION: function(state, payload) {
            state.datamart_revision = payload
        },
        SETCDP_MAPPING: function(state, payload) {
            state.cdp_mapping = payload
        },
    },
    actions: {
        async fetchInfo(context, project_id) {
            const response = await Vue.$API.getProjectInfo(project_id)
            const {data: {info, datamart_revision, cdp_mapping}} = response
            context.commit('SET_INFO',info)
            context.commit('SET_DATAMART_REVISION',datamart_revision)
            context.commit('SETCDP_MAPPING',cdp_mapping)
        },
    }
}

export default module;