<template>
  <div class="home-page">
    <h3>FHIR tools</h3>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. A voluptatum, minima perferendis laboriosam voluptates repudiandae minus quos harum voluptas excepturi laudantium aliquid necessitatibus, assumenda dolorem illum incidunt explicabo deleniti cumque.</p>
  </div>
</template>

<script>
import FhirForm from '@/components/FhirForm'
import ResourceContainer from '@/components/ResourceContainer'

export default {
  name: 'HomePage',
  components: {
    FhirForm,
    ResourceContainer,
  },
  props: {
    msg: String
  },
  methods: {},
  computed: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.fhir-form {
  margin-bottom: 10px;
}
</style>