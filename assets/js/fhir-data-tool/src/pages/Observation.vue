<template>
  <div class="home-page">
    <FhirForm class="fhir-form">
      <ObservationFields />
    </FhirForm>
    <ResourceContainer />
  </div>
</template>

<script>
import FhirForm from '@/components/FhirForm'
import ResourceContainer from '@/components/ResourceContainer'
import ObservationFields from'@/components/observation/ObservationFields'
// temp for development
import observation_json from '@/assets/observation'

const headers = {
  code: 'Code',
  system: 'System',
  display: 'Description (from EHR, not from REDCap mapping)',
  value: 'Value',
  date: 'Date/time of service',
}

export default {
  name: 'ObservationPage',
  components: {
    FhirForm,
    ResourceContainer,
    ObservationFields,
  },
  computed: {},
  methods: {
    getRowsFromEntry(entry) {
      const className = entry.constructor.name
      const rows = []
      const {date, values} = entry
      // loop through all values
      values.forEach(value => {
        const {codings} = value
        if(codings.length==0) {
          const row = {
            date,
            // code:'',
            display: value.display, // display from coding or from code.text
            // system:'',
            value: value.toString(),
          }
          // collect the row
          rows.push(row)
        }else {
          // loop through all codings of the value
          codings.forEach(coding => {
            // create a row
            const {code, display, system} = coding
            const row = {
              date,
              code,
              display, // display from coding or from code.text
              system,
              value: value.toString(),
            }
            // collect the row
            rows.push(row)
          })
        }
      })

      return rows
    },
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.fhir-form {
  margin-bottom: 10px;
}
</style>