import Vue from 'vue'

const initialState = {
    observations: [],
    conditions: [],
    medication_orders: [],
    resource: {},
    resources: [],
}

const module = {
    namespaced: true,
    state: {...initialState},
    mutations: {
        SET_RESOURCE: function(state, {endpoint, resource}) {
            state.resource = resource
        },
        SET_RESOURCES: function(state, {resources}) {
            state.resources = resources
        },
    },
    actions: {
        async fetchResource(context, {endpoint, mrn, params}) {
            // set the list to an empty array before fetching remote data
            context.commit('SET_RESOURCE',{endpoint, resurce:{}})
            const response = await Vue.$API.getFhirResource(endpoint, mrn, params)
            const resource = response.data || {}
            return resource
        },
        setResource(context, {endpoint, resource}) {
            context.commit('SET_RESOURCE',{endpoint, resource})
        },
        setResources(context, {resources}) {
            if(!Array.isArray(resources)) resources = [resources] // convert to array
            context.commit('SET_RESOURCES',{resources})
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