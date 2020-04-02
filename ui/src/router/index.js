import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'home',
    component: function () {
      return import('../views/categoryList.vue')
    }
  },
  {
    path: '/login',
    name: 'adminLogin',
    component: function () {
      return import('../views/adminLogin.vue')
    }
  },
  {
    path: '/category/:categoryId/gifs',
    name: 'categoryGifList',
    component: function () {
      return import('../views/categoryGifList.vue')
    }
  },
  {
    path: '/gif/:gifId/view',
    name: 'gifView',
    component: function () {
      return import('../views/gifView.vue')
    }
  },
  {
    path: '/gif/:gifId/edit',
    name: 'gifEdit',
    component: function () {
      return import('../views/gifEdit.vue')
    }
  },
  {
    path: '/gif/add',
    name: 'gifCreate',
    component: function () {
      return import('../views/gifEdit.vue')
    }
  },
  {
    path: '/gif/:gifId/delete',
    name: 'gifDelete',
    component: function () {
      return import('../views/gifDelete.vue')
    }
  },
  {
    path: '/category/add',
    name: 'categoryCreate',
    component: function () {
      return import('../views/categoryEdit.vue')
    }
  },
  {
    path: '/category/:categoryId/edit',
    name: 'categoryEdit',
    component: function () {
      return import('../views/categoryEdit.vue')
    }
  },
  {
    path: '/category/:categoryId/delete',
    name: 'categoryDelete',
    component: function () {
      return import('../views/categoryDelete.vue')
    }
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
