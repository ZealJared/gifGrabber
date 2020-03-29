<?php
namespace GifGrabber;

use DateTime;
use Throwable;

class Gif extends Model {
  /** @var Category|null */
  private $category = null;

  protected $doNotSet = [
    'AssetUrl'
  ];

  protected $alsoSerialize = [
    'AssetUrl'
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

  public function getAssetUrl(): string
  {
    return sprintf(
      '%s/gif/%d/animation.gif',
      Config::getStorageUrl(),
      $this->getId()
    );
  }
}
