<?php
namespace GifGrabber;

use DateTime;
use Exception;
use JsonSerializable;
use PDO;
use PDOStatement;
use stdClass;
use Throwable;

abstract class Model implements JsonSerializable {
  /** @var array<string,scalar> */
  private $data = [];
  /** @var array<string,bool> */
  private $changed = [];
  /** @var array<string,string> */
  protected $aliases = [];
  /** @var array<int,string> */
  protected $alsoSerialize = [];
  /** @var array<int,string> */
  protected $doNotSerialize = [];

  abstract public static function getTableName(): string;

  /** @param array<string,scalar> $data */
  final public function __construct(array $data = [])
  {
    $this->data = $data;
  }

  /**
   * @return array<int,static>
   * @param PDOStatement<int,array<string,scalar>> $statement
   * */
  public static function fromRecords(PDOStatement $statement): array
  {
    $items = [];
    $records = $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    foreach($records as $record)
    {
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
    if(empty($records)){
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

  /** @param scalar $value */
  protected function set(string $key, $value): void
  {
    $this->data[$key] = $value;
    $this->changed[$key] = true;
  }

  protected function setInteger(string $key, int $value): void
  {
    $this->set($key, $value);
  }

  /** @return scalar */
  protected function get(string $key)
  {
    if(!isset($this->data[$key]))
    {
      throw new Exception(sprintf(
        '%s not set on %s',
        $key,
        get_called_class()
      ));
    }
    return $this->data[$key];
  }

  protected function getInteger(string $key): int
  {
    return intval($this->get($key));
  }

  protected function getString(string $key): string
  {
    return strval($this->get($key));
  }

  protected function getDateTime(string $key): DateTime
  {
    return new DateTime($this->getString($key));
  }

  protected function getBool(string $key): bool
  {
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
    $this->changed = [];
  }

  private function insert(): void
  {
    $values = [];
    foreach(array_keys($this->changed) as $key)
    {
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
  }

  private function update(): void
  {
    $values = [];
    $setStatements = [];
    foreach(array_keys($this->changed) as $key)
    {
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
      SET %s',
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

  public function jsonSerialize(): stdClass
  {
    $return = new stdClass();
    foreach(array_keys($this->data) as $key)
    {
      if(in_array($key, $this->doNotSerialize)){
        continue;
      }
      if(in_array($key, array_keys($this->aliases)))
      {
        $name = $this->aliases[$key];
      } else {
        $name = str_replace(' ', '', ucwords(preg_replace('~[-_]~', ' ', strtolower($key)) ?? ''));
      }
      $methodName = sprintf('get%s', $name);
      $return->$name = $this->$methodName();
    }
    foreach($this->alsoSerialize as $name)
    {
      $methodName = sprintf('get%s', $name);
      $return->$name = $this->$methodName();
    }
    return $return;
  }
}
