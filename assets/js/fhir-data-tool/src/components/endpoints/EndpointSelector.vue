<template>
  <div class="hello">
    <select v-model="endpoint">
      <option disabled value="">Please select an endpoint...</option>
      <option v-for="(endpoint, index) in endpoints" :key="index" :value="endpoint" v-text="endpoint"></option>
    </select>
  </div>
</template>

<script>
import {endpoints} from '@/variables'

export default {
  name: 'EndpointSelector',
  data: () => ({}),
  computed: {
    endpoints() {
      return endpoints
    },
    endpoint: {
      get: function() {
        return this.$store.state.endpoint.current
      },
      set: function(value) {
        this.setEndpoint(value)
      }
    },
  },
  methods: {
    setEndpoint(endpoint) {
      this.$store.dispatch('endpoint/setEndpoint', endpoint)
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
