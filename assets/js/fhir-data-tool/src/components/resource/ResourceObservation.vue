<template>
  <div class="resource-observation">

  </div>
</template>

<script>
import {mapActions} from 'vuex'

export default {
  name: 'ResourceObservation',
  computed: {
    counter() {
      return this.$store.state.counter.count
    },
  },
  methods: {
    ...mapActions('counter', [
      'increment',
      'decrement',
    ]),
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
