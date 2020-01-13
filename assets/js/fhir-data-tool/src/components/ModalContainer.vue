<template>
  <div>
    <Modal
      :show="modal.show"
      :prevent_closing="modal.prevent_closing"
      :cancel_text="modal.cancel_text"
      :confirm_text="modal.confirm_text"
      @hidden="onHidden"
      @cancel="onCancelClicked"
      @confirm="onConfirmClicked"
      >
      <template v-slot:header>
        <span v-if="modal.header!=null">{{modal.header}}</span>
      </template>
      <template v-slot:body>
          <template  v-if="modal.component">
            <component :is="modal.component" v-bind="modal.component_properties" />
          </template>
          <span>{{modal.body}}</span>
      </template>
      <template v-slot:footer>
        <div v-if="modal.footer!=null">{{modal.footer}}</div>
      </template>
    </Modal>


    <button @click="showModal">shoModal</button>
    <button @click="setComponent">component</button>
  </div>
</template>

<script>
import Vue from 'vue'
import { mapState } from 'vuex'
import Modal from '@/components/Modal'
import BaseComponent from '@/components/BaseComponent'

export default {
  name: 'ModalContainer',
  components: {Modal},
  computed: {
    ...mapState({
      modal: 'modal', // the same as state => state.modal
    }),
  },
  methods: {
    showModal() {
      this.$store.dispatch('modal/show')
    },
    setComponent() {
      const body_component = () => import('@/components/BaseComponent')

      this.$store.dispatch('modal/fire', {
        component: body_component,
        // body_properties: {msg:'ciaociao'},
        body: 1234,
        confirm_text: 'download',
        onConfirm: () => console.log(123)
        // footer: '',
        // onOk:()=>console.log(123),
      })
    },
    onHidden() {
      // reset the modal after it is hidden
      this.$store.dispatch('modal/reset')
    },
    onCancelClicked() {
      if(typeof this.modal.onCancel == 'function') this.modal.onCancel()
    },
    onConfirmClicked() {
      if(typeof this.modal.onConfirm == 'function') this.modal.onConfirm()
    },
  }
}
</script>

<style scoped>
</style>
