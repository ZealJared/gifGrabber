<template>
  <form @submit.prevent="login">
    <div class="form-group">
      <label for="userName">E-mail address</label>
      <input v-model="userName" class="form-control" type="text" name="userName">
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input v-model="password" class="form-control" type="password" name="password">
    </div>
    <button type="submit" class="btn btn-primary">Log in</button>
  </form>
</template>

<script>
export default {
  data () {
    return {
      userName: '',
      password: ''
    }
  },
  methods: {
    login () {
      this.$api.adminLogin(this.userName, this.password)
      this.$router.replace({ name: 'home' })
    }
  }
}
</script>
