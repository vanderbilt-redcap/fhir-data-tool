<template>
  <div class="fhir-form">

    <form class="my-2" @submit.prevent="onSubmit">
      <div class="form-group mb-2">
        <label for="mrn" class="mr-2">Medical Record Number</label>
        <input type="text" class="form-control" id="mrn" v-model="mrn" placeholder="MRN">
      </div>
      <slot></slot>
    </form>

  </div>
</template>

<script>
export default {
  name: 'FhirForm',
  components: {  },
  data: () => ({
    loading: false,
  }),
  props: {
    method_name: {
      type: String,
      default: ''
    },
    resource_type: {
      type: String,
      default: ''
    },
  },
  computed: {
    mrn: {
      get() {
        return this.$store.state.endpoint.mrn
      },
      set(value) {
        this.$store.dispatch('endpoint/setMRN', value)
      },
    },
    canSubmit() {
      return Boolean(this.mrn.trim())
    },
  },
  methods: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
form {
  margin-bottom: 10px;
}
.buttons button + button {
  margin-left: 5px;
}
</style>
