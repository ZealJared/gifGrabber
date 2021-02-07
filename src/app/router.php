<?php declare(strict_types=1);
namespace GifGrabber;

class Router
{
  /** @var array<int,Route> */
  private static $routes;

  public static function addRoute(string $method, string $path, RouteHandler $handler): void
  {
    self::$routes[] = new Route($method, $path, $handler);
  }

  public static function route(): void
  {
    foreach (self::$routes as $route) {
      if ($route->isMatch()) {
        $route->getResponse()->render();
      }
    }
  }
}
