<template>
  <div id="resource-container">
    <h1>container</h1>
    {{resource}}
    <ResourceRawData :raw_data="getSource(resource)" />
    <LoincCodesTable :loinc_codes="[]" />
  </div>
</template>

<script>
import ResourceRawData from '@/components/resource/ResourceRawData'
import LoincCodesTable from '@/components/LoincCodesTable'

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
    }
  },
  methods: {
    /**
     * extract the source from a resource
     */
    getSource(resource) {
      try {
        const { metadata: { source={} } } = resource
        return source
      } catch (error) {
        return {}
      }
    },
    onClick() {
      console.log(123)
    }
  }
}
</script>

<style scoped>
</style>
