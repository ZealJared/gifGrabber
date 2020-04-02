<template>
  <div class="alert alert-danger">Deleting GIF...</div>
</template>

<script>
export default {
  async mounted () {
    const gifResponse = await this.$api.gifDelete(this.$route.params.gifId)
    if (gifResponse.data.gif) {
      this.$router.replace({ name: 'categoryGifList', params: { categoryId: gifResponse.data.gif.CategoryId } })
    }
  }
}
</script>
