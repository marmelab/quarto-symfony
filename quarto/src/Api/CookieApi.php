<?php

namespace App\Api;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Game;

class CookieApi {
  public const COOKIE_NAME = 'current-quarto-';
  
  public static function whichPlayerAmI($request, $idGame) {
    return $request->cookies->get(self::COOKIE_NAME.$idGame);
  }

  public static function setPlayerId($response, $game, $idPlayer) {
    $idGame = $game->getIdGame();
    $response->headers->setCookie(new Cookie(self::COOKIE_NAME.$idGame, $idPlayer));
  }
}