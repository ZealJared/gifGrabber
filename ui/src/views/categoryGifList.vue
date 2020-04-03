<template>
  <div>
    <h1 v-if="category">{{ category.Name }}</h1>
    <ul v-if="gifList.length > 0" class="list-group">
      <li class="list-group-item">
        <router-link class="btn btn-primary" :to="{ name: 'gifView', params: { gifId: randomGifId() } }">Random</router-link>
      </li>
      <li class="list-group-item" v-for="(gif, index) in gifList" :key="index">
        <router-link :to="{ name: 'gifView', params: { gifId: gif.Id } }">{{ gif.Title }}{{ !gif.Approved ? ' (unapproved)' : '' }}</router-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data () {
    return {
      category: null,
      gifList: []
    }
  },
  methods: {
    randomGifId () {
      const index = Math.floor(Math.random() * this.gifList.length)
      return this.gifList[index].Id
    }
  },
  async mounted () {
    const gifResponse = await this.$api.getGifList(this.$route.params.categoryId)
    if (gifResponse.data.gif) {
      this.gifList = gifResponse.data.gif
    } else {
      throw new Error('No gif list returned.')
    }
    const categoryResponse = await this.$api.getCategory(this.$route.params.categoryId)
    if (categoryResponse.data.category) {
      this.category = categoryResponse.data.category
    } else {
      throw new Error('No category returned.')
    }
  }
}
</script>
