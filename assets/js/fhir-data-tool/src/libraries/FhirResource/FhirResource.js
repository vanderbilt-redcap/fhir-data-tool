import {search} from 'jmespath'

class FhirResource {
    constructor(source={}) {
        this.source = source
    }

    get codings() {
        const results = search({foo: {bar: {baz: [0, 1, 2, 3, 4]}}}, "foo.bar")
        return results
    }

    /**
     * expose the search method from jmesPath
     * @param  {...any} args 
     */
    search(...args) {
        return search(...args)
    }
}

export default FhirResource