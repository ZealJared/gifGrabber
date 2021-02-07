<?php

use GifGrabber\Gif;
use GifGrabber\Request;
use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class GifCreateRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $gif = new Gif();
    $gif->jsonSet(Request::getJsonBody());
    $gif->save();

    $response = new Response();
    $response->setDataObject((object)[
      'gif' => $gif
    ]);
    return $response;
  }
}

Router::addRoute('POST', '/gif', new GifCreateRouteHandler());
