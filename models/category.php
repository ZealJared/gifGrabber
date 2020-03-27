<?php
namespace GifGrabber;

use DateTime;

class Category extends Model {
  public static function getTableName(): string
  {
    return 'category';
  }

  public function getName(): string
  {
    return $this->getString('name');
  }

  public function getCreatedAt(): DateTime
  {
    return $this->getDateTime('created_at');
  }

  public function getUpdatedAt(): DateTime
  {
    return $this->getDateTime('updated_at');
  }
}
