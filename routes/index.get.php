<?php

use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class IndexRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $response = new Response();
    $response->setDataObject((object)[
      'message' => 'Welcome!'
    ]);
    return $response;
  }
}

Router::addRoute('GET', '/', new IndexRouteHandler());
