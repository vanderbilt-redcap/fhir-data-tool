/**
 * components are lazy loaded as the routes are visited
 */
const routes = [
    // { path: '/', redirect: {name: 'newsletter'} },
    { path: '/', component: () => import('@/layouts/MainLayout'),
        children: [
            { path: '', name: 'home', component: () => import('@/pages/Home') },
            { path: '/patient', name: 'patient', component: () => import('@/pages/Patient') },
            { path: '/medication-order', name: 'medication-order', component: () => import('@/pages/MedicationOrder') },
            { path: '/observation', name: 'observation', component: () => import('@/pages/Observation') },
            { path: '/condition', name: 'condition', component: () => import('@/pages/Condition') },

            { path: '/help', name: 'help', component: () => import('@/pages/Help') },
            { path: '/fhir_metadata', name: 'fhir_metadata', component: () => import('@/pages/FhirMetadata') },
            { path: '/project_info', name: 'project_info', component: () => import('@/pages/ProjectInfo') },

            { path: "*", name: 'not_found', component: () => import('@/pages/PageNotFound')  }
        ]
    },
]

export default routes