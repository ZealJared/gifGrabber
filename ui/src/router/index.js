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
    path: '/admin',
    name: 'adminLogin',
    component: function () {
      return import('../views/adminLogin.vue')
    }
  },
  {
    path: '/category/:categoryId',
    name: 'categoryGifList',
    component: function () {
      return import('../views/categoryGifList.vue')
    }
  },
  {
    path: '/gif/:gifId',
    name: 'gifView',
    component: function () {
      return import('../views/gifView.vue')
    }
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
