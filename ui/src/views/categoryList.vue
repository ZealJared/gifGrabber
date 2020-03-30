<template>
  <ul>
    <li v-for="(category, index) in categoryList" :key="index">{{ category.Name }}</li>
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
