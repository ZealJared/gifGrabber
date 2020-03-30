import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import Api from './api'

Vue.config.productionTip = false
Vue.prototype.$api = new Api(router, store)

new Vue({
  router,
  store,
  render: function (h) { return h(App) }
}).$mount('#app')
