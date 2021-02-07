<?php declare(strict_types=1);

use GifGrabber\Admin;
use GifGrabber\Gif;
use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class GifReadRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $gif = Gif::findById($params->getInteger('id'));
    if (!$gif->getApproved()) {
      Admin::guard();
    }

    $response = new Response();
    $response->setDataObject((object)[
      'gif' => $gif,
    ]);
    return $response;
  }
}

Router::addRoute('GET', '/gif/:id', new GifReadRouteHandler());
