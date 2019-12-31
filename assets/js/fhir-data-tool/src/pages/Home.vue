<template>
  <div class="home-page">
    <h3>FHIR tools</h3>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. A voluptatum, minima perferendis laboriosam voluptates repudiandae minus quos harum voluptas excepturi laudantium aliquid necessitatibus, assumenda dolorem illum incidunt explicabo deleniti cumque.</p>
    <button type="button" @click="onClick">get mapping fields</button>
  </div>
</template>

<script>

export default {
  name: 'HomePage',
  components: {},
  methods: {
    async onClick() {
      const response = await this.$API.getFhirMetadata()
      console.log(response.data)
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