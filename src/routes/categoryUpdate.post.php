<?php declare(strict_types=1);

use GifGrabber\Category;
use GifGrabber\Request;
use GifGrabber\Response;
use GifGrabber\RouteHandler;
use GifGrabber\RouteParameters;
use GifGrabber\Router;

class CategoryUpdateRouteHandler extends RouteHandler
{
  public function handle(RouteParameters $params): Response
  {
    $category = Category::findById($params->getInteger('id'));
    $category->jsonSet(Request::getJsonBody());
    $category->save();

    $response = new Response();
    $response->setDataObject((object)[
      'category' => $category,
    ]);
    return $response;
  }
}

Router::addRoute('POST', '/category/:id', new CategoryUpdateRouteHandler());
