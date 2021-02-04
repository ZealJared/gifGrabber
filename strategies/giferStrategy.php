<?php

namespace GifGrabber;

class GiferStrategy extends Strategy
{
  protected function getPattern(): string
  {
    return '~^(?:https?://(?:[^/]+?\.)*?)?gifer\.com~';
  }

  protected function saveVideo(): void
  {
    return;
  }

  protected function saveImage(): void
  {
    return;
  }

  protected function saveGif(): void
  {
    $url = $this->getGif()->getUrl();
    $url = preg_replace('~.gif$~', '', $url);
    $urlParts = explode('/', $url);
    $id = array_pop($urlParts);
    $source = sprintf(
      'https://i.gifer.com/embedded/download/%s.gif',
      $id
    );
    $animationPath = sprintf('%s/animation.gif', $this->getGif()->getStoragePath());
    if(file_exists($animationPath)){
      unlink($animationPath);
    }
    copy($source, $animationPath);
    $this->animationSaved = true;
    $videoPath = sprintf('%s/video.mp4', $this->getGif()->getStoragePath());
    exec(sprintf(
      'ffmpeg -i %s -movflags faststart -pix_fmt yuv420p -vf "scale=trunc(iw/2)*2:trunc(ih/2)*2" %s',
      $animationPath,
      $videoPath
    ));
    $this->videoSaved = true;
    $destination = sprintf('%s/image.jpg', $this->getGif()->getStoragePath());
    if(file_exists($destination)){
      unlink($destination);
    }
    exec(sprintf(
      'ffmpeg -i %s -vframes 1 %s',
      $animationPath,
      $destination
    ));
    $this->imageSaved = true;
  }
}

GifGrabber::addStrategy(new GiferStrategy());
