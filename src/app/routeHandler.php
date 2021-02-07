<?php declare(strict_types=1);
namespace GifGrabber;

abstract class RouteHandler
{
  abstract public function handle(RouteParameters $params): Response;
}
