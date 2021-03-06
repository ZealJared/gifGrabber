<?php declare(strict_types=1);
namespace GifGrabber;

use Exception;

class Admin
{
  public static function guard(): void
  {
    if (!Request::isAdmin()) {
      throw new Exception('User must be admin to perform this action.');
    }
  }
}
