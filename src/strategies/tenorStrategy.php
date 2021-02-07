<?php

namespace GifGrabber;

class TenorStrategy extends Strategy
{
  protected function getPattern(): string
  {
    return '~^(?:https?://(?:[^/]+?\.)*?)?tenor\.com~';
  }

  protected function fetchVideo(): void
  {
    $matches = [];
    preg_match('~"mp4":{"url":"([^"]+)~', $this->getPageContent(), $matches);
    if(empty($matches[1]))
    {
      return;
    }
    $mp4Url = json_decode(sprintf('"%s"', $matches[1]));
    $this->saveVideo($mp4Url);
  }

  protected function fetchImage(): void
  {
    return;
  }

  protected function fetchAnimation(): void
  {
    return;
  }
}

GifGrabber::addStrategy(new TenorStrategy());
