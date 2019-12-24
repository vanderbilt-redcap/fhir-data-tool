<template>
  <div id="resource-container">
    <slot></slot>
    <ResourceRawData v-if="source" :raw_data="source" />
  </div>
</template>

<script>
// the resource container shows the raw data and allows
// to embed additional elements
import ResourceRawData from '@/components/resource/ResourceRawData'

import observation_json from '@/assets/observation'
import vital_signs_json from '@/assets/vital_signs'

export default {
  name: 'resource-container',
  components: {
    ResourceRawData,
  },
  data: () => ({}),
  computed: {
    resource() {
      const { resource={} } = this.$store.state.resource
      return resource
    },
    data() {
      const {data={}} = this.resource
      return data
    },
    source() {
      try {
        const {metadata:{source={}}} = this.resource
        return source
      } catch (error) {
        return false
      }
    }
  },
  methods: {}
}
</script>

<style scoped>
</style>
