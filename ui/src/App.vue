<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">GifGrabber</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <router-link tag="li" class="nav-item" active-class="active" :to="{ name: 'home' }" exact>
            <a class="nav-link" href="#">Home</a>
          </router-link>
          <router-link tag="li" class="nav-item" active-class="active" :to="{ name: 'gifCreate' }" exact>
            <a class="nav-link" href="#">Add GIF</a>
          </router-link>
          <router-link tag="li" class="nav-item" active-class="active" :to="{ name: 'adminLogin' }" exact>
            <a class="nav-link" href="#">Admin Login</a>
          </router-link>
        </ul>
      </div>
    </nav>
    <div class="container mt-5 mb-5">
      <div class="alert alert-danger" v-for="(error, index) in errors" :key="index">{{ error }}</div>
      <router-view :key="$route.fullPath"/>
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    errors () {
      return this.$store.getters.errors
    }
  },
  watch: {
    $route (to, from) {
      this.$store.commit('setErrors', [])
    }
  }
}
</script>
