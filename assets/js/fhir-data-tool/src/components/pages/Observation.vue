<template>
  <div class="home-page">
    <FhirForm class="fhir-form">
      <ObservationFields />
    </FhirForm>
    <div v-if="Object.entries(grouped_codings).length">
      <table class="table table-striped table-bordered">
        <thead>
          <th>#</th>
          <th>Description (from EHR, not from REDCap mapping)</th>
          <th>Code</th>
          <th>System</th>
          <th>Value</th>
          <th>Date/time of service</th>
        </thead>
        <!-- codings are grouped by observation -->
        <tbody :style="getGroupStyle(index)" v-for="(group, index) in grouped_codings" :key="index">
          <tr v-for="(coding, coding_index) in group" :key="coding_index">
            <td>{{index}}</td>
            <td>{{coding.display}}</td>
            <td>{{coding.code}}</td>
            <td>{{coding.system}}</td>
            <td>{{coding.value}}</td>
            <td>{{coding.date}}</td>
          </tr>
        </tbody>
      </table>
      <button type="button" class="btn btn-info my-2" @click="toggleGroups">toggle groups</button>
    </div>
    <ResourceContainer />
  </div>
</template>

<script>
import Bundle from '@/libraries/FhirResource/Bundle'
import Observation from '@/libraries/FhirResource/Observation'

import FhirForm from '@/components/FhirForm'
import ResourceContainer from '@/components/ResourceContainer'
import ObservationFields from'@/components/observation/ObservationFields'
// temp for development
import observation_json from '@/assets/observation'



export default {
  name: 'ObservationPage',
  data: () => ({
    show_groups: false,
  }),
  components: {
    FhirForm,
    ResourceContainer,
    ObservationFields,
  },
  computed: {
    resource() {
      const {resource={}} = this.$store.state.resource
      return resource
    },
    entries() {
      try {
        const {source={}} = this.resource && this.resource.metadata || {}
        const bundle = new Bundle(source)
        // make sure every entry is of type MedicationOrder
        const entries = bundle.entries.filter( entry => {
          return entry instanceof Observation
        })
        return entries
      } catch (error) {
        console.log(error)
        return []  
      }
    },
    /* codings() {
      const entries = this.entries
      const all_rows = []
      entries.forEach((entry, index) => {
        console.log(index, ...entry.codings)
        all_rows.push(...entry.codings)
      })
      return all_rows
    }, */
    grouped_codings() {
      const entries = this.entries
      const groups = {}
      entries.forEach((entry, index) => {
        groups[index] = [...entry.codings]
      })
      return groups
    },
  },
  methods: {
    toggleGroups() {
      this.show_groups = !this.show_groups
    },
    getGroupStyle(index) {
      if(!this.show_groups) return
      const rotation_delta = 160
      if(isNaN(index)) index=0
      const style = {
        filter: `hue-rotate(${rotation_delta*index}deg)`,
        backgroundColor: 'rgba(0,200,200,0.2)',
      }
      return style
    }
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>