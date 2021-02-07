<?php

namespace GifGrabber;

class ImgurStrategy extends Strategy
{
  protected function getPattern(): string
  {
    return '~^(?:https?://(?:[^/]+?\.)*?)?imgur\.com~';
  }

  protected function fetchVideo(): void
  {
    $matches = [];
    $videoUrl = '';
    preg_match('~property="og:video"[\s\w\-="]+?content="([^"]+?\.mp4)"~', $this->getPageContent(), $matches);
    if (!empty($matches[1])) {
      $videoUrl = $matches[1];
    } else {
      preg_match('~<meta name="twitter:player:stream"[\s\w\-="]+?content="([^"]+\.mp4)"~', $this->getPageContent(), $matches);
      if(empty($matches[1]))
      {
        return;
      }
      $videoUrl = $matches[1];
    }
    $this->saveVideo($videoUrl);
  }

  protected function fetchImage(): void
  {
    $matches = [];
    preg_match('~<meta name="twitter:image"[\s\w\-="]+?content="([^"]+\.jpg)~', $this->getPageContent(), $matches);
    if(empty($matches[1]))
    {
      return;
    }
    $imageUrl = $matches[1];
    $this->saveImage($imageUrl);
  }

  protected function fetchAnimation(): void
  {
    return;
  }
}

GifGrabber::addStrategy(new ImgurStrategy());
