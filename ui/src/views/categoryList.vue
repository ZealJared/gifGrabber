<template>
  <ul class="list-group">
    <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(category, index) in categoryList" :key="index">
      <router-link :to="{ name: 'categoryGifList', params: { categoryId: category.Id } }">{{ category.Name }}</router-link>
      <router-link v-if="loggedIn" class="btn btn-primary" :to="{ name: 'categoryEdit', params: { categoryId: category.Id } }">Edit</router-link>
    </li>
    <li v-if="loggedIn" class="list-group-item">
      <router-link class="btn btn-success" :to="{ name: 'categoryCreate' }">Add category</router-link>
    </li>
  </ul>
</template>

<script>
export default {
  data () {
    return {
      categoryList: []
    }
  },
  computed: {
    loggedIn () {
      return this.$store.getters.loggedIn
    }
  },
  async mounted () {
    const response = await this.$api.getCategoryList()
    if (response.data.category) {
      this.categoryList = response.data.category
    } else {
      throw new Error('No category list returned.')
    }
  }
}
</script>
