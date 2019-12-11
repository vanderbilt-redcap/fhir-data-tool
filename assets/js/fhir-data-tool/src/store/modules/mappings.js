import Vue from 'vue'

const initialState = {
    values: [],
    key: [],
}

const module = {
    namespaced: true,
    state: {...initialState},
    mutations: {
        SET_VALUES: function(state, {values}) {
            state.values = values
        },
        SET_KEY: function(state, {key}) {
            state.key = key
        },
    },
    actions: {
        setValues(context, {values}) {
            context.commit('SET_VALUES',{resource})
        },
        setKey(context, {key}) {
            context.commit('SET_KEY',{resources})
        }
    },
    getters: {
        test: state => {},
    }
}

export default module;