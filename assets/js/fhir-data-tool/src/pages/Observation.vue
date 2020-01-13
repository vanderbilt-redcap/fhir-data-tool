<template>
  <div class="home-page">
    <FhirForm class="fhir-form">
      <ObservationFields />
    </FhirForm>

    <div v-if="filtered_codings.length">

      <section>
        <p>An "add code" request can be sent to the admin for review when a code is not available in REDCap.</p>
        <p>Codes that are not mapped in your project can be <b>exported</b> and used as a reference in the mapping process.</p>
      </section>

      <table class="table table-striped table-bordered">
        <thead>
          <!-- <th><button type="button" class="btn" @click="toggleGroups">Group</button></th> -->
          <th>Description (from EHR, not from REDCap mapping)</th>
          <th>
            <div v-show="exportable.length>0">
              <div class="btn-group" role="group">
                <button
                  type="button" 
                  class="btn btn-sm btn-info"
                  @click="toggleExportableSelection"
                ><span>{{(exportable.length===codes_to_export.length) ? `deselect all` : `select all`}} <i class="fas fa-check-square"></i></span></button>
                <button
                  type="button"
                  :disabled="codes_to_export.length<1"
                  class="btn btn-sm btn-primary"
                  @click="showPreview">
                  <span>Export <i class="fas fa-download"></i></span>
                </button>
              </div>
            </div>

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
        </thead>
        <!-- codings are grouped by observation -->
        <tbody >
          <tr v-for="(coding, coding_index) in filtered_codings" :key="coding_index">
            <!-- <td :style="getGroupStyle(index)">{{index}}</td> -->
            <td>{{coding.display}}</td>
            <td>
              <div><span>{{coding.code}}</span></div>
              <section v-if="isBlacklisted(coding.code)">
                <div>
                  <small><em>(this code is not used in REDCap: {{isBlacklisted(coding.code)}})</em></small>
                </div>
              </section>
              <section v-if="!isAvailableInREDCap(coding.code)">
                <div>
                  <small :style="{color:'red'}">(not available in REDCap)</small>
                </div>
                <button class="btn btn-sm btn-info" @click="sendNotification(coding.code)">
                  request <i class="fas fa-bell"></i>
                </button>
              </section>
              <section v-if="isExportable(coding.code)">
                <div>
                  <small><em>(not mapped in your project)</em></small>
                </div>
                <label :for="`code_checkbox_${coding_index}`" class="mr-2">Select</label>
                <input type="checkbox" name="" :id="`code_checkbox_${coding_index}`" v-model="codes_to_export" :value="coding.code">
              </section>
            </td>
            <td>{{coding.system}}</td>
            <td>{{coding.value}}</td>
            <td>{{coding.date}}</td>
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

import {download} from '@/libraries'


export default {
  name: 'ObservationPage',
  data: () => ({
    hide_blacklisted: true,
    show_groups: false,
    codes_to_export: [],
    internal_codes: null, // internal state for the mapped codes
  }),
  components: {
    FhirForm,
    ResourceContainer,
    ObservationFields,
  },
  computed: {
    /**
     * get a list of the codings based on the selected codes
     */
    values_to_export() {
      const codings = []
      // keep track of the codes pushed in codings to avoid duplicates
      const track_codes = []
      this.filtered_codings.forEach(coding => {
        if(this.codes_to_export.indexOf(coding.code)>=0) {
          // check for codes duplicates
          if(track_codes.indexOf(coding.code)<0) {
            track_codes.push(coding.code)
            codings.push(coding)
          }
        }
      })
      return codings
    },
    mapped_codes() {
      const codes = this.$store.getters['project/mappedCodes']
      return codes
    },
    /**
     * list of the mapped codes in REDCap
     */
    fhir_metadata_codes() {
      const {codes} = this.$store.state.fhir_metadata
      return codes
    },
    /**
     * get the entries
     */
    entries() {
      try {
        const {observations:entries=[]} = this.$store.state.resource
        return entries
      } catch (error) {
        console.log(error)
        return []  
      }
    },
    /**
     * extract codings from entries
     */
    codings() {
      const entries = this.entries
      const codings = this.entries.reduce((all, entry) => {
        return [...all, ...entry.codings]
      }, [])
      return codings
    },
    /**
     * apply filters to codings (blacklist...)
     */
    filtered_codings() {
      const codings = this.codings
      if(this.hide_blacklisted) {
        return codings.filter(coding => !this.isBlacklisted(coding.code) )
      }
      return codings
    },
    /**
     * get a list of codes that could be exported
     */
    exportable() {
      const codings = this.filtered_codings
      const exportable = []
      codings.forEach(coding => {
        const {code=''} = coding
        if(!this.isExportable(code)) return
        if(exportable.indexOf(code) >=0) return
        exportable.push(code)
      })
      return exportable
    }
  },
  methods: {
    toggleExportableSelection() {
      if(this.exportable.length===this.codes_to_export.length) {
        this.codes_to_export = []
      }else {
        this.codes_to_export = [...this.exportable]
      }
    },
    isAvailableInREDCap(code='') {
      if(code.trim()=='') return true
      if(this.isBlacklisted(code)) return true
      const mapped_codes = this.fhir_metadata_codes
      const mapped = mapped_codes.some(mapped_code => mapped_code===code)
      return mapped
    },
    /**
     * check if a code is blacklisted
     */
    isBlacklisted(code='') {
      const codes = codes_blacklist.map(element => element.code)

      const index = codes.indexOf(code)
      if(index>=0) return codes_blacklist[index].reason
      else return false
    },
    isMappedInProject(code='') {
      if(code.trim()=='') return true
      if(this.isBlacklisted(code)) return true
      const mapped_codes = this.mapped_codes
      return mapped_codes.indexOf(code) >=0
    },
    isExportable(code) {
      const mapped_in_redcap = this.isAvailableInREDCap(code)
      const mapped_in_project =this.isMappedInProject(code)
      return mapped_in_redcap && !mapped_in_project
    },
    sendNotification(code) {
      this.$API.sendNotification({code})
    },
    getLinesToExport() {
      const lines = []
      this.values_to_export.forEach(coding => {
        const line = `${coding.code}\t${coding.display}`
        lines.push(line)
      })
      return lines
    },
    showPreview() {
      const component = () => import('@/components/DownloadPreview')
      const lines = this.getLinesToExport()
      this.$store.dispatch('modal/fire',{
        component,
        component_properties: {
          lines
        },
        header: 'Export fields',
        confirm_text: 'Download',
        onConfirm: () => this.exportData(lines)
      })
    },
    /**
     * export data to file:
     * join array of lines using newline
     */
    exportData(lines) {
      const text = lines.join(`\n`)
      download('fields', text)
    },

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
button {
  white-space: nowrap;
}
td.centered {
  vertical-align: middle;
  text-align: center;
}
</style>