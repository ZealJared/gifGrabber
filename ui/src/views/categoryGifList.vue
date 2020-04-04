<template>
  <div>
    <router-link :to="{ name: 'home' }">&lt; Back</router-link>
    <h1 v-if="category">{{ category.Name }}</h1>
    <router-link class="btn btn-primary mb-3" :to="{ name: 'gifView', params: { gifId: randomGifId() } }">Random</router-link>
    <div class="d-flex flex-wrap">
      <figure v-for="(gif, index) in gifList" :key="index" class="figure mr-3" @focus="animate(index)" @mouseover="animate(index)" @blur="stop" @mouseout="stop">
        <router-link :to="{ name: 'gifView', params: { gifId: gif.Id } }">
          <div class="d-flex align-items-center justify-content-around figure-img rounded bg-dark shadowbox">
            <img :src="animateIndex === index ? gif.AnimationUrl : gif.ImageUrl" :alt="gif.Title" class="img-fluid shadowbox-img" tabindex="-1">
          </div>
          <figcaption class="figure-caption">{{ gif.Title }}{{ !gif.Approved ? ' (unapproved)' : '' }}</figcaption>
        </router-link>
      </figure>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      animateIndex: -1,
      category: null,
      gifList: [
        { Id: 0 }
      ]
    }
  },
  methods: {
    randomGifId () {
      const index = Math.floor(Math.random() * this.gifList.length)
      return this.gifList[index].Id
    },
    animate (index) {
      if (!this.gifList[index].AnimationUrl) {
        return
      }
      this.animateIndex = index
    },
    stop () {
      this.animateIndex = -1
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

<style>
  .shadowbox {
    width: 320px;
    height: 320px;
    overflow: hidden;
  }
  .shadowbox-img {
    max-width: 320px;
    max-height: 320px;
  }
</style>
