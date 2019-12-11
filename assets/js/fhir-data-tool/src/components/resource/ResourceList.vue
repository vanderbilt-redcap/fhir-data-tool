<template>
  <div class="resource-list">
    <div v-for="(resource, index) in resources" :key="index">
      <ResourceRawData :raw_data="sourcesData[index]" />
      <div class="table-container">
        <ResourceTable class="table" :data="tableData[index]" />
      </div>
    </div>
  </div>
</template>

<script>
import { keysIn, reduce, merge } from 'lodash'
import ResourceTable from '@/components/resource/ResourceTable'
import ResourceRawData from '@/components/resource/ResourceRawData'

export default {
  name: 'ResourceList',
  components: {
    ResourceTable,
    ResourceRawData,
  },
  computed: {
    resources() {
      const resources = this.$store.state.resource.resources
      return resources
    },
    sourcesData() {
      const data = this.resources.map(function(resource) {
        const {metadata: {source={}}} = resource
        return source
      })
      return data
    },
    tableData() {
      const data = this.resources.map(function(resource) {
        const { metadata: {resourceType} } = resource
        switch (resourceType) {
          case 'Patient':
            /**
             * if it is a patient resource, get the data
             * and put it in an array so it can be used in
             * the ResourceRawData
             */
            const {data={}} = resource
            return [data]
            break;
          case 'Bundle':
            const {data: {entries=[]}} = resource
            return entries
            break;
          default:
            return;
            break;
        }
      })
      return data
    },
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.resource-list .table-container {
  width: 100%;
  overflow-x: auto;
}
</style>
