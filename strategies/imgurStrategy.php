<?php

namespace GifGrabber;

class ImgurStrategy extends Strategy
{
  protected function getPattern(): string
  {
    return '~^(?:https?://(?:[^/]+?\.)*?)?imgur\.com~';
  }

  protected function saveVideo(): void
  {
    $matches = [];
    preg_match('~property="og:video"\s+content="([^"]+?\.mp4)"~', $this->getPageContent(), $matches);
    if (!empty($matches[1])) {
      $videoUrl = $matches[1];
    } else {
      preg_match('~<meta name="twitter:player:stream"\s+content="([^"]+\.mp4)" />~', $this->getPageContent(), $matches);
      $videoUrl = $matches[1];
    }
    copy($videoUrl, sprintf('%s/video.mp4', $this->getGif()->getStoragePath()));
  }

  protected function saveImage(): void
  {
    $matches = [];
    preg_match('~<meta name="twitter:image"\s+content="([^"]+\.jpg)" />~', $this->getPageContent(), $matches);
    $imageUrl = $matches[1];
    copy($imageUrl, sprintf('%s/image.jpg', $this->getGif()->getStoragePath()));
  }

  protected function saveGif(): void
  {
    $videoPath = sprintf('%s/video.mp4', $this->getGif()->getStoragePath());
    $animationPath = sprintf('%s/animation.gif', $this->getGif()->getStoragePath());
    exec(sprintf(
      'ffmpeg -i %s -vf "fps=12,scale=320:-1" -loop 0 %s',
      $videoPath,
      $animationPath
    ));
  }
}

GifGrabber::addStrategy(new ImgurStrategy());
