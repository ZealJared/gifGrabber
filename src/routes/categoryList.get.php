<?php

use GifGrabber\Category;
use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class CategoryListRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $category = Category::findAll();

    $response = new Response();
    $response->setDataObject((object)[
      'category' => $category
    ]);
    return $response;
  }
}

Router::addRoute('GET', '/category', new CategoryListRouteHandler());
