<template>
  <form @submit.prevent="save()">
    <div class="form-group">
      <label for="name">Name</label>
      <input v-model="category.Name" class="form-control" type="text" name="name" id="name">
    </div>
    <div class="d-flex justify-content-between align-items-center">
      <button type="submit" class="btn btn-primary">Save</button>
      <router-link :to="{ name: 'categoryDelete', params: { categoryId: category.Id } }" v-if="!!category.Id" class="btn btn-danger">Delete</router-link>
    </div>
  </form>
</template>

<script>
export default {
  data () {
    return {
      category: {
        Name: ''
      }
    }
  },
  async mounted () {
    if (this.$route.params.categoryId) {
      if (!this.$store.getters.loggedIn) {
        return
      }
      const categoryResponse = await this.$api.getCategory(this.$route.params.categoryId)
      if (categoryResponse.data.category) {
        this.category = categoryResponse.data.category
      } else {
        throw new Error('No category returned.')
      }
    }
  },
  methods: {
    async save () {
      let result = { data: null }
      if (this.category.Id) {
        result = await this.$api.categoryUpdate(this.category)
      } else {
        result = await this.$api.categoryCreate(this.category)
      }
      if (result.data.category) {
        this.$router.replace({ name: 'home' })
      }
    }
  }
}
</script>
