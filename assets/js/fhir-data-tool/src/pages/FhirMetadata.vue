<template>
  <div >
    <h3>FHIR tools</h3>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. A voluptatum, minima perferendis laboriosam voluptates repudiandae minus quos harum voluptas excepturi laudantium aliquid necessitatibus, assumenda dolorem illum incidunt explicabo deleniti cumque.</p>
    <!-- <div v-for="(group, group_key, group_index) in fhir_metadata" :key="group_index">
      {{group_key}}
      <div v-for="(category, category_key, category_index) in group" :key="category_index">
        {{category_key}}{{category}}
      </div>
    </div> -->
    <!-- <my-test /> -->
    <FhirMetadataNode :metadata="fhir_metadata" />
  </div>
</template>

<script>
import Vue from 'vue'
/* @if (is_array($value))
    <li>{{$key}}:
        <ul>
        @foreach($value as $sub_key => $sub_value)
            @include('ehr.partials.list', array('key' => $sub_key,'value' => $sub_value))
        @endforeach
        </ul>
    </li>
@else
<li>{{$key}}: {!!$value!!}</li>
@endif */

import FhirMetadataNode from '@/components/FhirMetadataNode'



export default {
  name: 'FhirMetadataPage',
  components: {
    FhirMetadataNode,
  },
  created() {
    this.$store.dispatch('fhir_metadata/fetchFields')
  },
  computed: {
    fhir_metadata() {
      const {fields} = this.$store.state.fhir_metadata
      return fields
    }
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.fhir-form {
  margin-bottom: 10px;
}
</style>