<?php

namespace GifGrabber;

use Imagick;

class GenericStrategy extends Strategy
{
  /** @var bool */
  private $videoSaved = false;
  /** @var bool */
  private $imageSaved = false;
  /** @var bool */
  private $animationSaved = false;

  protected function getPattern(): string
  {
    return '~.*~';
  }

  protected function saveVideo(): void
  {
    // try to find mp4
    $matches = [];
    preg_match('~[\'"]([^\'"]+?\.mp4(?:\?[^\'"]+)?)[\'"]~', $this->getPageContent(), $matches);
    $videoUrl = $matches[1] ?? null;
    // if mp4 found
    if (!is_null($videoUrl)) {
      // copy to local storage
      copy($videoUrl, sprintf('%s/video.mp4', $this->getGif()->getStoragePath()));
      // mark video as saved
      $this->videoSaved = true;
      return;
    }
    // try to find reddit gif mp4
    $matches = [];
    preg_match('~[\'"]([^\'"]+?\.gif\?format=mp4(?:\&[^\'"]+)?)[\'"]~', $this->getPageContent(), $matches);
    $videoUrl = $matches[1] ?? null;
    // if mp4 found
    if (!is_null($videoUrl)) {
      $videoUrl = htmlspecialchars_decode($videoUrl);
      // copy to local storage
      copy($videoUrl, sprintf('%s/video.mp4', $this->getGif()->getStoragePath()));
      // mark video as saved
      $this->videoSaved = true;
      return;
    }
    // if mp4 not found, try to find m3u8 playlist
    preg_match('~[\'"]([^\'"]+?\.m3u8(?:\?[^\'"]+)?)[\'"]~', $this->getPageContent(), $matches);
    $videoUrl = $matches[1] ?? null;
    // if no playlist found, do not attempt to save video
    if (is_null($videoUrl)) {
      return;
    }
    // if playlist found, get base folder name (needed for subsequent relative URLs)
    $base = dirname($videoUrl);
    // get playlist file content
    $playListData = file_get_contents($videoUrl) ?: '';
    // find first (highest quality) video playlist
    $matches = [];
    preg_match('~\n([^,]+\.m3u8)~', $playListData, $matches);
    $videoUrl = $matches[1] ?? null;
    // if not found, do not try to save video
    if (is_null($videoUrl)) {
      return;
    }
    // if second playlist found, get content
    $videoUrl = sprintf('%s/%s', $base, $videoUrl);
    $playListData = file_get_contents($videoUrl) ?: '';
    // search second playlist content for a TS video file
    $matches = [];
    preg_match('~\n(.+\.ts)~', $playListData, $matches);
    $videoUrl = $matches[1] ?? null;
    // if not found, do not try to save video
    if (is_null($videoUrl)) {
      return;
    }
    // if found, copy TS to local storage
    $videoUrl = sprintf('%s/%s', $base, $videoUrl);
    $destinationTs = sprintf('%s/video.ts', $this->getGif()->getStoragePath());
    $destinationMp4 = sprintf('%s/video.mp4', $this->getGif()->getStoragePath());
    copy($videoUrl, $destinationTs);
    // convert TS to MP4
    exec(sprintf(
      'ffmpeg -i %s -c:v libx264 -c:a aac %s',
      $destinationTs,
      $destinationMp4
    ));
    // mark video as saved
    $this->videoSaved = true;
    return;
  }

  protected function saveImage(): void
  {
    /**
     * If video saved, get preview frame
     * if video not saved, find and save *.jpg set image saved
     */
    if ($this->videoSaved) {
      $mp4 = sprintf('%s/video.mp4', $this->getGif()->getStoragePath());
      $destination = sprintf('%s/image.jpg', $this->getGif()->getStoragePath());
      exec(sprintf(
        'ffmpeg -i %s -vframes 1 %s',
        $mp4,
        $destination
      ));
      $this->imageSaved = true;
      return;
    }
    // if page has og:image, use that
    $matches = [];
    preg_match('~<meta\s+property="og:image"\s+content="([^"]+\.(?:png|jpg|jpeg)(?:\?[^"]+)?)"~', $this->getPageContent(), $matches);
    $imageUrl = $matches[1] ?? null;
    if (!is_null($imageUrl)) {
      $imageUrl = htmlspecialchars_decode($imageUrl);
      // if png, convert
      $extension = strtolower(pathinfo($imageUrl, PATHINFO_EXTENSION));
      $destinationJpg = sprintf('%s/image.jpg', $this->getGif()->getStoragePath());
      if ($extension === 'png') {
        $destinationPng = sprintf('%s/image.png', $this->getGif()->getStoragePath());
        copy($imageUrl, $destinationPng);
        exec(sprintf(
          'gm convert -background white -resize 15000x15000\> %s %s',
          $destinationPng,
          $destinationJpg
        ));
      } else {
        copy($imageUrl, $destinationJpg);
      }
      $this->imageSaved = true;
      return;
    }
    // else, get whatever jpg
    $matches = [];
    preg_match('~[\'"]([^\'"]+?\.jpg(?:\?[^\'"]+)?)[\'"]~', $this->getPageContent(), $matches);
    $imageUrl = $matches[1] ?? null;
    if (!is_null($imageUrl)) {
      copy($imageUrl, sprintf('%s/image.jpg', $this->getGif()->getStoragePath()));
      $this->imageSaved = true;
      return;
    }
  }

  protected function saveGif(): void
  {
    /**
     * If video saved, convert to gif
     * if video not saved, find and save *.gif set animation saved
     *   if animation saved and image not saved, get preview frame from gif
     */
    if ($this->videoSaved) {
      $mp4 = sprintf('%s/video.mp4', $this->getGif()->getStoragePath());
      $destination = sprintf('%s/animation.gif', $this->getGif()->getStoragePath());
      exec(sprintf(
        'ffmpeg -i %s -vf "fps=12,scale=320:-1" -loop 0 %s',
        $mp4,
        $destination
      ));
      $this->animationSaved = true;
      return;
    }
    $matches = [];
    preg_match('~[\'"]([^\'"]+?\.gif(?:\?[^\'"]+)?)[\'"]~', $this->getPageContent(), $matches);
    $animationUrl = $matches[1] ?? null;
    if (!is_null($animationUrl)) {
      $animationUrl = htmlspecialchars_decode($animationUrl);
      copy($animationUrl, sprintf('%s/animation.gif', $this->getGif()->getStoragePath()));
      $this->animationSaved = true;
      if (!$this->imageSaved) {
        $gif = sprintf('%s/animation.gif', $this->getGif()->getStoragePath());
        $destination = sprintf('%s/image.jpg', $this->getGif()->getStoragePath());
        exec(sprintf(
          'ffmpeg -i %s -vframes 1 %s',
          $gif,
          $destination
        ));
        $this->imageSaved = true;
      }
      // TODO: convert GIF to mp4
      return;
    }
  }
}
