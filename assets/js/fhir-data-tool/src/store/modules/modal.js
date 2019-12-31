import Vue from 'vue'

const initialState = {
    show: false,
    header: '',
    body: '',
    body_component: null,
    body_properties: {},
    footer: '',
}

const module = {
    namespaced: true,
    state: {...initialState},
    mutations: {
        SET_SHOW: function(state, payload) {
            state.show = payload
        },
        SET_HEADER: function(state, payload) {
            state.header = payload
        },
        SET_BODY: function(state, payload) {
            state.body = payload
        },
        SET_BODY_PROPERTIES: function(state, payload) {
            state.body_component = payload
        },
        SET_BODY_COMPONENT: function(state, payload) {
            state.body_component = payload
        },
        SET_FOOTER: function(state, payload) {
            state.footer = payload
        },
    },
    actions: {
        /**
         * set all states using a config object
         * @param {object} context 
         * @param {object} config 
         */
        fire(context, config) {
            const {header, body, body_component, body_properties, footer} = config
            if(header) context.dispatch('setHeader', header)
            if(body) context.dispatch('setBody', body)
            if(body_component) context.dispatch('setBodyComponent', body_component)
            if(body_properties) context.dispatch('setBodyProperties', body_properties)
            if(footer) context.dispatch('setFooter', footer)
            context.dispatch('show')
        },
        setHeader(context, text) {
            context.commit('SET_HEADER',text)
        },
        setBody(context, text) {
            context.commit('SET_BODY',text)
        },
        setBodyComponent(context, component) {
            context.commit('SET_BODY_COMPONENT',component)
        },
        setBodyProperties(context, properties={}) {
            context.commit('SET_BODY_PROPERTIES',properties)
        },
        setFooter(context, text) {
            context.commit('SET_FOOTER',text)
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