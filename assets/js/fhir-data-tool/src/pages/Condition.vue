<template>
  <div >
    <FhirForm class="fhir-form"></FhirForm>

    <div v-if="entries">
      <table class="table table-striped table-bordered">
        <thead>
          <th>#</th>
          <th>Description (from EHR, not from REDCap mapping)</th>
          <th>Status</th>
          <th>System</th>
          <th>Code</th>
          <th>Date/time</th>
        </thead>
        <!-- codings are grouped by condition??? -->
        <tbody v-for="(group, group_index) in groups" :key="group_index">
          <tr v-for="(row, index) in group" :key="index">
            <td >{{group_index}}</td>
            <td>{{row.display}}</td>
            <td>{{row.status}}</td>
            <td>{{row.system}}</td>
            <td>{{row.code}}</td>
            <td>{{row.date}}</td>
          </tr>
        </tbody>
      </table>

    </div>
    <ResourceContainer />
  </div>
</template>

<script>
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
    entries() {
      try {
        const {conditions:entries=[]} = this.$store.state.resource
        return entries
      } catch (error) {
        console.log(error)
        return []  
      }
    },
    groups() {
      const entries = this.entries
      const group = {}
      entries.forEach((entry, index) => {
        const {date, status, text, codings} = entry
        if(codings.length==0) {
          const row = {
            date,
            status,
            display: text,
          }
          group[index] = [row]
        }else {
          group[index] = []
          codings.forEach(coding => {
            const row = {
              date,
              status,
              display: coding.display || text,
              system: coding.system,
              code: coding.code
            }
            group[index].push(row)
          })
        }
      })
      return group
    }
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