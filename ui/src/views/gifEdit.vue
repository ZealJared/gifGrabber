<template>
  <form @submit.prevent="save()">
    <div class="form-group">
      <label for="categoryId">Category</label>
      <select v-model="gif.CategoryId" class="form-control" name="categoryList" id="categoryList">
        <option value="0" disabled>Choose one</option>
        <option v-for="(category, index) in categoryList" :key="index" :value="category.Id">{{ category.Name }}</option>
      </select>
    </div>
    <div class="form-group">
      <label for="title">Title</label>
      <input v-model="gif.Title" class="form-control" type="text" name="title" id="title">
    </div>
    <div class="form-group">
      <label for="caption">Caption</label>
      <input v-model="gif.Caption" class="form-control" type="text" name="caption" id="caption">
    </div>
    <div class="form-group">
      <label for="url">Grab from original URL</label>
      <input v-model="gif.Url" class="form-control" type="text" name="url" id="url">
    </div>
    <div class="form-group">
      <div v-if="loggedIn" class="form-check">
        <input v-model="gif.Approved" type="checkbox" class="form-check-input" id="approved">
        <label class="form-check-label" for="approved">Approved</label>
      </div>
    </div>
    <div class="d-flex justify-content-between align-items-center">
      <button type="submit" class="btn btn-primary">Save</button>
      <router-link :to="{ name: 'gifDelete', params: { gifId: gif.Id } }" v-if="!!gif.Id" class="btn btn-danger">Delete</router-link>
    </div>
  </form>
</template>

<script>
export default {
  data () {
    return {
      gif: {
        CategoryId: 0,
        Title: '',
        Caption: '',
        Url: '',
        Approved: false
      },
      categoryList: [],
      loggedIn: false
    }
  },
  async mounted () {
    this.loggedIn = this.$api.loggedIn()
    const categoryResponse = await this.$api.getCategoryList()
    if (categoryResponse.data.category) {
      this.categoryList = categoryResponse.data.category
    } else {
      throw new Error('No category list returned.')
    }
    if (this.$route.params.gifId) {
      if (!this.loggedIn) {
        return
      }
      const gifResponse = await this.$api.getGif(this.$route.params.gifId)
      if (gifResponse.data.gif) {
        this.gif = gifResponse.data.gif
      } else {
        throw new Error('No gif returned.')
      }
    }
  },
  methods: {
    async save () {
      let result = { data: null }
      if (this.gif.Id) {
        result = await this.$api.gifUpdate(this.gif)
      } else {
        result = await this.$api.gifCreate(this.gif)
      }
      if (result.data.gif) {
        if (this.loggedIn || result.data.gif.Approved) {
          this.$router.replace({ name: 'gifView', params: { gifId: result.data.gif.Id } })
        } else {
          this.$router.replace({ name: 'home' })
        }
      }
    }
  }
}
</script>
