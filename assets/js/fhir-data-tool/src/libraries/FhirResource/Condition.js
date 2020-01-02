import Resource from './Resource'
import Coding from './Coding'
import {intersection} from 'lodash'

class Condition extends  Resource {

    static status_list = ['active', 'completed', 'on-hold', 'stopped']

    constructor(source={}) {
        super(source)
    }

    toString() {
        return this.medication_reference
    }
}



export default Condition