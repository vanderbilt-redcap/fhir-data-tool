<template>
  <div class="fhir-form">

    <form @submit.prevent="onSubmit">
      <div class="form-group mb-2">
        <label for="mrn" class="mr-2">MRN</label>
        <input type="text" class="form-control" id="mrn" v-model="mrn" placeholder="insert a medical record number">
      </div>
      
      <slot></slot>

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <button class="btn btn-info" @click="onCleanClick">clean results</button>
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
    endpoint() {
      const {name:route_name} = this.$route
      console.log(route_name)
      switch (route_name) {
        case 'patient':
          return 'Patient'
          break;
        case 'medication-order':
          return 'MedicationOrder'
          break;
        case 'observation':
          return 'Observation'
          break;
        case 'condition':
          return 'Condition'
          break;
      
        default:
          break;
      }
    }
  },
  methods: {
    async onSubmit() {
      const endpoint = this.endpoint
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
  margin-bottom: 10px;
}
</style>
