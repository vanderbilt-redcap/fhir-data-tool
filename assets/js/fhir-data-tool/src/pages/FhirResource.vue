<template>
  <div class="fhir-resource">
    <!-- form -->
    <FhirForm class="fhir-form">
      <router-view />
      <!-- placeholder for extra fields -->
      <router-view name="extra_fields"></router-view>
    </FhirForm>

    <!-- buttons -->

    <div class="buttons my-2">
      <button class="btn btn-primary" type="button" :disabled="!canSubmit || loading" @click="submit">
        <span class="mr-2">Fetch</span>
        <i v-if="loading" class="fas fa-spinner fa-spin"></i>
        <i v-else class="fas fa-cloud-download-alt"></i>
      </button>
      <button class="btn btn-info" type="button" @click="onCleanClick">
        <span class="mr-2">clean results</span>
        <i class="fas fa-redo"></i>
      </button>
    </div>
    <!-- placeholder for table -->
    <router-view name="table"></router-view>
    <!-- json results -->
    <ResourceContainer class="my-2"/>
  </div>
</template>

<script>
import FhirForm from '@/components/FhirForm'
import ResourceContainer from '@/components/ResourceContainer'

export default {
  name: 'FhirResource',
  components: {
    FhirForm,
    ResourceContainer,
  },
  data: () => ({
    loading: false,
    resource_types: {
      Patient: {
        to: 'patient',
        label: 'Demographics',
      },
      MedicationOrder: {
        to: 'medication-order',
        label: 'Medications',
      },
      Observation: {
        to: 'observation',
        label: 'Labs and vitals',
      },
      Condition: {
        to: 'condition',
        label: 'Problem list',
      },
    },
    methods: [
      'search',
      'read',
    ]
  }),
  computed: {
    resource_type() {
      const {resource_type} = this.$store.state.endpoint
      return resource_type
    },
    interaction() {
      const {interaction} = this.$store.state.endpoint
      return interaction
    },
    /* resource_type: {
      get() {
        const {resource_type} = {...this.$route.meta}
        return resource_type
      },
      set(value) {
        const {to} = this.resource_types[value]
        const {name} = this.$route
        if(name!=to) this.$router.push({name: to}).catch(error => {
          console.error(error)
        })
      },
    },
    method: {
      get() {
        const default_method = this.methods[0]
        const params = {...this.$route.params}
        const method = params.method || default_method
        return method
      },
      set(value) {
        const params = {...this.$route.params}
        if(value==params.method) return
        params.method = value
        this.$router.push({params}).catch(error => {
          console.error(error)
        })
      },
    }, */
    mrn() {
        return this.$store.state.endpoint.mrn
    },
    canSubmit() {
      return Boolean(this.resource_type && this.interaction && this.mrn.trim())
    },
  },
  watch: {
    $route(to, from) {
      // react to route changes amd set resource type and interaction...
      const {meta: {resource_type}, params: {method}} = to
      this.$store.dispatch('endpoint/setResourceType', resource_type)
      this.$store.dispatch('endpoint/setInteraction', method)
    }
  },
  methods: {
    async submit() {
      if(!this.canSubmit) return
      /* const all_params = this.$store.state.endpoint.params // global params object. contains params for every endpoint
      const params = all_params[endpoint] || [] // get extra params for the current endpoint */
      const resource_type = this.resource_type
      const interaction = this.interaction
      const mrn = this.mrn
      const query_params = this.$route.query
      
      // set the params in [key, value] array structure
      const params = []
      for(let key in query_params) {
        const value = query_params[key]
        params.push([key, value])
      }

      try {
        this.loading = true
        const resource = await this.$store.dispatch('resource/fetchResource', {resource_type, interaction, mrn, params})
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
</style>