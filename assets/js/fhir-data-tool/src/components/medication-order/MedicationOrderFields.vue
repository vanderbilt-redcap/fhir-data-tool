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
    this.checked = status.split(',') //comma separated values
    // this.checked = status // repeated values
  },
  watch: {
    checked(value) {
      const query = Object.assign({}, this.$route.query)
      const status = this.checked.join(',') // comma separated values
      // const status = [...this.checked] // repeated values

      this.$router.push({query: {status}})
        .catch(() => {
          console.log('query params not changed')
        })
    }
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
