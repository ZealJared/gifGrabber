;(function(){
  let previews = Array.from(document.querySelectorAll('.gif-preview'))
  previews.forEach(function(preview){
    preview.gif = preview.parentElement.querySelector('.gif-image')
    preview.gif.preview = preview
    preview.addEventListener('click', function(){
      this.style.display = "none"
      this.gif.style.display = "block"
    })
    preview.gif.addEventListener('click', function(){
      this.style.display = "none"
      this.preview.style.display = "block"
    })
  })
})()

;(function(){
  let expandToggles = Array.from(document.querySelectorAll('.gif-expand'))
  expandToggles.forEach(function(expandToggle){
    expandToggle.addEventListener('click', function(){
      // toggle active
      this.parentElement.classList.toggle('active')
    })
  })
})()