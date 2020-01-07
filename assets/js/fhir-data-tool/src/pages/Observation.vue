<template>
  <div class="home-page">
    <FhirForm class="fhir-form">
      <ObservationFields />
    </FhirForm>

    <div v-if="filtered_codings.length">

      <section>
        <p>The column <strong>"code"</strong> will show a button if a code is not available in REDCap; clicking the button the admin will be notified that the code must be added.</p>
        <p>You can select codes that are not mapped in your project and export them using the checkboxes in the last column</p>
      </section>

      <table class="table table-striped table-bordered">
        <thead>
          <!-- <th><button type="button" class="btn" @click="toggleGroups">Group</button></th> -->
          <th>Description (from EHR, not from REDCap mapping)</th>
          <th>
              <button class="btn btn-sm"
                @click="hide_blacklisted=!hide_blacklisted"
                :title="(hide_blacklisted ? 'show' : 'hide') + ' hidden codes'">
                <span class="mr-2">Code</span>
                <i v-if="hide_blacklisted" class="fa fa-eye-slash"></i>
                <i v-else class="fas fa-eye"></i>
              </button>
          </th>
          <th>System</th>
          <th>Value</th>
          <th>Date/time of service</th>
          <th><button :disabled="test_codes.length<1" class="btn btn-primary">Export selected codes</button></th>
        </thead>
        <!-- codings are grouped by observation -->
        <tbody >
          <tr v-for="(coding, coding_index) in filtered_codings" :key="coding_index">
            <!-- <td :style="getGroupStyle(index)">{{index}}</td> -->
            <td>{{coding.display}}</td>
            <td>
              <span>{{coding.code}}</span>
              <div v-if="!isMappedInREDCap(coding.code)">
                <span :style="{color:'red'}"><small>not available in REDCap</small></span>
                <button class="btn btn-sm btn-info" @click="sendNotification(coding.code)">
                  Notify admin <i class="fas fa-bell"></i>
                </button>
              </div>
            </td>
            <td>{{coding.system}}</td>
            <td>{{coding.value}}</td>
            <td>{{coding.date}}</td>
            <td class="centered project_mapped" :class="{is_mapped: isMappedInProject(coding.code)}">
              <template v-if="coding.code && !isMappedInProject(coding.code)">
                <div>
                  <small><em>not mapped in your project</em></small>
                </div>
                <label :for="`code_checkbox_${coding_index}`" class="mr-2">Export</label>
                <input type="checkbox" name="" :id="`code_checkbox_${coding_index}`" v-model="test_codes" :value=coding.code>
              </template>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <ResourceContainer class="my-2"/>
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
    hide_blacklisted: true,
    show_groups: false,
    test_codes: [],
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
    codings() {
      const entries = this.entries
      const codings = this.entries.reduce((all, entry) => {
        return [...all, ...entry.codings]
      }, [])
      return codings
    },
    filtered_codings() {
      const codings = this.codings
      if(!this.hide_blacklisted) return codings
      return codings.filter(coding => codes_blacklist.indexOf(coding.code)<0 )
    },
    grouped_codings() {
      const entries = this.entries
      const groups = {}
      entries.forEach((entry, index) => {
        const codings = entry.codings.filter(coding => {
          return codes_blacklist.indexOf(coding.code)<0
        })
        if(codings.length>0) {
          groups[index] = [...codings]
        }
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
    isMappedInREDCap(code) {
      if(!code) return true
      const mapped_codes = this.fhir_metadata_codes
      const mapped = mapped_codes.some(mapped_code => mapped_code===code)
      return mapped
    },
    isMappedInProject(code) {
      if(!code) return false
      const mapped_codes = this.mapped_codes
      return mapped_codes.indexOf(code) >=0
    },
    sendNotification(code) {
      this.$API.sendNotification({code})
    }

  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.project_mapped,
.redcap_mapped {
  /* background: rgba(100,0,0,.3); */
}
.project_mapped.is_mapped,
.redcap_mapped.is_mapped {
  /* background: rgba(0,100,0,.3); */
}
td.centered {
  vertical-align: middle;
  text-align: center;
}
</style>