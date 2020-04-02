<template>
  <div class="alert alert-danger">Deleting category...</div>
</template>

<script>
export default {
  async mounted () {
    const categoryResponse = await this.$api.categoryDelete(this.$route.params.categoryId)
    if (categoryResponse.data.category) {
      this.$router.replace({ name: 'home' })
    }
  }
}
</script>
