import Vue from 'vue'

const initialState = {
    show: false,
    prevent_closing: false,
    header: null,
    body: null,
    component: null,
    component_properties: {},
    footer: null,
    onConfirm: null,
    onCancel: null,
    cancel_text: 'Cancel',
    confirm_text: 'Ok',
}

const module = {
    namespaced: true,
    state: {...initialState},
    mutations: {
        SET_SHOW: function(state, payload) {
            state.show = payload
        },
        SET_PROPERTY: function(state, {key, value}) {
            state[key] = value
        },
        RESET: function(state) {
            Object.entries(initialState)
            for (let [key, value] of Object.entries(initialState)) {
                state[key] = value
            }
        },
    },
    actions: {
        /**
         * set all states using a config object
         * @param {object} context 
         * @param {object} config 
         */
        fire(context, config) {
            context.dispatch('setProperties', config)
            context.dispatch('show')
        },
        setProperties(context, properties) {
            const state_keys = Object.keys(initialState)
            for (let [key, value] of Object.entries(properties)) {
                if(state_keys.indexOf(key)>=0) {
                    context.commit('SET_PROPERTY', {key, value})
                }
            }
        },
        reset(context) {
            context.commit('RESET')
        },
        show(context, config) {
            context.commit('SET_SHOW',true)
        },
        hide(context) {
            context.commit('SET_SHOW',false)
        },
    }
}

export default module;