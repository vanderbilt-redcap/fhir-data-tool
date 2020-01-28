
const initialState = {
    show: false,
    prevent_closing: false,
    header: null,
    body: null,
    component: null,
    component_properties: {},
    footer: null,
    onConfirm: null,
    onCancel: null,
    cancel_text: 'Cancel',
    confirm_text: 'Ok',
}

class ModalManager {

    constructor(component) {
        this.state = {...initialState}

        component.modal = this.state

        /**
         * listen for reset event
         */
        component.$on('reset', () => {
            this.reset()
        })
        this.component = component
    }

    fire(config) {
        this.setProperties(config)
        this.show()
    }

    setProperties(properties) {
        const state_keys = Object.keys(initialState)
        for (let [key, value] of Object.entries(properties)) {
            if(state_keys.indexOf(key)>=0) {
                this.component.modal[key] =  value
            }
        }
    }

    reset() {
        this.setProperties(initialState)
    }

    show() {
        this.setProperties({show: true})
    }
    
    hide() {
        this.setProperties({show: false})
    }
}

export default ModalManager