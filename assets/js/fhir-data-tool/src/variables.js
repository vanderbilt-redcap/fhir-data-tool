export const observation_categories = [
    'laboratory',
    'vital-signs',
    'social-history',
]

export const medication_status_list = [
    'active',
    'completed',
    'on-hold',
    'stopped',
]

export const resource_types = [
    'Patient',
    'Condition',
    'Observation',
    'MedicationOrder',
    'AllergyIntolerance',
    'DocumentReference',
]

export const interactions = [
    'read',
    'update',
    'delete',
    'create',
    'search',
    'history',
    'transaction',
    'operation',
]

export const menu_links = [
    { label:'Home', to: {name: 'home'}, exact:true },
    { label:'Demographics', to: {name: 'patient', params: {method: 'read'}},},
    { label:'Medications', to: {name: 'medication-order', params: {method: 'search'}},},
    { label:'Labs and Vitals', to: {name: 'observation', params: {method: 'search'}},},
    { label:'Problem list', to: {name: 'condition', params: {method: 'search'}},},
    { label:'FHIR Tool', to: {name: 'fhir-tool'} },
    { label:'String Identifier', to: {name: 'string-identifier', params: {method: 'search'}},},
    // { label:'Help', to: {name: 'help'} },
    // { label:'FHIR Metadata', to: {name: 'fhir_metadata'} },
    // { label:'Project Info', to: {name: 'project_info'} },
]

export const date_format = 'YYYY-MM-DD HH:mm'

/**
 * codes to ignore from results
 */
export const codes_blacklist = [
    {
        code: '8716-3', // Vital signs
        reason: 'too generic'
    }
]