<template>
  <transition name="modal-fade" @after-leave="afterLeave">
    <div class="modal-backdrop" v-show="show" @click.self="onBackdropClicked">
      <div class="vue-modal">
        <header class="vue-modal-header">
          <slot name="header">
            <span></span>
          </slot>
          <button v-if="!prevent_closing" type="button" class="btn-close" @click.self="onHeaderClose" >×</button>
        </header>
        <section class="vue-modal-body">
          <slot name="body"></slot>
        </section>
        <footer class="vue-modal-footer">
            <slot name="footer">
              <button type="button" class="btn btn-secondary mr-2" @click.self="onCancel">{{cancel_text}}</button>
              <button type="button" class="btn btn-primary" @click.self="onConfirm">{{confirm_text}}</button>
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
  props: {
    show: {
      type: Boolean,
      default: false
    },
    showFooter: {
      type: Boolean,
      default: true
    },
    prevent_closing: {
      type: Boolean,
      default: false
    },
    confirm_text: {
      type: String,
      default: 'Ok'
    },
    cancel_text: {
      type: String,
      default: 'Cancel'
    },
  },
  methods: {
    onBackdropClicked() {
      if(this.prevent_closing) return
      this.$emit('backdrop-clicked')
      this.close()
    },
    onHeaderClose() {
      this.$emit('header-close-clicked')
      this.close()
    },
    onCancel() {
      this.$emit('cancel')
      this.close()
    },
    onConfirm() {
      this.$emit('confirm')
      this.close()
    },
    close() {
      this.$store.dispatch('modal/hide')
      this.$emit('close')
    },
    afterLeave() {
      this.$emit('hidden')
    }
  },
}
</script>

<style scoped>
/* transitions */
/* overall opacity */
.modal-fade-enter,
.modal-fade-leave-active {
  opacity: 0;
}
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity .3s ease-out;
}
/* translate the modal */
.modal-fade-enter .vue-modal,
.modal-fade-leave-active .vue-modal {
  transform: translate(0, -25%);
}
.modal-fade-enter-to .vue-modal,
.modal-fade-leave .vue-modal {
  opacity: 1;
  transform: translate(0, 0);
}
.modal-fade-enter-active .vue-modal,
.modal-fade-leave-active .vue-modal {
  transition: transform .3s ease-out;
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
    align-items: flex-start;
  }

  .vue-modal {
    background: #FFFFFF;
    box-shadow: 2px 2px 20px 1px;
    overflow-x: auto;
    display: flex;
    flex-direction: column;
    margin: 1.75rem auto;
    max-width: 80%;
    min-width: 30%;
  }

  .vue-modal-header,
  .vue-modal-footer {
    padding: 15px;
    display: flex;
  }

  .vue-modal-header {
    border-bottom: 1px solid #eeeeee;
    color: rgb(0,0,0);
    justify-content: space-between;
    vertical-align: center;
    align-items: center;
    font-weight: 700;
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
    font-weight: 700;
    color: rgba(10,10,10,1);
    background: transparent;
  }

  .btn-green {
    color: white;
    background: #4AAE9B;
    border: 1px solid #4AAE9B;
    border-radius: 2px;
  }
</style>
