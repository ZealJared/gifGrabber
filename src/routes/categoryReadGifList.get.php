<?php

use GifGrabber\Category;
use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class CategoryReadGifListRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $category = Category::findById($params->getInteger('id'));

    $response = new Response();
    $response->setDataObject((object)[
      'gif' => $category->getGifList()
    ]);
    return $response;
  }
}

Router::addRoute('GET', '/category/:id/gif', new CategoryReadGifListRouteHandler());
