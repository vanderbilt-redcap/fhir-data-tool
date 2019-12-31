import Vue from 'vue'

const initialState = {
    info: {},
}

const module = {
    namespaced: true,
    state: {...initialState},
    mutations: {
        SET_INFO: function(state, payload) {
            state.info = payload
        },
    },
    actions: {
        async fetchInfo(context, project_id) {
            const response = await Vue.$API.getProjectInfo(project_id)
            const {data: {info}} = response
            context.commit('SET_INFO',info)
        },
    }
}

export default module;