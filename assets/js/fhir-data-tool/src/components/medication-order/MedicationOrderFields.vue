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
export default {
  name: 'MedicationOrderFields',
  data: () => ({ 
    status_list: [
      'active',
      'completed',
      'on-hold',
      'stopped',
    ],
    checked: [],
  }),
  created() {
    const params = this.$store.state.endpoint.params
    const {status=[]} = params.MedicationOrder || {}
    this.checked = status
  },
  watch: {
    checked() {
      const value = {status: this.checked}
      /* this.checked.forEach(status => params.push(`status=${status}`) )
      const value = params.join('&') */
      this.$store.dispatch('endpoint/setParam',{key:'MedicationOrder', value})
    }
  },
  computed: {
    /* checked: {
      get() {
        const params = this.$store.state.endpoint.params
        const checked = params.MedicationOrder || []
        return checked
      },
      set(value) {
        this.$store.dispatch('endpoint/setParam',{key:'MedicationOrder', value })
      },
    } */
  },
  methods: {
    
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
