<?php
namespace GifGrabber;

class GfyCatStrategy extends Strategy {
  protected function getPattern(): string
  {
    return '~^(?:https?://(?:[^/]+?\.)*?)?gfycat\.com~';
  }

  protected function saveImage(): void
  {
    $matches = [];
    preg_match('~property="og:image" content="([^"]+?\.jpg)"~', $this->getPageContent(), $matches);
    $imageUrl = $matches[1];
    $destination = sprintf('%s/image.jpg', $this->getGif()->getStoragePath());
    if(file_exists($destination)){
      unlink($destination);
    }
    copy($imageUrl, $destination);
  }

  protected function saveGif(): void
  {
    $matches = [];
    preg_match('~property="og:image" content="([^"]+?\.gif)"~', $this->getPageContent(), $matches);
    $animationUrl = $matches[1];
    $destination = sprintf('%s/animation.gif', $this->getGif()->getStoragePath());
    if(file_exists($destination)){
      unlink($destination);
    }
    copy($animationUrl, $destination);
  }

  protected function saveVideo(): void
  {
    $matches = [];
    preg_match('~property="og:video" content="([^"]+?\.mp4)"~', $this->getPageContent(), $matches);
    $videoUrl = $matches[1];
    $destination = sprintf('%s/video.mp4', $this->getGif()->getStoragePath());
    if(file_exists($destination)){
      unlink($destination);
    }
    copy($videoUrl, $destination);
  }
}

GifGrabber::addStrategy(new GfyCatStrategy());
