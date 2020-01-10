<template>
  <div id="base">
    <span>{{msg}}</span>
    {{test}}
  </div>
</template>

<script>


export default {
  name: 'base-component',
  props: {
    msg: {
      type: String,
      default: 'hello'
    }
  },
  computed: {
    test() {
      return this.$store.state.counter.count
    }
  }
}
</script>

<style scoped>
</style>
