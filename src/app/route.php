<?php

namespace GifGrabber;

use Exception;

class Route
{
  /** @var string */
  private $method;
  /** @var string */
  private $path;
  /** @var RouteHandler */
  private $handler;
  /** @var RouteParameters */
  private $params;

  public function __construct(string $method, string $path, RouteHandler $handler)
  {
    $this->method = strtoupper($method);
    if (!in_array($this->method, ['GET', 'POST'])) {
      throw new Exception(sprintf('Route method "%s" not allowed.', $method));
    }
    $this->path = $path;
    $this->handler = $handler;
  }

  public function isMatch(): bool
  {
    if ($this->method !== Request::getMethod()) {
      return false;
    }
    $pattern = sprintf(
      '~^%s$~',
      preg_replace('~:([^/]+)~', '(?P<$1>[^/]+)', $this->path)
    );
    $matches = [];
    $matchResult = preg_match($pattern, Request::getPath(), $matches);
    if (empty($matchResult)) {
      return false;
    }
    $this->params = new RouteParameters($matches);
    return true;
  }

  public function getResponse(): Response
  {
    return $this->handler->handle($this->params);
  }
}
