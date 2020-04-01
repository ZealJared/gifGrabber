<template>
  <ul class="list-group">
    <li class="list-group-item" v-for="(category, index) in categoryList" :key="index">
      <router-link :to="{ name: 'categoryGifList', params: { categoryId: category.Id } }">{{ category.Name }}</router-link>
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
