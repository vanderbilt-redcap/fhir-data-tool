// import Modal from './Modal'

import ModalContainer from './ModalContainer'
import ModalManager from './ModalManager'

export default {
    /**
     * 
     * @param {object} Vue 
     * @param {object} options options will be used to pass arguments to the modal container. i.e. store, router
     */
    install(Vue, options) {
        let container = document.querySelector('[data-bmodal]')
        if(!container) {
            container = document.createElement('div')
            container.setAttribute('data-bmodal', true)
            document.body.appendChild(container)
        }

        const Constructor = Vue.extend(ModalContainer)
        const modalComponent = new Constructor(options).$mount(container) // pass the options to the modal container

        const modalManager = new ModalManager(modalComponent)
        // create a modal instance
        // set a global $API reference with params
        Vue.$RcModal = modalManager
        // Add Vue instance methods by attaching them to Vue.prototype.
        Vue.prototype.$RcModal = modalManager
    },
}