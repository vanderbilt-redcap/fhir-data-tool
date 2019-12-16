<template>
  <div id="resource-container">
    <ResourceRawData :raw_data="source" />
    <LoincCodesTable :loinc_codes="[1,2,3]" />
    <button @click="onClick">extract</button>
  </div>
</template>

<script>
import ResourceRawData from '@/components/resource/ResourceRawData'
import LoincCodesTable from '@/components/LoincCodesTable'
// import {test} from '@/libraries'
import {FhirResource, FhirResourceBundle} from '@/libraries'
import {search} from 'jmespath'

const test = new FhirResourceBundle({})

export default {
  name: 'resource-container',
  components: {
    ResourceRawData,
    LoincCodesTable,
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
    }
  },
  methods: {
    onClick() {
      try {
        const source = this.source
        const {resourceType} = source

        var resource = null
        switch (resourceType) {
          case 'Bundle':
            resource = new FhirResourceBundle(source)
            const entries = resource.entries
            entries.forEach(entry => {
              const codings = entry.codings
            })
            break;
        
          default:
            break;
        }
      } catch (error) {
        console.log(error)
      }
    }
  }
}
</script>

<style scoped>
</style>
