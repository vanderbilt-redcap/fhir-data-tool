<template>
  <div class="fhir-form">

    <form class="my-2" @submit.prevent="onSubmit">
      <div class="form-group mb-2">
        <label for="mrn" class="mr-2">Medical Record Number</label>
        <input type="text" class="form-control" id="mrn" v-model="mrn" placeholder="MRN">
      </div>
      
      <slot></slot>
      
      <div class="buttons my-2">
          <button class="btn btn-primary" type="submit" :disabled="!canSubmit" >Submit</button>
          <button class="btn btn-info" type="button" @click="onCleanClick">clean results</button>
      </div>
    </form>

  </div>
</template>

<script>

export default {
  name: 'FhirForm',
  components: {  },
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
    canSubmit() {
      return Boolean(this.mrn.trim())
    },
    endpoint() {
      const {name:route_name} = this.$route
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
      if(!endpoint) {
        alert('invalid endpoint')
        return
      }
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
.buttons button + button {
  margin-left: 5px;
}
</style>
