<template>
  <div class="home-page">
    <FhirForm class="fhir-form"></FhirForm>
    <ResourceContainer />
  </div>
</template>

<script>
import FhirForm from '@/components/FhirForm'
import ResourceContainer from '@/components/ResourceContainer'
import ResourceTable from '@/components/tables/ResourceTable'

const table_headers = {
    'first_name': 'first name',
    'last_name': 'last name',
    'gender': 'gender',
    'gender_code': 'gender code',
    'ethnicity': 'ethnicity',
    'ethnicity_code': 'ethnicity code',
    'race': 'race',
    'race_code': 'race code',
    'birthdate': 'birthdate',
    'address_city': 'address city',
    'address_country': 'address country',
    'address_postal_code': 'address postal code',
    'address_state': 'address state',
    'address_line': 'address line',
    'phone_home': 'phone home',
    'phone_mobile': 'phone mobile',
    'email': 'email',
    'is_deceased': 'is deceased',
}

export default {
  name: 'PatientPage',
  components: {
    FhirForm,
    ResourceContainer,
    ResourceTable
  },
  data: () => ({
      table_headers,
  }),
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