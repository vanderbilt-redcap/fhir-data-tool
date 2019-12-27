import Vue from 'vue'

const initialState = {
    fields: {},
}

const module = {
    namespaced: true,
    state: {...initialState},
    mutations: {
        SET_FIELDS: function(state, payload) {
            state.fields = payload
        },
    },
    actions: {
        async fetchFields(context) {
            const response = await Vue.$API.getFhirMetadata()
            const {data: {fields}} = response
            context.commit('SET_FIELDS',fields)
        },
    }
}

export default module;