<template>
  <section class="app">
    <button @click="test">test</button>
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

import Modal from '@/plugins/Modal'
Vue.use(Modal)


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
    },
    test() {
      const body_component = () => import('@/components/BaseComponent')
      console.log(body_component)
      body_component.store = this.$store

      this.$RcModal.fire({
        component: body_component,
        // body_properties: {msg:'ciaociao'},
        body: 1234,
        confirm_text: 'download',
        onConfirm: () => console.log(123)
        // footer: '',
        // onOk:()=>console.log(123),
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
