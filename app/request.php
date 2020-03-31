<?php
namespace GifGrabber;

use Exception;

class Request {
  public static function getMethod(): string
  {
    return $_SERVER['REQUEST_METHOD'];
  }

  public static function getPath(): string
  {
    $path = $_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '/';
    $trimmedPath = preg_replace('~/$~', '', $path);
    return strval($trimmedPath) ?: '/';
  }

  public static function getJsonBody(): object
  {
    $body = file_get_contents('php://input') ?: '';
    $jsonBody = json_decode($body);
    if(!is_object($jsonBody))
    {
      throw new Exception('Post body must be a JSON object.');
    }
    return $jsonBody;
  }

  public static function getOrigin(): string
  {
    return strval($_SERVER['HTTP_ORIGIN'] ?? '');
  }

  public static function getAuth(): Auth
  {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    /** @var string */
    $token = preg_replace('~^Basic ~', '', $authHeader);
    $payload = base64_decode($token) ?: ':';
    [$userName, $password] = explode(':', $payload);
    return new Auth($userName, $password);
  }
}

class Auth {
  /** @var string */
  private $userName = '';
  /** @var string */
  private $password = '';

  public function __construct(string $userName, string $password)
  {
    $this->userName = $userName;
    $this->password = $password;
  }

  public function getUserName(): string
  {
    return $this->userName;
  }

  public function getPassword(): string
  {
    return $this->password;
  }
}
