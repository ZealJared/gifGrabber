* Link back to gif category from Gif view page ✓

# Create a strategy for each site in existing database GIFs

* https://imgur.com/p56yhlS ✓

* Generic image: https://xkcd.com/1732/

* Generic mp4: https://deadspin.com/scottish-golf-course-to-u-s-opens-windy-conditions-1826889294

  * Get \<video> poster as image if exists (convert to jpg if not already)

* Generic m3u8: https://www.reddit.com/r/EngineeringPorn/comments/8wk5e2/boat_stabiliser

  * Get \<video> poster as image if exists (convert to jpg if not already)

* TS to mp4

  ffmpeg -i input.ts -c:v libx264 -c:a aac video.mp4
* mp4 to GIF

  ffmpeg -i video.mp4 -vf "fps=12,scale=320:-1" -loop 0 animation.gif
* mp4 to jpg

  ffmpeg -i video.mp4 -vframes 1 image.jpg

## Priority
* page contains \<video> tag
  * video tag contains poster (get as image)
    * Set flag to convert video frame #1 to image
  * video tag contains *.mp4 (get)
    * video tag contains *.m3u8 (follow to TS, get, convert to mp4)
  * convert mp4 frame #1 to image if flag set
  * convert mp4 to gif
* page contains no \<video>, but contains *.gif
  * get gif
  * convert to mp4
  * convert to image
* page contains no \<video> and no *.gif
  * find first content-likely jpg/png
    * get, convert to jpg if needed

## Notes

ads will cause false results, work on filter to recognize likely ads as false results occur
