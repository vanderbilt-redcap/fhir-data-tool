<template>
  <div id="loinc-codes-table">
    <h1>Values</h1>
    <table class="table table-bordered no-table-striped">
      <thead>
        <th>entry #</th>
        <th>Code</th>
        <th>System</th>
        <th>Description (from EHR, not from REDCap mapping)</th>
        <th>Value</th>
        <th>Date/time of service</th>
      </thead>
      <tbody >
        <tr v-for="(row, index) in rows" :key="index" >
          <td :style="getGroupStyle(row._group)">{{(index)}}</td>
          <td :style="getGroupStyle(row._group)">{{row.code}}</td>
          <td :style="getGroupStyle(row._group)">{{row.system}}</td>
          <td :style="getGroupStyle(row._group)">{{row.display}}</td>
          <td :style="getGroupStyle(row._group)">{{row.value}}</td>
          <td :style="getGroupStyle(row._group)">{{row.date}}</td>
        </tr>
      </tbody>
    <tfoot>

    </tfoot>
  </table>

  </div>
</template>

<script>
const keys = [
  'date',
  'code',
  'display',
  'system',
  'value',
]

export default {
  name: 'loinc-codes-table',
  components: {

  },
  data: () => ({
    rotation_delta: 50
  }),
  props: {
    rows: {
      type: Object,
      default: () => ({})
    }
  },
  methods: {

    getGroupStyle(group) {
      if(isNaN(group)) group=0
      const style = {
        filter: `hue-rotate(${this.rotation_delta*group}deg)`,
        backgroundColor: 'rgba(150,0,0,0.2)',
      }
      return style
    }
  }
}
</script>

<style scoped>

</style>
