<?php

namespace App\Models;

use App\Helpers\URL;
use App\Helpers\User;
use Leaf\DevTools;

/**
 * Base Model
 * ---
 * The base model provides a space to set atrributes
 * that are common to all models
 */
class Rating extends \Leaf\Model {
  // set public LIKE and DISLIKE constants
  const LIKE = 1;
  const DISLIKE = 0;

  /**
   * Retrieves a list of ratings for a given url
   * @param string $url
   * @param string $user_id (optional)
   * @return array<{}>
   */
  static function get_rating_list($url, $user_id = null) {
    // sanitize url
    $url = URL::sanitize($url);

    $ratings =  db()->select('ratings')->where('url', $url);
    if ($user_id) $ratings->where('user', $user_id);
    return $ratings->get();
  }

  /**
   * Rates a url
   * @param string $url
   * @param int $rating
   * @return Array
   */
  static function rate($url, $rating) {
    // sanitize url    
    $url = URL::sanitize($url);

    // get user
    $user = User::get_user();

    // check if user has already rated
    $user_ratings = self::get_rating_list($url, $user);
    $user_rating = $user_ratings[0] ?? null;
    if ($user_rating) {
      // check if rating is the same
      if ($user_rating['rating'] == $rating) {
        return [
          'status' => 'no_change',
          'message' => 'Rating unchanged'
        ];
      }

      // update rating
      db()->update('ratings')->params(['rating' => $rating])->where('url', $url)->where('user', $user)->execute();
      return [
        'status' => 'updated',
        'message' => 'Rating updated'
      ];
    }

    // check if rating is valid
    if (in_array($rating, [self::LIKE, self::DISLIKE]) === false) {
      return [
        'message' => 'Invalid rating',
        'status' => 'error'
      ];
    }

    // insert
    db()->insert('ratings')->params([
      'url' => $url,
      'rating' => $rating,
      'user' => $user
    ])->execute();

    // return success message
    return [
      'message' => 'Rating successful',
      'status' => 'success'
    ];
  }

  /**
   * Counts the number of likes and dislikes for a given url
   * @param string $url
   * @return Array
   */
  static function get_rating($url) {
    // sanitize urls
    $url =  URL::sanitize($url);

    // get ratings
    $ratings = db()->query("SELECT rating, COUNT(rating) as count FROM ratings WHERE url = ? GROUP BY rating")->bind($url)->all();

    $result = [
      'likes' => 0,
      'dislikes' => 0,
      'url' => $url,
    ];

    foreach ($ratings as $obj) {
      $rating = (int)$obj['rating'];
      $count = $obj['count'];

      $key = null;
      if ($rating == self::LIKE) $key = 'likes';
      if ($rating == self::DISLIKE) $key = 'dislikes';
      if ($key === null) continue;
      $result[$key] = $count;
    }

    return $result;
  }

  /**
   * 
   */
  static function get_ratings($url) {
    // sanitize url
    $url = URL::sanitize($url);

    $parents_urls = URL::get_parents($url);

    $ratings = [];
    foreach ($parents_urls as $parent_url) {
      $rating = self::get_rating($parent_url);
      $ratings[$parent_url] = $rating;
    }

    return $ratings;
  }
}
