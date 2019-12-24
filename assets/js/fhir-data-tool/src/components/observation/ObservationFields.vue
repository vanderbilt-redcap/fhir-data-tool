<template>
  <div class="observation-params">
    <label for="observation-type">Obersvation type</label>
    <select class="form-control" id="observation-type" v-model="current_category">
      <option disabled value="">Please select one</option>
      <option v-for="(category, index) in categories" :key="index" :value="category">{{category}}</option>
    </select>
  </div>
</template>

<script>
export default {
  name: 'ObservationFields',
  data: () => ({ 
    categories: [
      'laboratory',
      'vital-signs',
      'social-history',
    ],
  }),
  computed: {
    current_category: {
      get() {
        const params = this.$store.state.endpoint.params
        const {category} = params['Observation'] || {}
        return category
      },
      set(value) {
        this.$store.dispatch('endpoint/setParam',{key:'Observation', value: {category: value}})
      },
    }
  },
  methods: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
