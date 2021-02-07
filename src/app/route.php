<?php declare(strict_types=1);

namespace GifGrabber;

use Exception;

class Route
{
  private string $method;

  private string $path;

  private RouteHandler $handler;

  private RouteParameters|

null $params = null;

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
    /** @var array<string,string> */
    $matches = [];
    $matchResult = preg_match($pattern, Request::getPath(), $matches);
    if (empty($matchResult)) {
      return false;
    }
    Utility::assertNamedStringCollection($matches);
    $this->params = new RouteParameters($matches);
    return true;
  }

  public function getResponse(): Response
  {
    assert(!is_null($this->params));
    return $this->handler->handle($this->params);
  }
}
