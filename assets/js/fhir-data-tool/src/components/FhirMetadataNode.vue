<template>
    <div class="fhir-metadata-node" @innerClick="onInnerClick">
        {{expanded}}{{chidlren_expanded}}
        <ul v-for="(field, key) in fields" :key="key" class="children" >
            <li v-if="field.field">
                {{field.field}}
            </li>
            <li v-else class="category" @click="onClick($event)">
                <span class="label" >{{key}}</span>
                <FhirMetadataNode :fields="field" />
            </li>
        </ul>
    </div>
</template>

<script>
import FhirMetadataNode from '@/components/FhirMetadataNode'

export default {
  name: 'FhirMetadataNode',
  components: {
    FhirMetadataNode,
  },
  data: () => ({
      chidlren_expanded: false
  }),
  props: {
      fields: {
          type: Object|Array,
          default: {}
      },
      expanded: {
          type: Boolean,
          default: true
      },
  },
  computed: {},
  methods: {
      onClick(e) {
          this.chidlren_expanded = !this.chidlren_expanded
          console.log('onClick', e, e.target)
          this.$emit('innerClick')
      },
      onInnerClick(e) {
          //this.chidlren_expanded = !this.chidlren_expanded
          console.log('onInnerClick', e, e.target)
      }
  },
}
</script>

<style>
.fhir-metadata-node ul,
.fhir-metadata-node li {
    margin: 0;
    padding: 0;
}
.fhir-metadata-node .category > .label {
 font-weight: bold;
}
.fhir-metadata-node .children {
    margin-left: 10px;
}
</style>