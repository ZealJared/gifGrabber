<template>
  <ul class="list-group">
    <li class="list-group-item" v-for="(gif, index) in gifList" :key="index">
      <router-link :to="{ name: 'gifView', params: { gifId: gif.Id } }">{{ gif.Title }}{{ !gif.Approved ? ' (unapproved)' : '' }}</router-link>
    </li>
  </ul>
</template>

<script>
export default {
  data () {
    return {
      gifList: []
    }
  },
  async mounted () {
    const response = await this.$api.getGifList(this.$route.params.categoryId)
    if (response.data.gif) {
      this.gifList = response.data.gif
    } else {
      throw new Error('No gif list returned.')
    }
  }
}
</script>
