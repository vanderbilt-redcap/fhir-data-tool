<template>
  <div class="home-page">
    <p>Medication Order</p>

    <FhirForm class="fhir-form">
      <MedicationOrderFields />
    </FhirForm>
    <ResourceContainer />
  </div>
</template>

<script>
import FhirForm from '@/components/FhirForm'
import ResourceContainer from '@/components/ResourceContainer'
import MedicationOrderFields from'@/components/medication-order/MedicationOrderFields'

export default {
  name: 'MedicationOrderPage',
  components: {
    FhirForm,
    ResourceContainer,
    MedicationOrderFields,
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