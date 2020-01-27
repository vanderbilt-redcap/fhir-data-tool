
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

class Modal {

    constructor(component) {
        this.state = initialState
        console.log('this is a modal', component)
        component.modal = this.state
    }

    fire(config) {
        this.setProperties(config)
        this.show()
    }

    setProperties(properties) {
        const state_keys = Object.keys(initialState)
        for (let [key, value] of Object.entries(properties)) {
            if(state_keys.indexOf(key)>=0) {
                this.state[key] = value
            }
        }
    }

    reset() {
        this.state = initialState
    }

    show() {
        this.setProperties({show: true})
    }
    
    hide() {
        this.setProperties({show: false})
    }
}

export default Modal