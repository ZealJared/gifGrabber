<?php

namespace GifGrabber;

class GenericStrategy extends Strategy
{
  protected function getPattern(): string
  {
    return '~.*~';
  }

  protected function fetchVideo(): void
  {
    // try to find mp4
    $matches = [];
    preg_match('~[\'"]([^\'"]+?\.mp4(?:\?[^\'"]+)?)[\'"]~', $this->getPageContent(), $matches);
    $videoUrl = $matches[1] ?? null;
    // if mp4 found
    if (!is_null($videoUrl) && strstr($videoUrl, 'ripsave.com') === false) {
      $this->saveVideo($videoUrl);
      return;
    }
    // try to find reddit gif mp4
    $matches = [];
    preg_match('~[\'"]([^\'"]+?\.gif\?format=mp4(?:\&[^\'"]+)?)[\'"]~', $this->getPageContent(), $matches);
    $videoUrl = $matches[1] ?? null;
    // if mp4 found
    if (!is_null($videoUrl)) {
      $videoUrl = htmlspecialchars_decode($videoUrl);
      $this->saveVideo($videoUrl);
      return;
    }
    // if mp4 not found, try to find m3u8 playlist
    $videoUrl = Reddit::getTsUrl($this->getPageContent());
    if($videoUrl){
      $this->saveVideo($videoUrl);
    }
  }

  protected function fetchImage(): void
  {
    if(preg_match('~\.(jpg|jpeg|png)$~', $this->getUrl()))
    {
      $this->saveImage($this->getUrl());
      return;
    }
    // if page has og:image, use that
    $matches = [];
    preg_match('~<meta\s+property="og:image"\s+content="([^"]+\.(?:png|jpg|jpeg)(?:\?[^"]+)?)"~', $this->getPageContent(), $matches);
    $imageUrl = $matches[1] ?? null;
    if (!is_null($imageUrl)) {
      $imageUrl = htmlspecialchars_decode($imageUrl);
      $this->saveImage($imageUrl);
      return;
    }
    // video not set, get whatever jpg
    if($this->videoSaved || $this->animationSaved)
    {
      return;
    }
    $matches = [];
    preg_match('~[\'"]([^\'"]+?\.jpg(?:\?[^\'"]+)?)[\'"]~', $this->getPageContent(), $matches);
    $imageUrl = $matches[1] ?? null;
    if (!is_null($imageUrl)) {
      $this->saveImage($imageUrl);
      return;
    }
  }

  protected function fetchAnimation(): void
  {
    if(preg_match('~\.gif$~', $this->getUrl()))
    {
      $this->saveAnimation($this->getUrl());
      return;
    }
    // if we have the video, no need for the gif
    if($this->videoSaved){
      return;
    }
    $matches = [];
    preg_match('~[\'"]([^\'"]+?\.gif(?:\?[^\'"]+)?)[\'"]~', $this->getPageContent(), $matches);
    $animationUrl = $matches[1] ?? null;
    if (is_null($animationUrl)) {
      return;
    }
    $animationUrl = htmlspecialchars_decode($animationUrl);
    // some files are named '.gif' and served as jpg >8(
    if(strstr($animationUrl, 'format=png') !== false){
      return;
    }
    $this->saveAnimation($animationUrl);
  }
}
