<?php

use GifGrabber\Gif;
use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class GifGetByIdRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $response = new Response();
    $response->setDataObject((object)[
      'gif' => Gif::findById($params->getInteger('id'))
    ]);
    return $response;
  }
}

Router::addRoute('GET', '/gif/:id', new GifGetByIdRouteHandler());
