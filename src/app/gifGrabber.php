<?php declare(strict_types=1);
namespace GifGrabber;

class GifGrabber
{
  /** @var array<int,Strategy> */
  private static $strategies = [];

  public static function addStrategy(Strategy $strategy): void
  {
    self::$strategies[] = $strategy;
  }

  public static function grab(Gif $gif): void
  {
    foreach (self::$strategies as $strategy) {
      if ($strategy->execute($gif)) {
        return;
      }
    }
    // try generic strategy here.
    (new GenericStrategy())->execute($gif);
  }
}
