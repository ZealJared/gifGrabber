export default class Api {
  constructor (router, store) {
    this.baseUrl = 'http://localhost:3000'
    this.router = router
    this.store = store
  }

  async request (url, method = 'GET', body = null) {
    const options = {
      method: method
    }
    if (body) {
      options.body = body
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
}
