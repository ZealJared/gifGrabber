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

$strategyFiles = glob('../strategies/*') ?: [];

foreach($strategyFiles as $strategyFile)
{
  require_once $strategyFile;
}

try {
  Router::route();
} catch (Throwable $e) {
  $exception = new Response();
  $exception->addError($e->getMessage());
  $trace = $e->getTrace();
  array_unshift($trace, [
    'file' => $e->getFile(),
    'line' => $e->getLine(),
    'function' => '',
    'class' => '',
    'type' => '',
    'args' => [
      ''
    ]
  ]);
  $exception->setDataObject((object)[
    'trace' => $trace
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
