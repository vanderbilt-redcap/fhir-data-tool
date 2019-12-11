import Vue from 'vue'
Vue.config.productionTip = false

import store from '@/store'
import { router } from './router'

import API from '@/API/plugin'
Vue.use(API)

import App from './App.vue'


new Vue({
    store: store,
    router: router,
    render: h => h(App),
}).$mount('#app')