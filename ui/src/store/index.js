import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    errors: [],
    loggedIn: false
  },
  getters: {
    errors (state) {
      return state.errors || []
    },
    loggedIn (state) {
      return state.loggedIn
    }
  },
  mutations: {
    setErrors (state, payload) {
      if (Array.isArray(payload)) {
        state.errors = payload
      }
    },
    loggedIn (state, payload) {
      state.loggedIn = payload
    }
  },
  actions: {
  },
  modules: {
  }
})
