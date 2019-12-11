/**
 * components are lazy loaded as the routes are visited
 */
const routes = [
    // { path: '/', redirect: {name: 'newsletter'} },
    { path: '/', component: () => import('@/layouts/MainLayout'),
        children: [
            { path: '', name: 'home', component: () => import('@/pages/Home') },

            { path: '/help', name: 'help', component: () => import('@/pages/Help') },

            { path: "*", name: 'not_found', component: () => import('@/pages/PageNotFound')  }
        ]
    },
]

export default routes