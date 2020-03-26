<?php
namespace GifGrabber;

class Request {
  public static function getMethod(): string
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  public static function getPath(): string
  {
    $path = $_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '/';
    $trimmedPath = preg_replace('~/$~', '', $path);
    return strval($trimmedPath) ?: '/';
  }
}
