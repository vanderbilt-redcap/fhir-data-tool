
<template>
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
        <router-link class="nav-link" exact-active-class="active" :to="{name: 'condition'}">Condition</router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" exact-active-class="active" :to="{name: 'help'}">Help</router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" exact-active-class="active" :to="{name: 'fhir_metadata'}">FHIR Metadata</router-link>
      </li>
      <li class="nav-item">
        <router-link class="nav-link" exact-active-class="active" :to="{name: 'project_info'}">Project Info</router-link>
      </li>
    </ul>
</template>

<script>

export default {
  name: 'MenuLayout',
}
</script>

<style scoped>

@media only screen and (max-width: 768px) {

}
</style>