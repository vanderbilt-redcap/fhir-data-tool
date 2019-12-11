<template>
  <div class="test">
    <section class="main">
      <details>
        <summary>Test MRN list</summary>
        <ul>
          <li v-for="(mrn, index) in mrns" :key="index">{{mrn}}</li>
        </ul>
      </details>
      <form @submit.prevent="onSubmit">
        <button type="submit">start test</button>
      </form>
    </section>
    <section class="aside">
      <div v-if="processing" class="progress-bar-container">
        <span>{{processed}}/{{total}}</span>
        <progress id="file" :max="total" :value="processed"> {{processed}}/{{total}} </progress>
      </div>
    </section>
  </div>
</template>

<script>
import Vue from 'vue'
import {dev_MRNs} from '@/variables'
import ResourceTable from '@/components/resource/ResourceTable'

export default {
  name: 'Test',
  components: {ResourceTable},
  data: () => ({
    list: [],
    processing: false,
    processed: 0,
    total: dev_MRNs.length,
  }),
  computed: {
    mrns() {
      return dev_MRNs
    },
  },
  methods: {
    async onSubmit() {
      const endpoint = this.$store.state.endpoint.current
      const all_params = this.$store.state.endpoint.params // global params object. contains params for every endpoint
      const params = all_params[endpoint] || [] // get extra params for the current endpoint
      let list = []
      console.log('start')
      const promises = []
      const resources = []
      const entries = []
      this.processed = 0 //reset counter
      this.processing = true
      for (const index in this.mrns) {
        const mrn = this.mrns[index]
        try {
          const promise = this.$store.dispatch('resource/fetchResource', {endpoint, mrn, params})
          promises.push(promise)
          const resource = await promise
          resources.push(resource)
          if(resource.entries) entries.push(resource.entries)
          console.log('into')
        } catch (error) {
          console.log(error)
        }finally {
          this.processed += 1
        }
      }
      try {
        const resolved = await Promise.all(promises)
        this.processing = false
        console.log(entries)
        console.log('end')
        this.list = entries
        this.$store.dispatch('resource/setResources',{resources})
      } catch (error) {
        console.log(error)
      }
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.test {
  border: solid 1px #cacaca;
  padding: 10px;
  margin-bottom: 10px;
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
}
section.main {
  display: flex;
  flex-direction: row;
}
section.main > * {
  margin: 10px;
}
section.aside {
  display: flex;
  margin-left: 30px;
}
.progress-bar-container  {
  margin: auto;
  vertical-align: middle;
}
.progress-bar-container progress {
  margin: auto;
  vertical-align: middle;
}
</style>
