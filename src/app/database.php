<?php
namespace GifGrabber;

use PDO;

class Database {
  /** @var PDO|null */
  private static $connection = null;

  public static function getConnection(): PDO
  {
    if(is_null(self::$connection))
    {
      $dsn = sprintf(
        'mysql:host=%s;dbname=%s',
        Config::getDatabaseHost(),
        Config::getDatabaseName()
      );
      self::$connection = new PDO($dsn, Config::getDatabaseUserName(), Config::getDatabasePassword(), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
      ]);
    }
    return self::$connection;
  }
}
