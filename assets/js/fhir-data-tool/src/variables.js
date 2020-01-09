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

/**
 * codes to ignore from results
 */
export const codes_blacklist = [
    {
        code: '8716-3', // Vital signs
        reason: 'too generic'
    }
]