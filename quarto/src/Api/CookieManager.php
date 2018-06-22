<?php

namespace App\Api;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Game;
use App\Repository\GameRepository;

class CookieManager {
  public const COOKIE_NAME = 'current-quarto-';

  private $gameRepository;

  public function __construct(GameRepository $gameRepository) {
    $this->gameRepository = $gameRepository;
  }
  
  public function whichPlayerAmI(Request $request, Game $game) {
    return $request->cookies->get(self::COOKIE_NAME.$game->getIdGame());
  }

  public function setPlayerId(Response $response, Game $game, int $idPlayer) {
    $response->headers->setCookie(new Cookie(self::COOKIE_NAME.$game->getIdGame(), $idPlayer));
  }

  public function tryCreatePlayer2(Request $request, Game $game): Array {
    $playerId = $this->whichPlayerAmI($request, $game);
    if ($playerId == NULL && $game->getNumberOfPlayers() == 1) {
      $playerId = 2;
      $game->setNumberOfPlayers(2);
      $this->gameRepository->save($game);
      return ["playerCreated" => true, "playerId" => $playerId];
    }
    return ["playerCreated" => false, "playerId" => $playerId];
  }
}