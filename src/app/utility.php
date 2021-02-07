<?php declare(strict_types=1);
namespace GifGrabber;

class Utility
{
  public static function getUrlFileExtension(string $url): string
  {
    return strtolower(pathinfo(self::getUrlPath($url), PATHINFO_EXTENSION));
  }

  public static function getUrlPath(string $url): string
  {
    $urlString = '';
    $urlParts = parse_url($url);
    $scheme = $urlParts['scheme'] ?? '';
    if ($scheme) {
      $urlString .= $scheme . '://';
    }
    $user = $urlParts['user'] ?? '';
    $pass = $urlParts['pass'] ?? '';
    if ($user && $pass) {
      $urlString .= $user . ':' . $pass . '@';
    }
    $host = $urlParts['host'] ?? '';
    $urlString .= $host;
    $port = $urlParts['port'] ?? '';
    if ($port) {
      $urlString .= ':' . $port;
    }
    $path = $urlParts['path'] ?? '';
    $urlString .= $path;
    return $urlString;
  }

  /** @return array<string,string> */
  public static function getNamedStringCollection(array $array): array
  {
    /** @var array<string,string> */
    $return = [];
    /** @var mixed $value */
    foreach ($array as $key => $value) {
      if (is_string($key) && is_string($value)) {
        $return[$key] = $value;
      }
    }
    return $return;
  }

  public static function getFloatIntStringNull(mixed $value): float|int|string|null
  {
    assert(is_float($value) || is_int($value) || is_string($value) || is_null($value));
    return $value;
  }

  /** @psalm-assert array<string,(float|int|string|null)> $array */
  public static function assertArrayOfFloatIntStringNull(array $array): void
  {
    /** @var mixed $value */
    foreach ($array as $key => $value) {
      assert(is_string($key));
      self::getFloatIntStringNull($value);
    }
  }
}
