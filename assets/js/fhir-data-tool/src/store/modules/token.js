import Vue from 'vue'

const initialState = {
    list: [],
}

const module = {
    namespaced: true,
    state: {...initialState},
    mutations: {
        SET_LIST: function(state, {list}) {
            state.list = list
        },
    },
    actions: {
        async fetchList(context, {user}) {
            // set the list to an empty array before fetching remote data
            context.commit('SET_LIST',{list:[]})
            const response = await Vue.$API.getTokens({user})
            console.log(response)
            const list = response.data || []
            context.commit('SET_LIST',{list})
            return list
        },
    },
}

export default module;