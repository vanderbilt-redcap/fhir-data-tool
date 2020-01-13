export const observation_categories = [
    'laboratory',
    'vital-signs',
    'social-history',
]

export const endpoints = [
    'Patient',
    'Condition',
    'Observation',
    'MedicationOrder',
    'AllergyIntolerance',
    'DocumentReference',
]

export const menu_links = [
    { label:'Home', to: {name: 'home'} },
    { label:'Demographics', to: {name: 'patient'} },
    { label:'Medications', to: {name: 'medication-order'} },
    { label:'Labs and Vitals', to: {name: 'observation'} },
    { label:'Problem list', to: {name: 'condition'} },
    { label:'String Identifier', to: {name: 'string-identifier'} },
    // { label:'Help', to: {name: 'help'} },
    // { label:'FHIR Metadata', to: {name: 'fhir_metadata'} },
    // { label:'Project Info', to: {name: 'project_info'} },
]

export const date_format = 'YYYY-MM-DD hh:mm a'

/**
 * codes to ignore from results
 */
export const codes_blacklist = [
    {
        code: '8716-3', // Vital signs
        reason: 'too generic'
    }
]