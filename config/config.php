<?php
namespace GifGrabber;

class Config {
  public static function getDatabaseHost(): string
  {
    return 'localhost';
  }
  
  public static function getDatabaseName(): string
  {
    return 'gifgrabber';
  }
  
  public static function getDatabaseUserName(): string
  {
    return 'gifgrabber';
  }
  
  public static function getDatabasePassword(): string
  {
    return '***REMOVED***';
  }
}
