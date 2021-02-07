<?php
namespace GifGrabber;

class GfyCatStrategy extends Strategy {
  protected function getPattern(): string
  {
    return '~^(?:https?://(?:[^/]+?\.)*?)?gfycat\.com~';
  }

  protected function fetchImage(): void
  {
    $matches = [];
    preg_match('~property="og:image" content="([^"]+?\.jpg)"~', $this->getPageContent(), $matches);
    if(empty($matches[1]))
    {
      return;
    }
    $imageUrl = $matches[1];
    $this->saveImage($imageUrl);
  }

  protected function fetchAnimation(): void
  {
    $matches = [];
    preg_match('~property="og:image" content="([^"]+?\.gif)"~', $this->getPageContent(), $matches);
    if(empty($matches[1])){
      return;
    }
    $animationUrl = $matches[1];
    $this->saveAnimation($animationUrl);
  }

  protected function fetchVideo(): void
  {
    $matches = [];
    preg_match('~property="og:video" content="([^"]+?\.mp4)"~', $this->getPageContent(), $matches);
    if(empty($matches[1]))
    {
      return;
    }
    $videoUrl = $matches[1];
    $this->saveVideo($videoUrl);
  }
}

GifGrabber::addStrategy(new GfyCatStrategy());
