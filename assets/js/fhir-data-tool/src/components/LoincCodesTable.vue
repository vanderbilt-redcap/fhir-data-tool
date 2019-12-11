<template>
  <div id="loinc-codes-table">
    <h1>LOINC Codes</h1>
    {{loinc_codes}}
  </div>
</template>

<script>

export default {
  name: 'loinc-codes-table',
  components: {

  },
  data: () => ({

  }),
  props: {
    loinc_codes: {
      type: Array,
      default: []
    }
  },
}
</script>

<style scoped>

</style>
