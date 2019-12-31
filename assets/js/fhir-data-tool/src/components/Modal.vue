<template>
  <transition name="modal-fade">
    <div class="modal-backdrop" v-show="show">
      <div class="vue-modal">
        <header class="vue-modal-header">
          <slot name="header">
            <span>{{title}}</span>
            <button v-if="closeable" type="button" class="btn-close" @click="close" >x</button>
          </slot>
        </header>
        <section class="vue-modal-body">
          <slot name="body">
            <component v-if="body_component" :is="body_component" v-bind="body_properties" />
            <span v-else>{{body}}</span>
          </slot>
        </section>
        <footer class="vue-modal-footer">
            <slot name="footer">
              <span>{{footer}}</span>
              <button v-if="closeable" type="button" class="btn-green" @click="close">Close</button>
          </slot>
        </footer>
      </div>
    </div>
  </transition>
</template>

<script>
import Vue from 'vue'
import DataLoader from '@/components/DataLoader'

export default {
  name: 'Modal',
  data: () => ({
    title_component: null,
    footer_component: null,
  }),
  computed: {
    closeable() {
      const closeable = this.$store.state.modal.closeable
      return closeable
    },
    show() {
      const show = this.$store.state.modal.show
      return show
    },
    title() {
      const title = this.$store.state.modal.header
      return title
    },
    body_component() {
      const component = this.$store.state.modal.body_component
      return component
    },
    body_properties() {
      const properties = this.$store.state.modal.body_properties || {}
      return properties
    },
    body() {
      const body = this.$store.state.modal.body
      return body
    },
    footer() {
      const footer = this.$store.state.modal.footer
      return footer
    },
  },
  methods: {
    close() {
      // this.$emit('close')
      this.$store.dispatch('modal/hide')

    },
  },
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
    overflow-x: auto;
    display: flex;
    flex-direction: column;
  }

  .vue-modal-header,
  .vue-modal-footer {
    padding: 15px;
    display: flex;
  }

  .vue-modal-header {
    border-bottom: 1px solid #eeeeee;
    color: #4AAE9B;
    justify-content: space-between;
    vertical-align: center;
    align-items: center;
  }

  .vue-modal-footer {
    border-top: 1px solid #eeeeee;
    justify-content: flex-end;
  }

  .vue-modal-body {
    position: relative;
    padding: 20px 10px;
  }

  .btn-close {
    align-self: flex-end;
    border: none;
    font-size: 20px;
    margin-left: 20px;
    cursor: pointer;
    font-weight: bold;
    color: #4AAE9B;
    background: transparent;
  }

  .btn-green {
    color: white;
    background: #4AAE9B;
    border: 1px solid #4AAE9B;
    border-radius: 2px;
  }
</style>
