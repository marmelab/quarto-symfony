<?php

namespace App\Api;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Game;
use App\Api\GameManager;

class CookieManager {
  public const COOKIE_NAME = 'current-quarto-';

  private $gameManager;

  public function __construct(GameManager $gameManager) {
    $this->gameManager = $gameManager;
  }
  
  public function whichPlayerAmI(Request $request) {
    return $request->cookies->get(self::COOKIE_NAME.$this->gameManager->getGame()->getIdGame());
  }

  public function setPlayerId(Response $response, int $idPlayer) {
    $response->headers->setCookie(new Cookie(self::COOKIE_NAME.$this->gameManager->getGame()->getIdGame(), $idPlayer));
  }

  public function tryCreatePlayer2(Request $request): Array {
    $playerId = $this->whichPlayerAmI($request);
    if ($playerId == NULL && $this->gameManager->getNumberOfPlayers() == 1) {
      $playerId = 2;
      $this->gameManager->setNumberOfPlayers(2);
      $this->gameManager->saveGame();
      return ["playerCreated" => true, "playerId" => $playerId];
    }
    return ["playerCreated" => false, "playerId" => $playerId];
  }
}