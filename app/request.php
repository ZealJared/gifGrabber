<?php
namespace GifGrabber;

class Request {
  public static function getMethod(): string
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  public static function getPath(): string
  {
    return $_SERVER['PATH_INFO'];
  }
}
