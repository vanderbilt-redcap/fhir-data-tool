<template>
  <div class="home-page">
    <FhirForm class="fhir-form"></FhirForm>
    <div v-if="Object.keys(data).length" class="card p-2">
      <!-- print patient data -->
      <div  v-for="(value, key) in data" :key="key">
          <span class="label">{{key}}:</span><span> {{value}}</span>
      </div>
    </div>
    <ResourceContainer />
  </div>
</template>

<script>
import FhirForm from '@/components/FhirForm'
import ResourceContainer from '@/components/ResourceContainer'

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
  },
  data: () => ({
      table_headers,
  }),
  props: {
    msg: String
  },
  computed: {
    resource() {
      try {
        
        const { resource={} } = this.$store.state.resource
        const {metadata:{resourceType}} = resource
        console.log(resourceType)
        // make sure the resource is of type patient
        if(resourceType!=='Patient') return {}
        console.log(resource)
        return resource
        } catch (error) {
          return {}
        }
    },
    data() {
      const {data={}} = this.resource
      return data
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.label {
  font-weight: bold;
}
</style>