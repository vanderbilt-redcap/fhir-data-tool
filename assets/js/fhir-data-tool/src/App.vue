<template>
  <section class="app">
    <router-view/>
    <DataLoader />
    <ProgressBar :total="3" :progress="2"/>
  </section>
</template>

<script>
import Vue from 'vue'
import store from '@/store'
import router from '@/router'

import API from '@/API/plugin'
Vue.use(API)

import App from '@/App.vue'
import DataLoader from '@/components/DataLoader'
import ProgressBar from '@/components/ProgressBar'

export default {
  name: 'app',
  components: {DataLoader,ProgressBar,},
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
