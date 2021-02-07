<?php
namespace GifGrabber;

use Exception;

abstract class Strategy {
  /** @var Gif|null */
  private $gif = null;
  /** @var string|null */
  private $pageContent = null;
  protected $videoSaved = false;
  protected $imageSaved = false;
  protected $animationSaved = false;

  protected function matches(Gif $gif): bool {
    return preg_match($this->getPattern(), $gif->getUrl()) === 1;
  }

  protected function getUrl(): string
  {
    return trim($this->getGif()->getUrl(), '/');
  }

  private function getGif(): Gif
  {
    if (is_null($this->gif)) {
      throw new Exception('Strategy Gif property is null.');
    }
    return $this->gif;
  }

  protected function getPageContent(): string
  {
    if (is_null($this->pageContent)) {
      $context = stream_context_create([
        'http' => [
          'header' => 'Cookie: over18=1\r\n'
        ]
      ]);
      $pageContent = file_get_contents($this->getGif()->getUrl(), context: $context);
      if ($pageContent === false) {
        throw new Exception(sprintf('Unable to load URL: %s', $this->getGif()->getUrl()));
      }
      $this->pageContent = $pageContent;
    }
    return $this->pageContent;
  }

  abstract protected function getPattern(): string;

  abstract protected function fetchImage(): void;

  abstract protected function fetchAnimation(): void;

  abstract protected function fetchVideo(): void;

  protected function saveImage(string $imageUrl): void
  {
    // error_log(sprintf('Saving image: %s', $imageUrl));
    $this->getGif()->saveImage($imageUrl);
    $this->imageSaved = true;
  }

  protected function saveVideo(string $videoUrl): void
  {
    // error_log(sprintf('Saving video: %s', $videoUrl));
    $this->getGif()->saveVideo($videoUrl);
    $this->videoSaved = true;
  }

  protected function saveAnimation(string $animationUrl): void
  {
    // error_log(sprintf('Saving animation: %s', $animationUrl));
    $this->getGif()->saveAnimation($animationUrl);
    $this->animationSaved = true;
  }

  protected function videoToAnimation(): void
  {
    // error_log('Converting video to animation.');
    $this->getGif()->videoToAnimation();
    $this->animationSaved = true;
  }

  protected function animationToVideo(): void
  {
    // error_log('Converting animation to video.');
    $this->getGif()->animationToVideo();
    $this->videoSaved = true;
  }

  protected function videoToImage(): void
  {
    // error_log('Getting image from video.');
    $this->getGif()->videoToImage();
    $this->imageSaved = true;
  }

  protected function animationToImage(): void
  {
    // error_log('Getting image from animation.');
    $this->getGif()->animationToImage();
    $this->imageSaved = true;
  }

  protected function normalize(): void
  {
    // if video is saved and animation is not, video to animation
    if($this->videoSaved && !$this->animationSaved){
      $this->videoToAnimation();
    }
    // if video is saved and image is not, video to image
    if($this->videoSaved && !$this->imageSaved){
      $this->videoToImage();
    }
    // if animation is saved and video is not and image is not, animation to image
    if($this->animationSaved && !$this->videoSaved && !$this->imageSaved){
      $this->animationToImage();
    }
    // if animation is saved and video is not, animation to video
    if($this->animationSaved && !$this->videoSaved){
      $this->animationToVideo();
    }
  }

  public function execute(Gif $gif): bool
  {
    if (!$this->matches($gif)) {
      return false;
    }
    $this->gif = $gif;
    $this->fetchVideo();
    $this->fetchAnimation();
    $this->fetchImage();
    $this->normalize();
    return true;
  }
}
