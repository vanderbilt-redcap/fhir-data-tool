<template>
  <div id="loinc-codes-table">
    <h1>LOINC Codes</h1>

    <table class="table table-bordered table-striped">
      <thead>
        <th>#</th>
        <th>LOINC Code</th>
        <th>Description (from EHR, not from REDCap mapping)</th>
        <th>Value</th>
        <th>Date/time of service</th>
      </thead>
      <tbody>
        <tr v-for="(code, index) in loinc_codes" :key="index">
          <td>{{index}}</td>
          <td>test</td>
          <td>test</td>
          <td>test</td>
          <td>test</td>
        </tr>
      </tbody>
    <tfoot>

    </tfoot>
  </table>

  </div>
</template>

<script>

class LoincCode {
  constructor(coding) {
    this.coding = coding
  }

  get code() {
    return this.coding.code || ''
  }

  get system() {
    return this.coding.syste || ''
  }

  get display() {
    return this.coding.displ || ''
  }

}

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
