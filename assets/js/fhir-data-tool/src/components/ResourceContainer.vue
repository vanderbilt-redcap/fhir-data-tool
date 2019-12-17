<template>
  <div id="resource-container">
    <ObservationTable :rows="rows" />
    <ResourceRawData :raw_data="source" />
    <button @click="onClick">extract</button>
  </div>
</template>

<script>
import ResourceRawData from '@/components/resource/ResourceRawData'
import ObservationTable from '@/components/ObservationTable'
// import {test} from '@/libraries'
import {Bundle} from '@/libraries'
import {search} from 'jmespath'
import observation_json from '@/assets/observation'
import vital_signs_json from '@/assets/vital_signs'


export default {
  name: 'resource-container',
  components: {
    ResourceRawData,
    ObservationTable,
  },
  data: () => ({

  }),
  props: {
    data: {
      type: Object,
    }
  },
  computed: {
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
    rows() {
      const rows = []
      try {
        const source = this.source
        const {resourceType} = source

        switch (resourceType) {
          case 'Bundle':
            const bundle = new Bundle(source)
            const entries = bundle.entries
            entries.forEach((entry, index) => {
              const entry_rows = this.getRowsFromEntry(entry)
              const rows_with_group  = entry_rows.map(row => ({...row, _group: index}))
              rows.push(...rows_with_group)
            })
            return rows
            break
          default:
            break
        }
      } catch (error) {
        console.log(error)
      }finally {
        // return rows even if there is an error
        return rows
      }
    }
  },
  methods: {
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
