<template>
  <div class="home-page">
    <FhirForm class="fhir-form">
      <ObservationFields />
    </FhirForm>
    {{codes}}
    <div v-if="Object.entries(grouped_codings).length">
      <table class="table table-striped table-bordered">
        <thead>
          <th><button type="button" class="btn" @click="toggleGroups">Group</button></th>
          <th>Description (from EHR, not from REDCap mapping)</th>
          <th>Code</th>
          <th>System</th>
          <th>Value</th>
          <th>Date/time of service</th>
          <th>Codes to export</th>
        </thead>
        <!-- codings are grouped by observation -->
        <tbody v-for="(group, index) in grouped_codings" :key="index">
          <tr v-for="(coding, coding_index) in group" :key="coding_index">
            <td :style="getGroupStyle(index)">{{index}}</td>
            <td>{{coding.display}}</td>
            <td>
              <span>{{coding.code}}</span>
              <div v-if="!isMappedInREDCap(coding)">
                <span :style="{color:'red'}">(not available in REDCap)</span>
                <button class="btn btn-sm btn-info">
                  Notify admin <i class="fas fa-bell"></i>
                </button>
              </div>
            </td>
            <td>{{coding.system}}</td>
            <td>{{coding.value}}</td>
            <td>{{coding.date}}</td>
            <td class="centered project_mapped" :class="{is_mapped: isMappedInProject(coding)}">
              <template v-if="!isMappedInProject(coding)">
                <input v-if="coding.code" type="checkbox" name="" id="" v-model="codes" :value=coding.code>
              </template>
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
// blacklisted codes
import {codes_blacklist} from '@/variables'


export default {
  name: 'ObservationPage',
  data: () => ({
    show_groups: false,
    internal_codes: null, // internal state for the mapped codes
  }),
  components: {
    FhirForm,
    ResourceContainer,
    ObservationFields,
  },
  computed: {
    mapped_codes() {
      const codes = this.$store.getters['project/mappedCodes']
      return codes
    },
    codes: {
      /**
       * get the codes from the internal state (data) if available
       * otherwise get it from the state
       */
      get(){
        if(this.internal_codes) return this.internal_codes
        const codes = this.$store.getters['project/mappedCodes']
        return codes
      },
      set(codes){
        this.internal_codes = codes
      },
    },
    /**
     * list of the mapped codes in REDCap
     */
    fhir_metadata_codes() {
      const {codes} = this.$store.state.fhir_metadata
      return codes
    },
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
        const codings = entry.codings.filter(coding => {
          return codes_blacklist.indexOf(coding.code)<0
        })
        groups[index] = [...codings]
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
      if(!coding.code) return true
      const codes = this.fhir_metadata_codes
      const mapped = codes.some(code => code===coding.code)
      return mapped
    },
    isMappedInProject(coding) {
      if(!coding.code) return false
      const mapped_codes = this.mapped_codes
      return mapped_codes.indexOf(coding.code) >=0
    },
    addMapping(code) {
      try {
        const {project_id} = this.$store.state.project.info
        this.$API.addMapping({code,project_id})
      } catch (error) {
        console.log(error, 'cannot determine project ID')
      }
    },
    removeMapping(code) {
      try {
        const {project_id} = this.$store.state.project.info
        this.$API.removeMapping({code,project_id})
      } catch (error) {
        console.log(error, 'cannot determine project ID')
      }
    },
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.project_mapped,
.redcap_mapped {
  background: rgba(100,0,0,.3);
}
.project_mapped.is_mapped,
.redcap_mapped.is_mapped {
  background: rgba(0,100,0,.3);
}
td.centered {
  vertical-align: middle;
  text-align: center;
}
</style>