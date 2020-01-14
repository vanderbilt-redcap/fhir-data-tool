<template>
  <div>
    <label for="observation-type">Obersvation type</label>
    <select class="form-control" id="observation-type" v-model="current_category">
      <option disabled value="">Please select a category</option>
      <option v-for="(category, index) in categories" :key="index" :value="category">{{category}}</option>
    </select>
  </div>
</template>

<script>
import {observation_categories} from '@/variables' 
export default {
  name: 'ObservationFields',
  data: () => ({ 
    categories: observation_categories,
  }),
  computed: {
    current_category: {
      get() {
        const {category} = this.$route.query
        if(!category) {
          // set the first category if not is set
          const default_category = observation_categories[0]
          this.$router.replace({query: {category: default_category}})
          return default_category
        }
        return category
      },
      set(value) {
        this.$router.push({query: {category: value}})
      },
    }
  },
  methods: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
