<?php

use GifGrabber\Category;
use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class CategoryReadRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $category = Category::findById($params->getInteger('id'));

    $response = new Response();
    $response->setDataObject((object)[
      'category' => $category
    ]);
    return $response;
  }
}

Router::addRoute('GET', '/category/:id', new CategoryReadRouteHandler());
