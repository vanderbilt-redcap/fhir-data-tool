<template>
  <div class="home-page">
    <FhirForm class="fhir-form">
      <MedicationOrderFields />
    </FhirForm>
    <table class="table table-striped table-bordered" v-if="entries.length">
      <thead>
        <th>medication_reference</th>
        <th>status</th>
        <th>dosage_instruction</th>
        <th>date</th>
      </thead>
      <tbody>
        <tr v-for="(entry, index) in entries" :key="index">
          <td>{{entry.medication_reference}}</td>
          <td>{{entry.status}}</td>
          <td>
            <div v-for="(dosage_instruction, dosage_index) in entry.dosage_instructions" :key="dosage_index">
              {{dosage_instruction}}
              {{dosage_instruction.route}}
            </div>
          </td>
          <td>{{formatDate(entry.date)}}</td>
        </tr>
      </tbody>
    </table>
    <ResourceContainer />
  </div>
</template>

<script>
import FhirForm from '@/components/FhirForm'
import ResourceContainer from '@/components/ResourceContainer'
import MedicationOrderFields from'@/components/medication-order/MedicationOrderFields'

import {formatDate} from '@/libraries'

export default {
  name: 'MedicationOrderPage',
  components: {
    FhirForm,
    ResourceContainer,
    MedicationOrderFields,
  },
  computed: {
    entries() {
      try {
        const {medication_orders:entries=[]} = this.$store.state.resource
        return entries
      } catch (error) {
        console.log(error)
        return []  
      }
    }
  },
  methods: {
    /**
     * formatDate from Utils
     */
    formatDate,
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>