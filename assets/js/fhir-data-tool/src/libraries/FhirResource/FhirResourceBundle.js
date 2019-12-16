import FhirResource from './FhirResource'
import FhirResourceObservation from './FhirResourceObservation'

class FhirResourceBundle extends  FhirResource {

    constructor(source={}) {
        super(source)
    }

    get entries() {
        const entries = this.search(this.source, 'entry')
        const results = []
        entries.forEach(entry => {
            const {resource, resource:{resourceType}} = entry
            switch (resourceType) {
                case 'Observation':
                    const observation = new FhirResourceObservation(resource)
                    results.push(observation)
                    break;
            
                default:
                    break;
            }
            
        });
        return results
    }

    get codings() {
        const results = this.search({foo: {bar: {baz: [0, 1, 2, 3, 4]}}}, "foo.bar")
        return results
    }
}

export default FhirResourceBundle