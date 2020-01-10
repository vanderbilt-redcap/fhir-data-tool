<template>
  <div class="loader">
    <h3>Loading...</h3>
    <ProgressBar :total="total" :progress="progress" />
  </div>
</template>

<script>
import Vue from 'vue'
import qs from 'qs'
import ProgressBar from '@/components/ProgressBar'


export default {
  /**
   * this companent only purspose is to preload
   * mandatory data
   */
  name: 'DataLoader',
  components: {ProgressBar},
  data: () => ({
    total: 0,
    progress: 0,
  }),
  props: {
    onDone: {
      type: Function,
      default: () => {}
    }
  },
  created() {
    const promises = [
      this.loadFhirMetadata(),
      this.loadProjectInfo(),
    ]
    this.total = promises.length
    this.progress = 0

    promises.forEach( async (promise, index) => {
      try {
        const resolved = await promise
      }catch(error) {
        console.error(error)
      } finally {
        this.progress = this.progress+1
        if(this.progress >= this.total) {
          if(typeof(this.onDone)==='function') this.onDone()
        }
      }
    })

  },
  computed: {
    showProgress() {
      return this.total > 0 && this.progress<this.total
    }
  },
  methods: {
    loadFhirMetadata() {
      return this.$store.dispatch('fhir_metadata/fetchMetadata')
    },
    loadProjectInfo() {
      const {pid} = qs.parse(location.search, { ignoreQueryPrefix: true })
      const project_id = pid || 1
      return this.$store.dispatch('project/fetchInfo', project_id)
    },
  }
}
</script>

<style scoped>
.loader {
  width: 400px;
}
</style>
