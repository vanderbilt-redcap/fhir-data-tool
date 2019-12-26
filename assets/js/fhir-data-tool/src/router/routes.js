/**
 * components are lazy loaded as the routes are visited
 */
const routes = [
    // { path: '/', redirect: {name: 'newsletter'} },
    { path: '/', component: () => import('@/components/layouts/MainLayout'),
        children: [
            { path: '', name: 'home', component: () => import('@/components/pages/Home') },
            { path: '/patient', name: 'patient', component: () => import('@/components/pages/Patient') },
            { path: '/medication-order', name: 'medication-order', component: () => import('@/components/pages/MedicationOrder') },
            { path: '/observation', name: 'observation', component: () => import('@/components/pages/Observation') },

            { path: '/help', name: 'help', component: () => import('@/components/pages/Help') },
            { path: '/video', name: 'video', component: () => import('@/components/pages/Video') },

            { path: "*", name: 'not_found', component: () => import('@/components/pages/PageNotFound')  }
        ]
    },
]

export default routes