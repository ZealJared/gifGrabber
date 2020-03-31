<?php
namespace GifGrabber;

use DateTime;
use Exception;
use Throwable;

class Gif extends Model {
  /** @var Category|null */
  private $category = null;

  protected $doNotSet = [
    'ImageUrl',
    'AnimationUrl',
    'VideoUrl'
  ];

  protected $alsoSerialize = [
    'ImageUrl',
    'AnimationUrl',
    'VideoUrl'
  ];

  public static function getTableName(): string
  {
    return 'gif';
  }

  protected function getDefaults(): array
  {
    return [
      'Id' => null,
      'CreatedAt' => (new DateTime())->format('Y-m-d G:i:s'),
      'UpdatedAt' => (new DateTime())->format('Y-m-d G:i:s'),
      'Approved' => 0,
      'Title' => null,
      'Caption' => null,
      'Url' => null,
      'CategoryId' => null
    ];
  }

  public function getApproved(): bool
  {
    return $this->getBool('approved');
  }

  public function setApproved(bool $approved): void
  {
    $this->setBool('approved', $approved);
  }

  public function getTitle(): string
  {
    return $this->getString('title');
  }

  public function setTitle(string $title): void
  {
    $this->setString('title', $title);
  }

  public function getCaption(): ?string
  {
    try {
      return $this->getString('caption');
    } catch (Throwable $e) {
      return null;
    }
  }

  public function setCaption(string $caption = null): void
  {
    if (is_null($caption)) {
      $this->setNull('caption');
    } else {
      $this->setString('caption', $caption);
    }
  }

  public function getUrl(): string
  {
    return $this->getString('url');
  }

  public function setUrl(string $url): void
  {
    if (parse_url($url) === false) {
      throw new Exception('Provided URL is invalid.');
    }
    $headers = get_headers($url, 1);
    if ($headers === false) {
      throw new Exception(sprintf('Could not reach URL: %s', $url));
    }
    $integerKeyHeaders = array_filter($headers, function ($key) { return is_int($key); }, ARRAY_FILTER_USE_KEY);
    if (array_pop($integerKeyHeaders) !== 'HTTP/1.1 200 OK') {
      throw new Exception(sprintf('Request did not return 200 OK for URL: %s', $url));
    }
    $this->setString('url', $url);
  }

  public function getFileType(): string
  {
    return $this->getString('file_type');
  }

  public function getCategoryId(): int
  {
    return $this->getInteger('category_id');
  }

  public function setCategoryId(int $categoryId): void
  {
    $this->setInteger('category_id', $categoryId);
  }

  public function getCategory(): Category
  {
    if(is_null($this->category)){
      $this->category = Category::findById($this->getCategoryId());
    }
    return $this->category;
  }

  public function getStoragePath(): string
  {
    $path = sprintf(
      '%s/gif/%d',
      Config::getStoragePath(),
      $this->getId()
    );
    if (!file_exists($path)) {
      mkdir($path, 0777, true);
    }
    return $path;
  }

  public function getImageUrl(): string
  {
    return sprintf(
      '%s/gif/%d/image.jpg',
      Config::getStorageUrl(),
      $this->getId()
    );
  }

  public function getAnimationUrl(): string
  {
    return sprintf(
      '%s/gif/%d/animation.gif',
      Config::getStorageUrl(),
      $this->getId()
    );
  }

  public function getVideoUrl(): string
  {
    return sprintf(
      '%s/gif/%d/video.mp4',
      Config::getStorageUrl(),
      $this->getId()
    );
  }

  protected function hookBeforeSave(): void
  {
    Admin::guard();
    if ($this->wasChanged('url')) {
      GifGrabber::grab($this);
    }
  }

  protected function hookBeforeDelete(): void
  {
    Admin::guard();
  }
}
