import jmespath from 'jmespath'

class FhirResource {
    constructor(source) {
        this.source = source
    }

    get codings() {
        const results = jmespath.search({foo: {bar: {baz: [0, 1, 2, 3, 4]}}}, "foo.bar")
        return results
    }
}

export default FhirResource