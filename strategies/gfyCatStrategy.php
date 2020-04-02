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
    copy($imageUrl, sprintf('%s/image.jpg', $this->getGif()->getStoragePath()));
  }

  protected function saveGif(): void
  {
    $matches = [];
    preg_match('~property="og:image" content="([^"]+?\.gif)"~', $this->getPageContent(), $matches);
    $animationUrl = $matches[1];
    copy($animationUrl, sprintf('%s/animation.gif', $this->getGif()->getStoragePath()));
  }

  protected function saveVideo(): void
  {
    $matches = [];
    preg_match('~property="og:video" content="([^"]+?\.mp4)"~', $this->getPageContent(), $matches);
    $videoUrl = $matches[1];
    copy($videoUrl, sprintf('%s/video.mp4', $this->getGif()->getStoragePath()));
  }
}

GifGrabber::addStrategy(new GfyCatStrategy());
