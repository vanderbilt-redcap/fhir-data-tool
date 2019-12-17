import Resource from './Resource'
import Observation from './Observation'

class Bundle extends  Resource {

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
                    const observation = new Observation(resource)
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

export default Bundle