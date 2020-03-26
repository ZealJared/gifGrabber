<?php
namespace GifGrabber;

use Exception;

abstract class Route {
  abstract protected function getMethod(): string;
  abstract protected function getPath(): string;
  public function handle(): Response
  {
    if($this->getMethod() !== Request::getMethod())
    {
      throw new Exception('Route does not match requested path.');
    }
    $pattern = sprintf(
      '~%s~',
      preg_replace('~:([^/]+)~', '(?P<$1>[^/]+)', $this->getPath())
    );
    $matches = [];
    $matchResult = preg_match($pattern, Request::getPath(), $matches);
    if(empty($matchResult))
    {
      throw new Exception('Route does not match requested path.');
    }
    return $this->handler($matches);
  }
  /** @param array<string,string> $params */
  abstract public function handler(array $params): Response;
}
