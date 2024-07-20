import { h, resolveComponent } from 'vue'
import { createRouter, createWebHashHistory } from 'vue-router'

import DefaultLayout from '@/layouts/DefaultLayout'

const routes = [
  {
      path: '/',
      name: 'Login',
      component: () => import('@/views/pages/Login.vue'),
  },
  {
    path: '/admin',
    name: 'Home',
    component: DefaultLayout,
    redirect: '/dashboard',
    children: [
      {
        path: '/dashboard',
        name: 'Dashboard',
        // route level code-splitting
        // this generates a separate chunk (about.[hash].js) for this route
        // which is lazy-loaded when the route is visited.
        component: () =>
          import(
            /* webpackChunkName: "dashboard" */ '@/views/dashboard/Dashboard.vue'
          ),
      },
      {
        path: '/users',
        name: 'Users',
        component: () => import(
          /* webpackChunkName: "dashboard" */ '@/views/users/List.vue'
        ),
      },
      {
        path: '/users/create',
        name: 'create-users',
        component: () => import(
          /* webpackChunkName: "dashboard" */ '@/views/users/Create.vue'
        ),
      },
      {
        path: '/customers',
        name: 'Customers',
        component: () => import(
          /* webpackChunkName: "dashboard" */ '@/views/customers/List.vue'
        ),
      },
      {
        path: '/customers/create',
        name: 'create-customer',
        component: () => import(
          /* webpackChunkName: "dashboard" */ '@/views/customers/Create.vue'
        ),
      },
      {
        path: '/purchase',
        name: 'Purchase',
        component: () => import(
          /* webpackChunkName: "dashboard" */ '@/views/purchase/List.vue'
        ),
      },
      {
        path: '/purchase/create',
        name: 'create-purchase',
        component: () => import(
          /* webpackChunkName: "dashboard" */ '@/views/purchase/CustomerPurchase.vue'
        ),
      },

    ],
  },

]

const router = createRouter({
  history: createWebHashHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior() {
    // always scroll to top
    return { top: 0 }
  },
})

// Global Navigation Guard for Authentication Checks
router.beforeEach((to, from) => {

    // Check if user is authenticated (has user data) 
  const userData = localStorage.getItem('user');
  if (!userData && to.path !== '/' ) {
    return {
      name: 'Login'
    }
  }
})

export default router
