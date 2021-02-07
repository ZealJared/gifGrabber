<?php declare(strict_types=1);

namespace GifGrabber;

use Exception;

class RouteParameters
{
  /** @var array<string,string> */
  private array $params;

  /** @param array<string,string> $params */
  public function __construct(array $params)
  {
    $this->params = $params;
  }

  private function get(string $key): string
  {
    if (!isset($this->params[$key])) {
      throw new Exception(sprintf(
        'No route parameter "%s" provided.',
        $key
      ));
    }
    return $this->params[$key];
  }

  public function getString(string $key): string
  {
    return urldecode($this->get($key));
  }

  public function getInteger(string $key): int
  {
    if (!is_numeric($this->get($key))) {
      throw new Exception(sprintf('Route parameter "%s" is not an integer.', $key));
    }
    return intval($this->get($key));
  }
}
