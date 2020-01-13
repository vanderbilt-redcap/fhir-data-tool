<template>
  <section class="app">
    <router-view/>
  </section>
</template>

<script>
import Vue from 'vue'
import store from '@/store'
import router from '@/router'

import API from '@/API/plugin'
Vue.use(API)

import App from '@/App.vue'


export default {
  name: 'app',
  methods: {
    /**
     * load data needed for the app
     */
    loadRemoteData() {
      const component = () => import('@/components/DataLoader')
      this.$store.dispatch('modal/fire',{
        component,
        prevent_closing: true,
        footer: '',
        component_properties: {
          // hide the modal when loading is complete
          onDone: () => this.$store.dispatch('modal/hide')
        },
      })
    }
  },
  mounted() {
    this.loadRemoteData()
  },
  store,
  router,
}
</script>

<style>
.router-link-exact-active {
  color: black;
  /* font-weight: bold; */
}
</style>
<style scoped>
.app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  /* text-align: center; */
  color: #2c3e50;
  margin-top: 60px;
  width: 80%;
  margin: 0px auto;
}
</style>
