<?php
namespace App\Api;
class TokenManager {
  public const TOKEN_LENGTH = 10;
  public static function generate() : string {
    return bin2hex(random_bytes(self::TOKEN_LENGTH));
  }
}