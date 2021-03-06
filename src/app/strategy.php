<?php declare(strict_types=1);
namespace GifGrabber;

use Exception;

abstract class Strategy
{
  private Gif|

null $gif = null;

  private string|

null $pageContent = null;

  protected bool $videoSaved = false;

  protected bool $imageSaved = false;

  protected bool $animationSaved = false;

  protected function matches(Gif $gif): bool
  {
    $isMatch = (preg_match($this->getPattern(), $gif->getUrl()) === 1);
    if (!$isMatch) {
      Utility::log(sprintf(
        '%s does not match %s',
        $gif->getUrl(),
        $this->getPattern()
      ));
    }
    return $isMatch;
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
          'header' => 'Cookie: over18=1\r\n',
        ],
      ]);
      $pageContent = file_get_contents($this->getGif()->getUrl(), use_include_path: false, context: $context);
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
    Utility::log(sprintf('Saving image: %s', $imageUrl));
    $this->getGif()->saveImage($imageUrl);
    $this->imageSaved = true;
  }

  protected function saveVideo(string $videoUrl): void
  {
    Utility::log(sprintf('Saving video: %s', $videoUrl));
    $this->getGif()->saveVideo($videoUrl);
    $this->videoSaved = true;
  }

  protected function saveAnimation(string $animationUrl): void
  {
    Utility::log(sprintf('Saving animation: %s', $animationUrl));
    $this->getGif()->saveAnimation($animationUrl);
    $this->animationSaved = true;
  }

  protected function videoToAnimation(): void
  {
    Utility::log('Converting video to animation.');
    $this->getGif()->videoToAnimation();
    $this->animationSaved = true;
  }

  protected function animationToVideo(): void
  {
    Utility::log('Converting animation to video.');
    $this->getGif()->animationToVideo();
    $this->videoSaved = true;
  }

  protected function videoToImage(): void
  {
    Utility::log('Getting image from video.');
    $this->getGif()->videoToImage();
    $this->imageSaved = true;
  }

  protected function animationToImage(): void
  {
    Utility::log('Getting image from animation.');
    $this->getGif()->animationToImage();
    $this->imageSaved = true;
  }

  protected function normalize(): void
  {
    // if video is saved and animation is not, video to animation
    if ($this->videoSaved && !$this->animationSaved) {
      $this->videoToAnimation();
    }
    // if video is saved and image is not, video to image
    if ($this->videoSaved && !$this->imageSaved) {
      $this->videoToImage();
    }
    // if animation is saved and video is not and image is not, animation to image
    if ($this->animationSaved && !$this->videoSaved && !$this->imageSaved) {
      $this->animationToImage();
    }
    // if animation is saved and video is not, animation to video
    if ($this->animationSaved && !$this->videoSaved) {
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
