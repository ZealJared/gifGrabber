<?php declare(strict_types=1);

namespace GifGrabber;

class GiferStrategy extends Strategy
{
  protected function getPattern(): string
  {
    return '~^(?:https?://(?:[^/]+?\.)*?)?gifer\.com~';
  }

  protected function fetchVideo(): void
  {
    return;
  }

  protected function fetchImage(): void
  {
    return;
  }

  protected function fetchAnimation(): void
  {
    $url = $this->getUrl();
    $url = preg_replace('~.gif$~', '', $url);
    $urlParts = explode('/', $url);
    $id = array_pop($urlParts);
    $source = sprintf(
      'https://i.gifer.com/embedded/download/%s.gif',
      $id
    );
    $this->saveAnimation($source);
  }
}

GifGrabber::addStrategy(new GiferStrategy());
