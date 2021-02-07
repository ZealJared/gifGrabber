<?php declare(strict_types=1);
namespace GifGrabber;

use Exception;

class Request
{
  public static function getMethod(): string
  {
    $method = $_SERVER['REQUEST_METHOD'];
    assert(is_string($method));
    return $method;
  }

  public static function getPath(): string
  {
    $path = $_SERVER['PATH_INFO'] ?? $_SERVER['REQUEST_URI'] ?? '/';
    assert(is_string($path));
    $trimmedPath = preg_replace('~/$~', '', $path);
    return strval($trimmedPath) ?: '/';
  }

  public static function getJsonBody(): object
  {
    $body = file_get_contents('php://input') ?: '';
    $jsonBody = json_decode($body);
    if (!is_object($jsonBody)) {
      throw new Exception('Post body must be a JSON object.');
    }
    return $jsonBody;
  }

  public static function getOrigin(): string
  {
    $value = $_SERVER['HTTP_ORIGIN'] ?? '';
    assert(is_string($value));
    return $value;
  }

  public static function getAuth(): Auth
  {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    assert(is_string($authHeader));
    $token = preg_replace('~^Basic ~', '', $authHeader);
    $payload = base64_decode($token) ?: ':';
    [$userName, $password] = explode(':', $payload);
    return new Auth($userName, $password);
  }

  public static function isAdmin(): bool
  {
    return (
      Request::getAuth()->getUserName() === Config::getAdminUserName()
      && Request::getAuth()->getPassword() === Config::getAdminPassword()
    );
  }
}

class Auth
{
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
