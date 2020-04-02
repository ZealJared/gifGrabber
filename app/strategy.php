<?php
namespace GifGrabber;

use Exception;

abstract class Strategy {
  /** @var Gif|null */
  private $gif = null;
  /** @var string|null */
  private $pageContent = null;

  protected function matches(Gif $gif): bool {
    return preg_match($this->getPattern(), $gif->getUrl()) === 1;
  }

  protected function getGif(): Gif
  {
    if (is_null($this->gif)) {
      throw new Exception('Strategy Gif property is null.');
    }
    return $this->gif;
  }

  protected function getPageContent(): string
  {
    if (is_null($this->pageContent)) {
      $pageContent = file_get_contents($this->getGif()->getUrl());
      if ($pageContent === false) {
        throw new Exception(sprintf('Unable to load URL: %s', $this->getGif()->getUrl()));
      }
      $this->pageContent = $pageContent;
    }
    return $this->pageContent;
  }

  abstract protected function getPattern(): string;

  abstract protected function saveImage(): void;

  abstract protected function saveGif(): void;

  abstract protected function saveVideo(): void;

  public function execute(Gif $gif): bool
  {
    if (!$this->matches($gif)) {
      return false;
    }
    $this->gif = $gif;
    $this->saveVideo();
    $this->saveImage();
    $this->saveGif();
    return true;
  }
}
