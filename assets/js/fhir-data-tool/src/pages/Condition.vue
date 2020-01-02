<template>
  <div class="home-page">
    <FhirForm class="fhir-form">

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
        <!-- codings are grouped by condition??? -->
        <tbody v-for="(group, index) in grouped_codings" :key="index">
          <tr v-for="(coding, coding_index) in group" :key="coding_index">
            <td :style="getGroupStyle(index)">{{index}}</td>
            <td>{{coding.display}}</td>
            <td>{{coding.code}}</td>
            <td>{{coding.system}}</td>
            <td>{{coding.value}}</td>
            <td>{{coding.date}}</td>
          </tr>
        </tbody>
      </table>

    </div>
    <ResourceContainer />
  </div>
</template>

<script>
import Bundle from '@/libraries/FhirResource/Bundle'
import Condition from '@/libraries/FhirResource/Condition'

import FhirForm from '@/components/FhirForm'
import ResourceContainer from '@/components/ResourceContainer'

export default {
  name: 'ConditionPage',
  data: () => ({}),
  components: {
    FhirForm,
    ResourceContainer,
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
        const entries = bundle.getEntriesOfType(Observation)
        return entries
      } catch (error) {
        console.log(error)
        return []  
      }
    },
  },
  methods: {},
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

td.centered {
  vertical-align: middle;
  text-align: center;
}
</style>