import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history', // instead of hash, which is default
    base: process.env.BASE_URL,
    base: location.hostname==='localhost' ? '/' : location.pathname,
    routes
})

export default  router