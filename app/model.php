<?php

namespace GifGrabber;

use DateTime;
use Exception;
use JsonSerializable;
use PDO;
use PDOStatement;
use stdClass;
use Throwable;

abstract class Model implements JsonSerializable
{
  /** @var array<string,(float|int|string|null)> */
  private $data = [];
  /** @var array<string,bool> */
  private $changed = [];
  /** @var array<string,string> */
  protected $aliases = [];
  /** @var array<int,string> */
  protected $alsoSerialize = [];
  /** @var array<int,string> */
  protected $doNotSet = [];
  /** @var array<int,string> */
  private $doNotEverSet = [
    'Id',
    'CreatedAt',
    'UpdatedAt'
  ];

  abstract public static function getTableName(): string;

  /** @return array<string,(null|string|float|int)> */
  protected function getDefaults(): array
  {
    return [];
  }

  public function jsonSet(object $data = null): void
  {
    if (!is_null($data)) {
      foreach (get_object_vars($data) as $property => $value) {
        if (in_array($property, $this->doNotSet) || in_array($property, $this->doNotEverSet)) {
          continue;
        }
        $methodName = sprintf('set%s', $property);
        if (is_object($value) && property_exists($value, 'date') && property_exists($value, 'timezone')) {
          $value = new DateTime(sprintf('%s %s', $value->date, $value->timezone));
        }
        $this->$methodName($value);
      }
    }
  }

  /** @param array<string,(null|string|float|int)> $data */
  final public function __construct(array $data = null)
  {
    if (!is_null($data)) {
      $this->data = $data;
    }
  }

  /**
   * @return array<int,static>
   * @param PDOStatement<int,array<string,scalar>> $statement
   * */
  public static function fromRecords(PDOStatement $statement): array
  {
    $items = [];
    $records = $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    foreach ($records as $record) {
      $items[] = new static($record);
    }
    return $items;
  }

  /** @return static */
  public static function findById(int $id): self
  {
    $sql = sprintf(
      'SELECT
      *
      FROM
      `%s`
      WHERE
      `id` = :id
      LIMIT 1',
      static::getTableName()
    );
    $statement = Database::getConnection()->prepare($sql);
    $statement->execute([
      'id' => $id
    ]);
    $records = self::fromRecords($statement);
    if (empty($records)) {
      $className = explode('\\', get_called_class());
      $classShortName = array_pop($className);
      throw new Exception(sprintf(
        'No %s found with id = %d',
        $classShortName,
        $id
      ));
    }
    return $records[0];
  }

  /** @return array<int,static> */
  public static function findAll(): array
  {
    $sql = sprintf(
      'SELECT
      *
      FROM
      `%s`',
      static::getTableName()
    );
    $statement = Database::getConnection()->prepare($sql);
    $statement->execute();
    return self::fromRecords($statement);
  }

  /** @param (float|int|string|null) $value */
  protected function set(string $key, $value): void
  {
    $this->data[$key] = $value;
    $this->changed[$key] = true;
  }

  protected function setInteger(string $key, int $value): void
  {
    $this->set($key, $value);
  }

  protected function setString(string $key, string $value): void
  {
    $this->set($key, $value);
  }

  protected function setNull(string $key): void
  {
    $this->set($key, NULL);
  }

  protected function setBool(string $key, bool $value): void
  {
    $this->set($key, $value ? 1 : 0);
  }

  /** @return (float|int|string|null) */
  protected function get(string $key)
  {
    if (!isset($this->data[$key])) {
      if (array_key_exists($this->normalizeKey($key), $this->getDefaults())) {
        $this->data[$key] = $this->getDefaults()[$this->normalizeKey($key)];
      } else {
        throw new Exception(sprintf(
          '%s not set on %s',
          $key,
          get_called_class()
        ));
      }
    }
    return $this->data[$key];
  }

  protected function getInteger(string $key): int
  {
    if (is_null($this->get($key))) {
      throw new Exception(sprintf('%s is NULL', $key));
    }
    return intval($this->get($key));
  }

  protected function getString(string $key): string
  {
    if (is_null($this->get($key))) {
      throw new Exception(sprintf('%s is NULL', $key));
    }
    return strval($this->get($key));
  }

  protected function getDateTime(string $key): DateTime
  {
    if (is_null($this->get($key))) {
      throw new Exception(sprintf('%s is NULL', $key));
    }
    return new DateTime($this->getString($key));
  }

  protected function getBool(string $key): bool
  {
    if (is_null($this->get($key))) {
      throw new Exception(sprintf('%s is NULL', $key));
    }
    return boolval($this->get($key));
  }

  public function getId(): int
  {
    return $this->getInteger('id');
  }

  public function save(): void
  {
    try {
      $this->getId();
      $this->update();
    } catch (Throwable $e) {
      $this->insert();
    }
    $this->data['updated_at'] = (new DateTime())->format('Y-m-d G:i:s');
    $this->changed = [];
  }

  private function insert(): void
  {
    $values = [];
    foreach (array_keys($this->changed) as $key) {
      $values[$key] = $this->data[$key];
    }
    $sql = sprintf(
      'INSERT INTO
      `%s`
      (`%s`)
      VALUES
      (:%s)',
      $this->getTableName(),
      implode('`, `', array_keys($this->changed)),
      implode(', :', array_keys($this->changed))
    );
    $statement = Database::getConnection()->prepare($sql);
    $statement->execute($values);
    $this->data['id'] = intval(Database::getConnection()->lastInsertId());
  }

  private function update(): void
  {
    $values = [
      'id' => $this->getId()
    ];
    $setStatements = [];
    foreach (array_keys($this->changed) as $key) {
      $values[$key] = $this->data[$key];
      $setStatements[] = sprintf(
        '`%s` = :%s',
        $key,
        $key
      );
    }
    $sql = sprintf(
      'UPDATE
      `%s`
      SET %s
      WHERE `id` = :id',
      $this->getTableName(),
      implode(', ', $setStatements)
    );
    $statement = Database::getConnection()->prepare($sql);
    $statement->execute($values);
  }

  public function delete(): void
  {
    $sql = sprintf(
      'DELETE FROM
      `%s`
      WHERE
      `id` = :id',
      $this->getTableName()
    );
    $statement = Database::getConnection()->prepare($sql);
    $statement->execute([
      'id' => $this->getId()
    ]);
  }

  private function normalizeKey(string $key): string
  {
    return str_replace(' ', '', ucwords(preg_replace('~[-_]~', ' ', $key) ?? ''));
  }

  public function jsonSerialize(): stdClass
  {
    $return = new stdClass();
    $fields = !empty($this->getDefaults()) ? array_keys($this->getDefaults()) : array_keys($this->data);
    foreach ($fields as $key) {
      if (in_array($key, array_keys($this->aliases))) {
        $name = $this->aliases[$key];
      } else {
        $name = $this->normalizeKey($key);
      }
      $methodName = sprintf('get%s', $name);
      $return->$name = $this->$methodName();
    }
    foreach ($this->alsoSerialize as $name) {
      $methodName = sprintf('get%s', $name);
      $return->$name = $this->$methodName();
    }
    return $return;
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
