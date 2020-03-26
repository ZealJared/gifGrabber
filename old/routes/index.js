var express = require('express')
var router = express.Router()
const URL = require('../url')

/* GET home page. */
router.get('/', function(req, res, next) {
  res.render('index')
})

router.post('/category', function(req, res, next) {
  res.redirect('/category/' + req.body.category)
})

router.get('/category/:category', function(req, res, next) {
  const Category = require('../models/category')
  Category.findById(req.params.category)
  .then(category => {
    res.redirect("/category/" + category.id + "/" + URL.encode(category.name))
  })
})

router.get('/category/:category/:catName/:page?', function(req, res, next) {
  var pageSize = 10
  const Gif = require("../models/gif")
  let category = req.params.category
  res.locals.categoryId = category
  let requestedPage = req.params.page ? parseInt(req.params.page, 10) - 1 : false
  let page = requestedPage > 0 ? requestedPage : 0
  res.locals.page = page
  res.locals.returnTo = category + "/" + req.params.catName + "/"
  if(req.session.admin && parseInt(category, 10) < 0){
    // show unapproved
    Gif.findAll({
      where: {
        approved: 0
      },
      offset: page * pageSize,
      limit: pageSize
    }).then((gifs) => {
      res.locals.admin = true
      res.locals.gifs = gifs
      res.render('index')
    })
    return
  }
  if(parseInt(category, 10) < 0){
    category = 0
  }
  Gif.findAll({
    where: {
      categoryId: category,
      approved: 1
    },
    offset: page * pageSize,
    limit: pageSize
  }).then((gifs) => {
    res.locals.gifs = gifs
    res.render('index')
  })
  return
})

const getPage = function(res, url, callback, gif){
  url = url.replace('.gifv', '.mp4')
  console.log('Getting ' + url)
  let https = null
  if(!url.includes('http:')){
    if(!url.includes('https:')){
      url = `https:${url}`
    }
    https = require('follow-redirects').https
  } else {
    https = require('follow-redirects').http
  }
  https.get(url, image => {
    let type = "html"
    let imageStream = null
    let sourceString = null
    
    if(image.headers["content-type"].match(/(image\/|video\/)/)){
      type = image.headers["content-type"].replace(/(image\/|video\/)/, '')
      // create file stream using gifId
      const fs = require('fs')
      imageStream = fs.createWriteStream("./public/images/" + gif.id + "." + type)
      imageStream.on('error', (err) => {
        console.log(err)
      })
    } else {
      sourceString = ''
    }
    image.on('error', (err) => {
      console.log(err)
    })
    image.on('data', chunk => {
      if(type == "html"){
        sourceString += chunk
      } else {
        // write to file stream
        imageStream.write(chunk)
      }
    })
    image.on('end', () => {
      if(type == "html"){
        callback(res, url, gif, type, sourceString)
      } else {
        imageStream.end()
        if(type == "gif"){
          /** Use node-canvas to get preview image for GIF */
          getPreviewFor(gif.id)
          .then(() => {
            callback(res, url, gif, type)
          })
          .catch((err) => {
            console.log(err)
            res.send("Failed... sorry. :-(")
          })
        } else {
          callback(res, url, gif, type)
        }
      }
    })
  })
}

const handleUrl = function(res, url, gif, type, err){
  if(err){
    const DomParser = require('dom-parser')
    const parser = new DomParser()
    let dom = parser.parseFromString(err)
    if(url.includes('imgur')){
      let id = dom.getElementsByClassName('post-image-container')[0].getAttribute('id')
      getPage(res, "http://i.imgur.com/" + id + ".gifv", handleUrl, gif)
    } else {
      let match = null
      match = err.match(/(\/\/[^\"]+?\.mp4)/)
      if(!match){
        match = err.match(/\"scrubberThumbSource\"\:\"([^\"]+?)\"/)
      }
      if(!match){
        match = err.match(/<(?:amp\-)img src=['"](.*?\/\/.*?)['"]/)
      }
      try {
        let tryUrl = match[1].replace(/^\/\//, "https://")
        getPage(res, tryUrl, handleUrl, gif)
      } catch (e) {
        console.log(e)
        res.send("Failed... sorry. :-(")
      }
    }
  } else {
    gif.type = type
    gif.save().then(gif => {
      res.redirect("/category/" + gif.categoryId)
    })
  }
}

router.post('/gif', (req, res, next) => {
  if(req.body.title.length < 1){
    res.send("You must supply a title.")
    return
  }
  if(req.body.url.length < 11){
    res.send("You must supply a valid URL.")
    return
  }
  
  // save to DB
  const Gif = require('../models/gif')
  Gif.create({
    approved: 0,
    title: req.body.title,
    caption: req.body.caption,
    url: req.body.url,
    type: "",
    categoryId: req.body.category
  }).then(gif => {
    // try to get image
    let url = req.body.url
    getPage(res, url, handleUrl, gif)
  }).catch(err => {
    res.send(err.errors[0].message)
  })
})

router.get('/edit/:gifId/:gifTitle?', (req, res, next) => {
  const Gif = require('../models/gif')
  Gif.findById(req.params.gifId)
  .then(gif => {
    res.locals.gif = gif
    res.render('edit')
  })
})

router.post('/edit', (req, res, next) => {
  // must be admin
  if(!req.session.admin){
    res.send('You must be admin to edit.')
    return
  }
  const Gif = require('../models/gif')
  let urlIsChanging = false
  Gif.findById(req.body.gif)
  .then(gif => {
    if(req.body.delete){
      let categoryId = gif.categoryId
      return gif.destroy()
    }
    gif.categoryId = req.body.category
    gif.title = req.body.title
    let newUrl = req.body.url
    if(gif.url != newUrl){
      urlIsChanging = true
      gif.url = newUrl
    }
    gif.caption = req.body.caption
    if(req.body.approved){
      gif.approved = req.body.approved
    } else {
      gif.approved = 0
    }
    return gif.save()
  })
  .then(gif => {
    if(urlIsChanging){
      getPage(res, gif.url, handleUrl, gif)
    } else {
      res.redirect("/category/" + gif.categoryId)
    }
  })
})

router.get('/login', (req, res, next) => {
  res.render('login')
})

router.post('/login', (req, res, next) => {
  if(req.body.email === "admin@physicspdx.com" && req.body.password === "***REMOVED***"){
    req.session.admin = true
  }
  res.redirect('/')
})

const getPreviewFor = function(gifId) {
  const { exec } = require('child_process')
  return new Promise((resolve, reject) => {
    exec(`convert "../public/images/${gifId}.gif[0]" ../public/images/${gifId}.jpg`, { cwd: __dirname }, (err) => {
      if(err){
        reject(err)
      }
      resolve()
    })
  })
}

module.exports = router
