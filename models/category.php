<?php
namespace GifGrabber;

use DateTime;

class Category extends Model {
  protected $doNotSet = [
    'CreatedAt',
    'UpdatedAt'
  ];

  public static function getTableName(): string
  {
    return 'category';
  }

  protected function getDefaults(): array
  {
    return [
      'Id' => null,
      'Name' => null,
      'CreatedAt' => (new DateTime())->format('Y-m-d G:i:s'),
      'UpdatedAt' => (new DateTime())->format('Y-m-d G:i:s')
    ];
  }

  public function getName(): string
  {
    return $this->getString('name');
  }

  public function setName(string $name): void
  {
    $this->setString('name', $name);
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
