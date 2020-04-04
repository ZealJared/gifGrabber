<template>
  <div v-if="gif">
    <router-link :to="{ name: 'categoryGifList', params: { categoryId: gif.CategoryId } }">&lt; Back</router-link>
    <div class="d-flex justify-content-between align-items-center">
      <h1>{{ gif.Title }}{{ !gif.Approved ? ' (unapproved)' : '' }}</h1>
      <router-link v-if="loggedIn" class="btn btn-primary" :to="{ name: 'gifEdit', params: { gifId: gif.Id } }">Edit</router-link>
    </div>
    <p>Original found at: <a :href="gif.Url">{{ gif.Url }}</a></p>
    <ul class="nav nav-tabs mb-3">
      <li class="nav-item">
        <a class="nav-link" :class="{ active: format === 'image' }" @click.prevent="viewFormat('image')" href="#">Image</a>
      </li>
      <li v-if="!!gif.AnimationUrl" class="nav-item">
        <a class="nav-link" :class="{ active: format === 'animation' }" @click.prevent="viewFormat('animation')" href="#">Animation</a>
      </li>
      <li v-if="!!gif.VideoUrl" class="nav-item">
        <a class="nav-link" :class="{ active: format === 'video' }" @click.prevent="viewFormat('video')" href="#">Video</a>
      </li>
    </ul>
    <figure class="figure">
      <img v-if="format === 'image'" class="figure-img img-fluid rounded" :src="gif.ImageUrl" :alt="gif.Title">
      <img v-if="format === 'animation'" class="figure-img img-fluid rounded" :src="gif.AnimationUrl" :alt="gif.Title">
      <video v-if="format === 'video'" class="figure-img img-fluid rounded" :src="gif.VideoUrl" autoplay loop controls></video>
      <figcaption class="figure-caption">{{ gif.Caption }}</figcaption>
    </figure>
  </div>
</template>

<script>
export default {
  data () {
    return {
      gif: null,
      format: 'image'
    }
  },
  computed: {
    loggedIn () {
      return this.$store.getters.loggedIn
    }
  },
  async mounted () {
    const response = await this.$api.getGif(this.$route.params.gifId)
    if (response.data.gif) {
      this.gif = response.data.gif
    } else {
      throw new Error('No gif returned.')
    }
  },
  methods: {
    viewFormat (format) {
      this.format = format
    }
  }
}
</script>
