<?php

namespace App\Helpers;

class URL {
  /**
   * Strips all unnecessary data from a URL
   */
  static function sanitize($url) {

    // get url parts
    $url_parts = parse_url($url);

    $path = $url_parts['path'] ?? '';
    $host = $url_parts['host'] ?? '';

    // get only the parts we need
    $url = strtolower(join('', [$host, $path]));

    // remove trailing slash
    $url = rtrim($url, '/');

    // remove www
    $url = str_replace('www.', '', $url);

    return $url;
  }

  static function get_parents($url) {
    $url = self::sanitize($url);
    $url_parts = explode('/', $url);
    $parents = [];

    foreach ($url_parts as $key => $part) {
      $parents[] = join('/', array_slice($url_parts, 0, $key + 1));
    }

    // reverse
    $parents = array_reverse($parents);

    return $parents;
  }
}
