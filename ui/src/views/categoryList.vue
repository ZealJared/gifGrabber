<template>
  <ul>
    <li v-for="(index, category) in categoryList" :key="index">{{ category.Name }}</li>
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
    this.categoryList = await this.$api.getCategoryList()
  }
}
</script>
