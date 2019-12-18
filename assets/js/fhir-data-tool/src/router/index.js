import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'hash', // history | hash,  hash is default
    base: process.env.BASE_URL,
    base: location.hostname==='localhost' ? '/' : location.pathname,
    routes
})

export default  router