import FhirResource from './FhirResource'

class FhirResourceObservation extends  FhirResource {

    constructor(source={}) {
        super(source)
    }

    get codings() {
        const results1 = this.search({foo: {bar: {baz: [0, 1, 2, 3, 4]}}}, "foo.bar")
        const results = this.search(this.source, "code.coding[].system")
        console.log(results)
        return results
    }
}

export default FhirResourceObservation