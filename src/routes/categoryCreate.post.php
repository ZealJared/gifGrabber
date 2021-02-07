<?php

use GifGrabber\Category;
use GifGrabber\Request;
use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class CategoryCreateRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $category = new Category();
    $category->jsonSet(Request::getJsonBody());
    $category->save();

    $response = new Response();
    $response->setDataObject((object)[
      'category' => $category
    ]);
    return $response;
  }
}

Router::addRoute('POST', '/category', new CategoryCreateRouteHandler());
