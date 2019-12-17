import Coding from './Coding'
import {search} from 'jmespath'

class Resource {
    constructor(source={}) {
        this.source = source
    }

    /**
     * return a list of Coding
     */
    get codings() {
        const { code: {coding:codings=[]}} = this.source
        const list = codings.map(coding => new Coding(coding))
        return list
    }

    /**
     * expose the search method from jmesPath
     * @param  {...any} args 
     */
    search(...args) {
        return search(...args)
    }
}

export default Resource