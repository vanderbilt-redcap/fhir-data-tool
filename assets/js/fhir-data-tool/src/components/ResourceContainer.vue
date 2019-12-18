<template>
  <div id="resource-container">
    <!-- <ObservationTable :rows="rows" /> -->
    <component :is="resource_table" v-bind="resource_table_props"/>
    <ResourceRawData :raw_data="source" />
    <button @click="onClick">extract</button>
  </div>
</template>

<script>
import ResourceRawData from '@/components/resource/ResourceRawData'
// import ObservationTable from '@/components/ObservationTable'
// import {test} from '@/libraries'
import {Bundle} from '@/libraries'

import observation_json from '@/assets/observation'
import vital_signs_json from '@/assets/vital_signs'


export default {
  name: 'resource-container',
  components: {
    ResourceRawData,
    // ObservationTable,
  },
  data: () => ({
    resource_table: null,
    resource_table_props: {},
  }),
  props: {
    data: {
      type: Object,
    }
  },
  watch: {
    source() {
      this.renderData()
    }
  },
  computed: {
    endpoint() {
      const endpoint = this.$store.state.endpoint.current
      return endpoint
    },
    /**
     * get the resource from the store
     */
    resource() {
      const { resource={} } = this.$store.state.resource
      return resource
    },
    /**
     * extract the source from a resource
     */
    source() {
      try {
        const { metadata: { source={} } } = this.resource
        return source
      } catch (error) {
        return {}
      }
    },
  },
  methods: {
    renderData() {
      try {
        const source = this.source
        const {resourceType} = source
        
        switch (resourceType) {
          case 'Bundle':
            this.renderObservationBundleData()
            break
          case 'Patient':
            this.renderPatientData()
            break;
          default:
            this.renderResourceData()
            break;
            break
        }
      } catch (error) {
        console.log(error)
      }
    },
    renderObservationBundleData() {
      const bundle = new Bundle(this.source)
      const entries = bundle.entries
      const rows = []
      entries.forEach((entry, index) => {
        const entry_rows = this.getRowsFromEntry(entry)
        const rows_with_group  = entry_rows.map(row => ({...row, _group: index}))
        rows.push(...rows_with_group)
      })
      const headers = {
        code: 'Code',
        system: 'System',
        display: 'Description (from EHR, not from REDCap mapping)',
        value: 'Value',
        date: 'Date/time of service',
      }
      this.resource_table = () => import('@/components/tables/ResourceTable')
      this.resource_table_props = {rows,headers}
    },
    renderResourceData() {
      console.log('rendering a resource')
    },
    renderPatientData() {
      const {data} = this.resource
      // const headers = Object.keys(data)
      const headers = {
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
      
      this.resource_table_props = {rows:[data], headers}
      this.resource_table = () => import('@/components/tables/ResourceTable')
    },
    getRowsFromEntry(entry) {
      const className = entry.constructor.name
      const rows = []
      switch (className) {
        case 'Observation':
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
          break
        default:
          break
      }
      return rows
    },
    onClick() {
      
    }
  }
}
</script>

<style scoped>
</style>
