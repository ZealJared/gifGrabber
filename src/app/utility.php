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
}
