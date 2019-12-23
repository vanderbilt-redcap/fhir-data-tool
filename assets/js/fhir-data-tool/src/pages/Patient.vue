<template>
  <div class="home-page">
    <p>Patient</p>

    <FhirForm class="fhir-form"></FhirForm>
    <ResourceContainer />
  </div>
</template>

<script>
import FhirForm from '@/components/FhirForm'
import ResourceContainer from '@/components/ResourceContainer'

export default {
  name: 'PatientPage',
  components: {
    FhirForm,
    ResourceContainer,
  },
  props: {
    msg: String
  },
  methods: {},
  computed: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.fhir-form {
  margin-bottom: 10px;
}
</style>