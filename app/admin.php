<?php
namespace GifGrabber;

use Exception;

class Admin {
  public static function guard(): void
  {
    if (Request::getAuth()->getUserName() !== Config::getAdminUserName() || Request::getAuth()->getPassword() !== Config::getAdminPassword()) {
      throw new Exception('User must be admin to perform this action.');
    }
  }
}
