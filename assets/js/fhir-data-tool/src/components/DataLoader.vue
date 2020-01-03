<template>
  <transition name="modal-fade">
    <div class="modal-backdrop" v-show="showProgress">
      <div class="vue-modal">
        <div class="vue-modal-body">
          <h3>Loading...</h3>
          <ProgressBar :total="total" :progress="progress" />
        </div>
      </div>
    </div>
  </transition>
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
  created() {
    const promises = []
    promises.push(this.loadFhirMetadata())
    promises.push(this.loadProjectInfo())
    this.total = promises.length
    this.progress = 0

    promises.forEach( async (promise, index) => {
      try {
        const resolved = await promise
      }catch(error) {
        console.error(error)
      } finally {
        this.progress = this.progress+1
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
/* transitions */
.modal-fade-enter,
.modal-fade-leave-active {
  opacity: 0;
}
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity .5s ease
}
/* component style */
.modal-backdrop {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .vue-modal {
    background: #FFFFFF;
    box-shadow: 2px 2px 20px 1px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 20px;
    width: 50%;
    max-width: 300px;
    height: auto;
    position: relative;
  }
  .vue-modal > * {
    flex: 1;
    width: 100%;
    height: 100%;
  }
</style>
