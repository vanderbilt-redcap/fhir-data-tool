import Vue from 'vue'
import Bundle from '@/libraries/FhirResource/Bundle'
import Patient from '@/libraries/FhirResource/Patient'


const initialState = {
    resource: {}, // resource as fetched from endpoint
    observations: [], //entries
    conditions: [], //entries
    medication_orders: [], //entries
    patient: {}, // patient entry
}

const module = {
    namespaced: true,
    state: {...initialState},
    mutations: {
        SET_RESOURCE: function(state, resource) {
            state.resource = resource
        },
        SET_OBSERVATIONS: function(state, entries) {
            state.observations = entries
        },
        SET_CONDITIONS: function(state, entries) {
            state.conditions = entries
        },
        SET_MEDICATION_ORDERS: function(state, entries) {
            state.medication_orders = entries
        },
        SET_PATIENT: function(state, entry) {
            state.patient = entry
        },
    },
    actions: {
        reset(context) {
            context.commit('SET_RESOURCE', initialState.resource)
            context.commit('SET_OBSERVATIONS', initialState.observations)
            context.commit('SET_CONDITIONS', initialState.conditions)
            context.commit('SET_MEDICATION_ORDERS', initialState.medication_orders)
            context.commit('SET_PATIENT', initialState.patient)
        },
        async fetchResource(context, {resource_type, interaction, mrn, params}) {
            // reset before fecthing
            context.dispatch('reset')
            const response = await Vue.$API.getFhirResourceByMrn(resource_type, interaction, mrn, params)
            const resource = response.data || {}
            context.commit('SET_RESOURCE', resource)
            context.dispatch('processResource', resource)
            return resource
        },
        processResource(context, resource) {
            const {source={}} = resource.metadata || {}
            const observations = []
            const conditions = []
            const medication_orders = []
            const {resourceType} = source
            switch (resourceType) {
                case 'Bundle':
                    const bundle = new Bundle(source)
                    const {entries} = bundle
                    entries.forEach(entry => {
                        const constructor = entry.constructor.name
                        switch (constructor) {
                            case 'MedicationOrder':
                                medication_orders.push(entry)
                                break;
                            case 'Condition':
                                conditions.push(entry)
                                break;
                            case 'Observation':
                                observations.push(entry)
                                break;
                            default:
                                break;
                        }
                    })
                    context.commit('SET_OBSERVATIONS', observations)
                    context.commit('SET_CONDITIONS', conditions)
                    context.commit('SET_MEDICATION_ORDERS', medication_orders)
                    break;
                case 'Patient':
                    const patient = new Patient(source)
                    context.commit('SET_PATIENT', patient)
                    break;

                default:
                    break;
            }
        }
    },
    getters: {
        entries: state => {
            let entries = []
            try {
                const resources = state.resources
                resources.forEach(resource => {
                    const { entries:_entries=[] } = resource.data
                    entries = entries.concat(_entries)
                });
                return entries
            } catch (error) {
                return []
            }
        },
    }
}

export default module;