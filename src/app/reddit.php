<?php declare(strict_types=1);
namespace GifGrabber;

class Reddit
{
  public static function findPlaylistInPageSource(string $source): string
  {
    $matches = [];
    preg_match('~[\'"]([^\'"]+?\.m3u8(?:\?[^\'"]+)?)[\'"]~', $source, $matches);
    return $matches[1] ?? '';
  }

  public static function getTsUrlFromPlaylistData(string $playlistData, string $url): string
  {
    $basePath = dirname(Utility::getUrlPath($url));
    $matches = [];
    preg_match('~\n(.+\.ts)~', $playlistData, $matches);
    $videoUrl = $matches[1] ?? null;
    if (is_null($videoUrl)) {
      return '';
    }
    $tsUrl = sprintf('%s/%s', $basePath, $videoUrl);
    // error_log(sprintf('Found TS video: %s', $tsUrl));
    return $tsUrl;
  }

  public static function getPlaylistUrlFromPlaylistData(string $playlistData, string $url): string
  {
    $basePath = dirname(Utility::getUrlPath($url));
    $matches = [];
    preg_match('~\n([^,#]+\.m3u8)~', $playlistData, $matches);
    $playlistUrl = $matches[1] ?? null;
    if (is_null($playlistUrl)) {
      return '';
    }
    $playlistUrl = sprintf('%s/%s', $basePath, $playlistUrl);
    // error_log(sprintf('Found playlist: %s', $playlistUrl));
    return $playlistUrl;
  }

  public static function getTsUrl(string $source): string
  {
    $playlistUrl = Reddit::findPlaylistInPageSource($source);
    if (!$playlistUrl) {
      return '';
    }
    // error_log(sprintf('Found playlist URL: %s', $playlistUrl));
    $playlistData = file_get_contents($playlistUrl) ?: '';
    $maxDepth = 5; // just in case
    $count = 0;
    $tsUrl = '';
    while (!($tsUrl = self::getTsUrlFromPlaylistData($playlistData, $playlistUrl)) && $count < $maxDepth) {
      $playlistUrl = self::getPlaylistUrlFromPlaylistData($playlistData, $playlistUrl);
      if (!$playlistUrl) {
        break;
      }
      $playlistData = file_get_contents($playlistUrl) ?: '';
      $count += 1;
    }
    return $tsUrl;
  }
}
