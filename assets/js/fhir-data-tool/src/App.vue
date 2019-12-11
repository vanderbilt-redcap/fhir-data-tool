<template>
  <section class="app">
    <router-view/>
  </section>
</template>

<script>



export default {
  name: 'app',

}
</script>

<style scoped>
.app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
  width: 80%;
  margin: 60px auto;
}
</style>
