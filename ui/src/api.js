export default class Api {
  constructor (router, store) {
    this.baseUrl = 'http://localhost:3000'
    this.router = router
    this.store = store
    this.userName = null
    this.password = null
  }

  async request (url, method = 'GET', body = null) {
    const options = {
      method: method,
      headers: new Headers()
    }
    if (this.loggedIn()) {
      const token = btoa(`${this.getUserName()}:${this.getPassword()}`)
      const authorization = `Basic ${token}`
      options.headers.append('Authorization', authorization)
    }
    if (body) {
      options.body = JSON.stringify(body)
    }
    const response = await window.fetch(`${this.baseUrl}/${url}`, options)
    const text = await response.text()
    let object = null
    try {
      object = JSON.parse(text)
    } catch (e) {
      object = null
    }
    if (object === null) {
      throw new Error(text)
    }
    if (object.errors) {
      this.store.commit('setErrors', object.errors)
      throw new Error(object.errors[0])
    }
    return object
  }

  getRequest (url) {
    return this.request(url, 'GET')
  }

  postRequest (url, body) {
    return this.request(url, 'POST', body)
  }

  getCategoryList () {
    return this.getRequest('category')
  }

  setUserName (userName) {
    window.localStorage.setItem('userName', userName)
    this.userName = userName
  }

  getUserName () {
    if (!this.userName) {
      this.setUserName(window.localStorage.getItem('userName'))
    }
    return this.userName
  }

  setPassword (password) {
    window.localStorage.setItem('password', password)
    this.password = password
  }

  getPassword () {
    if (!this.password) {
      this.setPassword(window.localStorage.getItem('password'))
    }
    return this.password
  }

  adminLogin (userName, password) {
    this.setUserName(userName)
    this.setPassword(password)
  }

  getGifList (categoryId) {
    return this.getRequest(`category/${categoryId}/gif`)
  }

  getGif (gifId) {
    return this.getRequest(`gif/${gifId}`)
  }

  gifCreate (gif) {
    return this.postRequest('gif', gif)
  }

  gifUpdate (gif) {
    return this.postRequest(`gif/${gif.Id}`, gif)
  }

  gifDelete (gifId) {
    return this.getRequest(`gif/${gifId}/delete`)
  }

  loggedIn () {
    return this.getUserName() !== 'null' && this.getPassword() !== 'null'
  }
}
