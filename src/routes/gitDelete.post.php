<?php declare(strict_types=1);

use GifGrabber\Gif;
use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class GifDeleteRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $gif = Gif::findById($params->getInteger('id'));
    $gif->delete();

    $response = new Response();
    $response->setDataObject((object)[
      'gif' => $gif,
    ]);
    return $response;
  }
}

Router::addRoute('GET', '/gif/:id/delete', new GifDeleteRouteHandler());
