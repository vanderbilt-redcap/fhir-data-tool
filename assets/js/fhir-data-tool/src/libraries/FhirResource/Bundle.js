import Resource from './Resource'
import Observation from './Observation'
import MedicationOrder from './MedicationOrder'

class Bundle extends  Resource {

    constructor(source={}) {
        super(source)
    }

    get entries() {
        const {entry:entries=[]} = this.source
        const results = []
        if(!entries) return results
        
        entries.forEach(entry => {
            const {resource, resource:{resourceType}} = entry
            switch (resourceType) {
                case 'Observation':
                    const observation = new Observation(resource)
                    results.push(observation)
                    break;
                case 'MedicationOrder':
                    const medication_order = new MedicationOrder(resource)
                    results.push(medication_order)
                    break;
                default:
                    break;
            }
            
        })
        return results
    }

    /**
     * return a list of entries filtered by class
     * 
     * @param {class} type_class 
     */
    getEntriesOfType(type_class) {
        const entries = this.entries.filter( entry => {
            return entry instanceof type_class
        })
        return entries
    }

}

export default Bundle