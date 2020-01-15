<template>
  <div class="fhir-form">

    <form class="my-2" @submit.prevent="onSubmit">
      <div class="form-group mb-2">
        <label for="mrn" class="mr-2">Medical Record Number</label>
        <input type="text" class="form-control" id="mrn" v-model="mrn" placeholder="MRN">
      </div>
      
      <slot></slot>
      
      <div class="buttons my-2">
        <button class="btn btn-info" type="button" @click="onCleanClick">
          <span class="mr-2">clean results</span>
          <i class="fas fa-redo"></i>
        </button>
        <button class="btn btn-primary" type="submit" :disabled="!canSubmit || loading">
          <span class="mr-2">Fetch</span>
          <i v-if="loading" class="fas fa-spinner fa-spin"></i>
          <i v-else class="fas fa-cloud-download-alt"></i>
        </button>
      </div>
    </form>

  </div>
</template>

<script>
import {Interaction} from '@/libraries'


export default {
  name: 'FhirForm',
  components: {  },
  data: () => ({
    loading: false,
  }),
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
    interaction() {
      const {name:route_name} = this.$route
      switch (route_name) {
        case 'patient':
          return new Interaction('read', 'Patient')
          break;
        case 'medication-order':
          return new Interaction('search', 'MedicationOrder')
          break;
        case 'observation':
          return new Interaction('search', 'Observation')
          break;
        case 'condition':
          return new Interaction('search', 'Condition')
          break;
        default:
          break;
      }
    }
  },
  methods: {
    async onSubmit() {
      const interaction = this.interaction
      console.log(interaction)
      if(!interaction) {
        alert('invalid interaction')
        return
      }
      const mrn = this.$store.state.endpoint.mrn
      /* const all_params = this.$store.state.endpoint.params // global params object. contains params for every endpoint
      const params = all_params[endpoint] || [] // get extra params for the current endpoint */
      const params = this.$route.query
      try {
        this.loading = true
        const resource = await this.$store.dispatch('resource/fetchResource', {interaction, mrn, params})
      } catch (error) {
        console.error(error)
      }finally {
        this.loading = false
      }
    },
    onCleanClick()
    {
      this.$store.dispatch('resource/reset')
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
