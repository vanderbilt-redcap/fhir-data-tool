
<template>
    <section class="layout">
      <img alt="REDCap logo" class="redcap-logo" src="@/assets/redcap_logo.png">
      <nav>
        <router-link :to="{name: 'home'}">Home</router-link>
        <router-link :to="{name: 'patient'}">Patient</router-link>
        <router-link :to="{name: 'medication-order'}">Medication Order</router-link>
        <router-link :to="{name: 'observation'}">Observation</router-link>
        <router-link :to="{name: 'help'}">Help</router-link>
      </nav>
      
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
img.redcap-logo {
  object-fit: contain;
}
.layout {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}
.page-wrapper {
  padding: 0 25px;
  flex: 1;
}
nav a + a {
  margin-left: 5px;
}
@media only screen and (max-width: 768px) {

}
</style>