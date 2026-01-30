import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import {useAuthStore} from "@/stores/auth.js";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: Login,
      meta: { requiresAuth: false, layout: 'empty' },
    },
    {
      path: '/',
      name: 'home',
      redirect: '/products',
      meta: { requiresAuth: true },
    },
    {
      path: '/products',
      name: 'products',
      meta: { requiresAuth: true },
      component: () => import('../views/ProductPage.vue'),
    },
    {
      path: '/products/:id/edit',
      name: 'products.edit',
      meta: { requiresAuth: true },
      component: () => import('../components/products/EditProduct.vue'),
    },
    {
      path: '/products/create',
      name: 'products.create',
      meta: { requiresAuth: true },
      component: () => import('../components/products/ProductCreate.vue'),
    },
    {
      path: '/products/:id',
      name: 'products.show',
      component: () => import('../components/products/ProductShow.vue')
    },
  ],
})


router.beforeEach((to) => {
  const store = useAuthStore()

  if (to.meta.requiresAuth && !store.logged) return '/login'
})

export default router
