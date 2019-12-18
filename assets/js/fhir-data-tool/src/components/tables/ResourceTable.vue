<template>
  <div id="loinc-codes-table">
    <h1>{{title}}</h1>
    <table class="table table-bordered no-table-striped">
      <thead>
        <th>entry #</th>
        <th v-for="(header, key, index) in headers" :key="index">{{header}}</th>
      </thead>
      <tbody >
        <tr v-for="(row, index) in rows" :key="index" >
          <td :style="getGroupStyle(row._group)">{{(index)}}</td>
          <td v-for="(header, header_key, header_index) in headers" :key="header_index" :style="getGroupStyle(row._group)">{{(row[header_key])}}</td>
        </tr> 
      </tbody>
    <tfoot>

    </tfoot>
  </table>

  </div>
</template>

<script>

export default {
  name: 'resource-table',
  components: {

  },
  data: () => ({
    rotation_delta: 50
  }),
  props: {
    title: {
      type: String,
      default: 'Values'
    },
    headers: {
      type: Array|Object,
      default: () => ([])
    },
    rows: {
      type: Array,
      default: () => ([])
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
