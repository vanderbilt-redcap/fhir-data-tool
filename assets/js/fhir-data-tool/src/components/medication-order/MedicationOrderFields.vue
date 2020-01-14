<template>
  <div class="medicationorder-params">
    <fieldset>Medication status</fieldset>
    <div class="form-check form-check-inline" v-for="(status, index) in status_list" :key="index" >
      <input class="form-check-input" type="checkbox" name="status" :id="`status-${index}`" :value="status" v-model="checked">
      <label class="form-check-label" :for="`status-${index}`">
        {{status}}
      </label>
    </div>
  </div>
</template>

<script>
import {medication_status_list} from '@/variables'
import qs from 'qs'

export default {
  name: 'MedicationOrderFields',
  data: () => ({ 
    status_list: medication_status_list,
    checked: [],
  }),
  created() {
    const {status=[]} = this.$route.query
    this.checked = status
  },
  watch: {
    checked(value) {
      const query = Object.assign({}, this.$route.query)
      query.status = [...this.checked]
      // exit if the query has not been changed
      // if(JSON.stringify(this.$route.query)==JSON.stringify(query)) return
      this.$router.push({query: {status: [...this.checked]}}).catch(() => {
        console.log('query params not changed')
      })
    }
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
