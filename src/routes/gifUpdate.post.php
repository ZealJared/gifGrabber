<?php declare(strict_types=1);

use GifGrabber\Gif;
use GifGrabber\Request;
use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class GifUpdateRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $gif = Gif::findById($params->getInteger('id'));
    $gif->jsonSet(Request::getJsonBody());
    $gif->save();

    $response = new Response();
    $response->setDataObject((object)[
      'gif' => $gif,
    ]);
    return $response;
  }
}

Router::addRoute('POST', '/gif/:id', new GifUpdateRouteHandler());
