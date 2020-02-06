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
        const default_category = observation_categories[0]
        const query = {...this.$route.query}
        const {category=default_category} = {...query}
        if(query.category != category) {
          query.category = category
          this.$router.replace({query}).catch((error) => {
            console.log(error)
        })
        }
        return category
      },
      set(value) {
        const query = {...this.$route.query}
        if(query.category == value) return
        query.category = value
        this.$router.push({query}).catch((error) => {
          console.log(error)
        })
      },
    }
  },
  methods: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
