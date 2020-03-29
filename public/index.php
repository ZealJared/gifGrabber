<?php
namespace GifGrabber;

use Throwable;

error_reporting(E_ALL);
ini_set('display_errors', 'on');
date_default_timezone_set('America/Los_Angeles');

require_once __DIR__ . '/../vendor/autoload.php';

$routeFiles = glob('../routes/*') ?: [];

foreach($routeFiles as $routeFile)
{
  require_once $routeFile;
}

try {
  Router::route();
} catch (Throwable $e) {
  $exception = new Response();
  $exception->addError($e->getMessage());
  $exception->setDataObject((object)[
    'trace' => $e->getTrace()
  ]);
  $exception->render();
}

$notFound = new Response();
$notFound->addError(sprintf(
  'Matching route not found for "%s %s".',
  Request::getMethod(),
  Request::getPath()
));
$notFound->render();
