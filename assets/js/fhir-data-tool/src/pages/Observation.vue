<template>
  <div class="home-page">
    <p>Observation</p>

    <FhirForm class="fhir-form">
      <ObservationFields />
    </FhirForm>
    <ResourceContainer />
  </div>
</template>

<script>
import FhirForm from '@/components/FhirForm'
import ResourceContainer from '@/components/ResourceContainer'
import ObservationFields from'@/components/observation/ObservationFields'

export default {
  name: 'ObservationPage',
  components: {
    FhirForm,
    ResourceContainer,
    ObservationFields,
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