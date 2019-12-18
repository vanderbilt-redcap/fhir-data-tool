<template>
  <div class="fhir-form">
    <form @submit.prevent="onSubmit">
      <EndpointSelector />
      <input type="text" name="mrn" v-model="mrn">
      <component :is="endpointForm"></component>
      <button type="submit">submit</button>
    </form>
    <button @click="onCleanClick">clean results</button>
  </div>
</template>

<script>
import EndpointSelector from '@/components/endpoints/EndpointSelector'

export default {
  name: 'FhirForm',
  components: { EndpointSelector },
  data: () => ({ }),
  computed: {
    mrn: {
      get() {
        return this.$store.state.endpoint.mrn
      },
      set(value) {
        this.$store.dispatch('endpoint/setMRN', value)
      },
    },
    endpointForm() {
      let component = null
      const endpoint = this.$store.state.endpoint.current
      switch (endpoint) {
        case 'Condition':
          component = null
          break;
        case 'MedicationOrder':
          component = () => import('@/components/endpoints/forms/MedicationOrderFields')
          break;
        case 'Observation':
          component = () => import('@/components/endpoints/forms/ObservationFields')
          break;
        default:
          break;
      }
      return component
    },
  },
  methods: {
    async onSubmit() {
      const endpoint = this.$store.state.endpoint.current
      const mrn = this.$store.state.endpoint.mrn
      const all_params = this.$store.state.endpoint.params // global params object. contains params for every endpoint
      const params = all_params[endpoint] || [] // get extra params for the current endpoint
      try {
        const resource = await this.$store.dispatch('resource/fetchResource', {endpoint, mrn, params})
        this.$store.dispatch('resource/setResource', {resource})
        // set the resources
        const resources = [resource]
        // this.$store.dispatch('resource/setResource',{resource})
        this.$store.dispatch('resource/setResources',{resources})
      } catch (error) {
        console.error(error)
      }
    },
    onCleanClick()
    {
      const resources = []
      const resource = {}
      this.$store.dispatch('resource/setResource',{resource})
      this.$store.dispatch('resource/setResources',{resources})
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
form {
  display: flex;
  margin-bottom: 10px;
  align-items: flex-start;
  justify-content: center;
}
</style>
