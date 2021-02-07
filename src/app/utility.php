<?php
namespace GifGrabber;

class Utility {
  public static function getUrlFileExtension(string $url): string
  {
    return strtolower(pathinfo(self::getUrlPath($url), PATHINFO_EXTENSION));
  }

  public static function getUrlPath(string $url): string
  {
    $urlString = '';
    $urlParts = parse_url($url);
    $scheme = $urlParts['scheme'] ?? '';
    if($scheme)
    {
      $urlString .= $scheme . '://';
    }
    $user = $urlParts['user'] ?? '';
    $pass = $urlParts['pass'] ?? '';
    if($user && $pass)
    {
      $urlString .= $user . ':' . $pass . '@';
    }
    $host = $urlParts['host'] ?? '';
    $urlString .= $host;
    $port = $urlParts['port'] ?? '';
    if($port)
    {
      $urlString .= ':' . $port;
    }
    $path = $urlParts['path'] ?? '';
    $urlString .= $path;
    return $urlString;
  }

  /** @psalm-assert array<string,string> $array */
  public static function assertNamedStringCollection(array $array): void
  {
    foreach(array_keys($array) as $key)
    {
      assert(is_string($key));
    }
    foreach($array as $value)
    {
      assert(is_string($value));
    }
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
    foreach($array as $key => $value)
    {
      assert(is_string($key));
      self::getFloatIntStringNull($value);
    }
  }
}
