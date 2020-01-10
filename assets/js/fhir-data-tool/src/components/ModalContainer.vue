<template>
  <div>
    <Modal
      :show="show"
      :prevent_closing="prevent_closing"
      @hidden="onHidden"
      @cancel-clicked="onCancelClicked"
      @ok-clicked="onOkClicked"
      >
      <template v-slot:header>
        <h3 v-if="title!=null">{{title}}</h3>
      </template>
      <template v-slot:body>
          <template  v-if="body_component">
            <component :is="body_component" v-bind="body_properties" />
          </template>
          <span>{{body}}</span>
      </template>
      <template v-slot:footer>
        <div v-if="footer!=null">{{footer}}</div>
      </template>
    </Modal>
    <async-webpack-example />
    <test :level="3">asdfdsafdsaf</test>
    <button @click="showModal">shoModal</button>
    <button @click="setComponent">component</button>
  </div>
</template>

<script>
import Vue from 'vue'
import { mapState } from 'vuex'
import Modal from '@/components/Modal'

Vue.component(
  'async-webpack-example',
  // The `import` function returns a Promise.
  () => import('@/components/BaseComponent')
)
Vue.component('test', {

    // The `import` function returns a Promise.
  render: (createElement) => {
    return createElement(
      'h' + this.level,   // tag name
      this.$slots.default // array of children
    )
  },
  props: {
    level: {
      type: Number,
      required: true
    }
  }
})

export default {
  name: 'ModalContainer',
  components: {
    Modal,
  },
  computed: {
    ...mapState({
      prevent_closing: state => state.modal.prevent_closing,
      show: state => state.modal.show,
      title: state => state.modal.title,
      body_component: state => state.modal.body_component,
      body_properties: state => state.modal.body_properties,
      body: state => state.modal.body,
      footer: state => state.modal.footer,
      onCancel: state => state.modal.onCancel,
      onOk: state => state.modal.onOk,
    }),
  },
  methods: {
    showModal() {
      this.$store.dispatch('modal/show')
    },
    setComponent() {
      const body_component = () => import('@/components/BaseComponent')
      body_component.propsData = {msg:'ciaociao'}
      this.$store.dispatch('modal/fire', {
        body_component,
        // body_properties: {msg:'ciaociao'},
        body: 1234,
        footer: '',
        onOk:()=>console.log(123),
      })
    },
    onHidden() {
      // reset the modal after it is hidden
      this.$store.dispatch('modal/reset')
    },
    onCancelClicked() {
      if(typeof this.onCancel == 'function') this.onCancel()
    },
    onOkClicked() {
      if(typeof this.onOk == 'function') this.onOk()
    },
  }
}
</script>

<style scoped>
</style>
