<template>
  <div class="home-page">
    <FhirForm class="fhir-form">
      <ObservationFields />
    </FhirForm>

    <div v-if="Object.entries(grouped_codings).length">
      <table class="table table-striped table-bordered">
        <thead>
          <th><button type="button" class="btn" @click="toggleGroups">Group</button></th>
          <th>Description (from EHR, not from REDCap mapping)</th>
          <th>Code</th>
          <th>System</th>
          <th>Value</th>
          <th>Date/time of service</th>
          <th>Mapped in REDCap</th>
          <th>Mapped in project</th>
        </thead>
        <!-- codings are grouped by observation -->
        <tbody v-for="(group, index) in grouped_codings" :key="index">
          <tr v-for="(coding, coding_index) in group" :key="coding_index">
            <td :style="getGroupStyle(index)">{{index}}</td>
            <td>{{coding.display}}</td>
            <td>{{coding.code}}</td>
            <td>{{coding.system}}</td>
            <td>{{coding.value}}</td>
            <td>{{coding.date}}</td>
            <td class="centered redcap_mapped" :class="{is_mapped: isMappedInREDCap(coding)}">
              <i v-if="isMappedInREDCap(coding)" class="fas fa-check"></i>
              <i v-else class="fas fa-ban"></i>
            </td>
            <td class="centered project_mapped" :class="{is_mapped: isMappedInProject(coding)}">
              <i v-if="isMappedInProject(coding)" class="fas fa-check"></i>
              <i v-else class="fas fa-ban"></i>
            </td>
          </tr>
        </tbody>
      </table>

    </div>
    <ResourceContainer />
  </div>
</template>

<script>
import FhirForm from '@/components/FhirForm'
import ResourceContainer from '@/components/ResourceContainer'
import ObservationFields from'@/components/observation/ObservationFields'
// temp for development
import observation_json from '@/assets/observation'



export default {
  name: 'ObservationPage',
  data: () => ({
    show_groups: false,
  }),
  components: {
    FhirForm,
    ResourceContainer,
    ObservationFields,
  },
  computed: {
    /**
     * list of the mapped codes in REDCap
     */
    fhir_metadata_codes() {
      const {codes} = this.$store.state.fhir_metadata
      return codes
    },
    /**
     * list of the mapped codes in REDCap
     */
    datamart_revision() {
      const {datamart_revision} = this.$store.state.project
      return datamart_revision
    },
    cdp_mapping() {
      const {cdp_mapping} = this.$store.state.project
      return cdp_mapping
    },
    /**
     * get the list of "Vital Signs" fields mapped in REDCap
     */
    /* fhir_vital_sign_fields() {
      const {fields:all_fields, codes} = this.$store.state.fhir_metadata
      const vital_sign_metadata = all_fields['Vital Signs']
      // extract the fields from the vital signs group
      const fields = Object.entries(vital_sign_metadata).reduce((fields, [key, value])=> {
        if(Array.isArray(value)) return [...fields, ...value]
        else return [...fields, value]
      }, [])
      return fields
    }, */
    entries() {
      try {
        const {observations:entries=[]} = this.$store.state.resource
        return entries
      } catch (error) {
        console.log(error)
        return []  
      }
    },
    grouped_codings() {
      const entries = this.entries
      const groups = {}
      entries.forEach((entry, index) => {
        groups[index] = [...entry.codings]
      })
      return groups
    },
  },
  methods: {
    toggleGroups() {
      this.show_groups = !this.show_groups
    },
    getGroupStyle(index) {
      if(!this.show_groups) return
      const rotation_delta = 160
      if(isNaN(index)) index=0
      const style = {
        filter: `hue-rotate(${rotation_delta*index}deg)`,
        backgroundColor: 'rgba(0,200,200,0.2)',
      }
      return style
    },
    isMappedInREDCap(coding) {
      const codes = this.fhir_metadata_codes
      const mapped = codes.some(code => code===coding.code)
      return mapped
    },
    isMappedInProject(coding) {
      const revision = this.datamart_revision
      const cdp_mapping = this.cdp_mapping

      if(revision) return this.isMappedInDatamartRevision(coding, revision)
      else if(cdp_mapping) return this.isMappedInCdpMapping(coding, cdp_mapping)
      else return false
    },
    isMappedInDatamartRevision(coding, revision) {
      const {data: {fields}} = revision
      const mapped = fields.some(code => code===coding.code)
      return mapped
    },
    isMappedInCdpMapping(coding, mapping) {
      const mapped = mapping.some(item => item.external_source_field_name===coding.code)
      return mapped
    },

  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.project_mapped,
.redcap_mapped {
  color: red;
}
.project_mapped.is_mapped,
.redcap_mapped.is_mapped {
  color: green;
}
td.centered {
  vertical-align: middle;
  text-align: center;
}
</style>