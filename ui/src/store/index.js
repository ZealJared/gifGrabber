import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    errors: []
  },
  getters: {
    errors (state) {
      return state.errors || []
    }
  },
  mutations: {
    setErrors (state, payload) {
      if (Array.isArray(payload)) {
        state.errors = payload
      }
    }
  },
  actions: {
  },
  modules: {
  }
})
