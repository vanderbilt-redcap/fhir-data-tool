
<template>
    <section class="layout">
      
      <h3>menu goes here</h3>
      <router-link :to="{name: 'home'}">home</router-link>
      <router-link :to="{name: 'help'}">help</router-link>
      
      <div class="page-wrapper">
        <router-view />
      </div>
      
      <h3>footer goes here</h3>
    </section>
</template>

<script>

export default {
  name: 'MainLayout',
  components: {
  },
  data() {
    return {}
  },
  props: {},
  methods: {},
  created() {},
  computed: {}
}
</script>

<style scoped>
.layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}
.page-wrapper {
  padding: 0 25px;
  flex: 1;
}
@media only screen and (max-width: 768px) {

}
</style>