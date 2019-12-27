
<template>
    <section class="layout">
      <img alt="REDCap logo" class="redcap-logo" src="@/assets/redcap_logo.png">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <router-link class="nav-link" exact-active-class="active" :to="{name: 'home'}">Home</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" exact-active-class="active" :to="{name: 'patient'}">Patient</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" exact-active-class="active" :to="{name: 'medication-order'}">Medication Order</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" exact-active-class="active" :to="{name: 'observation'}">Observation</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" exact-active-class="active" :to="{name: 'help'}">Help</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" exact-active-class="active" :to="{name: 'fhir_metadata'}">FHIR Metadata</router-link>
        </li>
      </ul>
      
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
  padding: 25px 0;
  flex: 1;
}
nav a + a {
  margin-left: 5px;
}
@media only screen and (max-width: 768px) {

}
</style>