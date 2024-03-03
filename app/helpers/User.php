<?php

namespace App\Helpers;


class User {
  // uses the ip address to get a user hash
  static function get_user() {
    return md5($_SERVER['REMOTE_ADDR']);
  }
}
