<template>
  <section class="app">
    <transition name="loader-fade" >
      <div class="loader-container" v-if="!data_loaded">
        <DataLoader  @onLoad="onLoad" />
      </div>
      <router-view v-else/>
    </transition>
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
Vue.use(Modal, {store,router})

import DataLoader from '@/components/DataLoader'

export default {
  name: 'app',
  data: () => ({
    data_loaded: false
  }),
  components: {DataLoader},
  methods: {
    onLoad() {
      this.data_loaded = true
    },
    /**
     * load data needed for the app
     */
    loadRemoteData() {
      const component = () => import('@/components/DataLoader')
      this.$RcModal.fire({
        prevent_closing: true,
        footer: '',
        component,
        component_properties: {
          // hide the modal when loading is complete
          onDone: () => this.$RcModal.hide()
        },
      })
    },
  },
  /* mounted() {
    this.loadRemoteData()
  }, */
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
.loader-container {
  position: fixed;
  left: 0;
  top: 0;
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}
/* transition */
.loader-fade-enter,
.loader-fade-leave-active {
  opacity: 0;
}
.loader-fade-enter-active,
.loader-fade-leave-active {
  transition: opacity .3s ease-out;
}

</style>
