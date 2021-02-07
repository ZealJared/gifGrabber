<?php declare(strict_types=1);

namespace GifGrabber;

use DateTime;

class Category extends Model
{
  /** @var array<int,Gif>|null */
  private $gifList = null;

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
      'UpdatedAt' => (new DateTime())->format('Y-m-d G:i:s'),
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

  protected function hookBeforeSave(): void
  {
    Admin::guard();
  }

  protected function hookBeforeDelete(): void
  {
    Admin::guard();
  }

  /** @return array<int,Gif> */
  public function getGifList(): array
  {
    if (is_null($this->gifList)) {
      $sql = sprintf(
        'SELECT
        *
        FROM
        `%s`
        WHERE
        `category_id` = :categoryId
        %s',
        Gif::getTableName(),
        !Request::isAdmin() ? 'AND `approved` = 1' : ''
      );
      $statement = Database::getConnection()->prepare($sql);
      $statement->execute([
        'categoryId' => $this->getId(),
      ]);
      $this->gifList = Gif::fromRecords($statement);
    }
    return $this->gifList;
  }
}
