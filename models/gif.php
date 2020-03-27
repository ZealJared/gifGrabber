<?php
namespace GifGrabber;

use DateTime;

class Gif extends Model {
  /** @var Category|null */
  private $category = null;
  protected $alsoSerialize = [
    'AssetUrl'
  ];
  protected $doNotSerialize = [
    'file_type'
  ];

  public static function getTableName(): string
  {
    return 'gif';
  }

  public function getCreatedAt(): DateTime
  {
    return $this->getDateTime('created_at');
  }

  public function getUpdatedAt(): DateTime
  {
    return $this->getDateTime('updated_at');
  }

  public function getApproved(): bool
  {
    return $this->getBool('approved');
  }

  public function getTitle(): string
  {
    return $this->getString('title');
  }

  public function getCaption(): string
  {
    return $this->getString('caption');
  }

  public function getUrl(): string
  {
    return $this->getString('url');
  }

  public function getFileType(): string
  {
    return $this->getString('file_type');
  }

  public function getCategoryId(): int
  {
    return $this->getInteger('category_id');
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
