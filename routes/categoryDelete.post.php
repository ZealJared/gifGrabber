<?php

use GifGrabber\Category;
use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class CategoryDeleteRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $category = Category::findById($params->getInteger('id'));
    $category->delete();

    $response = new Response();
    $response->setDataObject((object)[
      'category' => $category
    ]);
    return $response;
  }
}

Router::addRoute('GET', '/category/:id/delete', new CategoryDeleteRouteHandler());
