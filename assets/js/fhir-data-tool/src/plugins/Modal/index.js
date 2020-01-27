// import Modal from './Modal'

import ModalContainer from './ModalContainer'
import Modal from './Modal'

export default {
    install(Vue, options) {
        console.log(Vue)
        let container = document.querySelector('[data-bmodal]')
        if(!container) {
            container = document.createElement('div')
            container.setAttribute('data-bmodal', true)
            document.body.appendChild(container)
            console.log(container)
            console.log(container)
        }

        let Constructor = Vue.extend(ModalContainer)
        console.log(constructor)
          
        const modalComponent = new Constructor({
            propsData: {
                message: 'this is a test'
            }
        }).$mount(container)

        const modalManager = new Modal(modalComponent)

        console.log(modalComponent, ModalContainer)
        // create a modal instance
        // const modal = new Modal()
        // set a global $API reference with params
        Vue.$RcModal = modalManager
        // Add Vue instance methods by attaching them to Vue.prototype.
        Vue.prototype.$RcModal = modalManager
    },
}