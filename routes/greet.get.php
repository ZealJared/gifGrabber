<?php

use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class GreetRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $response = new Response();
    $response->setDataObject((object)[
      'message' => sprintf('Welcome, %s! You %s!', $params->getString('name'), $params->getString('verb'))
    ]);
    return $response;
  }
}

Router::addRoute('GET', '/greet/:name/:verb', new GreetRouteHandler());
