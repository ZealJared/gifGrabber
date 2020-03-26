<?php
namespace GifGrabber;

error_reporting(E_ALL);
ini_set('display_errors', 'on');

require_once __DIR__ . '/../vendor/autoload.php';

$routeFiles = glob('../routes/*') ?: [];

foreach($routeFiles as $routeFile)
{
  require_once $routeFile;
}

Router::route();

$notFound = new Response();
$notFound->addError(sprintf(
  'Matching route not found for "%s %s".',
  Request::getMethod(),
  Request::getPath()
));
$notFound->render();
