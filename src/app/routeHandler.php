<?php
namespace GifGrabber;

abstract class RouteHandler {
  abstract public function handle(RouteParameters $params): Response;
}
