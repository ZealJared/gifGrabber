<?php declare(strict_types=1);
namespace GifGrabber;

use Exception;

class Config
{
  public static function getDatabaseHost(): string
  {
    return 'localhost';
  }

  public static function getDatabaseName(): string
  {
    return 'gifgrabber';
  }

  public static function getDatabaseUserName(): string
  {
    return 'gifgrabber';
  }

  public static function getDatabasePassword(): string
  {
    return 'supercrazysweetpassword';
  }

  public static function getStorageFolderName(): string
  {
    return 'storage';
  }

  public static function getStoragePath(): string
  {
    $path = realpath(sprintf('%s/../../public/%s/', __DIR__, self::getStorageFolderName()));
    if ($path === false) {
      throw new Exception('Storage folder not found.');
    }
    return $path;
  }

  public static function getBaseUrl(): string
  {
    return 'http://localhost';
  }

  public static function getStorageUrl(): string
  {
    return sprintf(
      '%s/%s',
      self::getBaseUrl(),
      self::getStorageFolderName()
    );
  }

  /** @return array<int,string> */
  public static function getAllowedOrigins(): array
  {
    return [
      'http://localhost:8080',
    ];
  }

  public static function getAdminUserName(): string
  {
    return 'admin@gif.grabber';
  }

  public static function getAdminPassword(): string
  {
    return 'feelinglobsterleatherknife';
  }

  public static function getLogFilePath(): string
  {
    return realpath(__DIR__ . '/../../debug.log');
  }
}
