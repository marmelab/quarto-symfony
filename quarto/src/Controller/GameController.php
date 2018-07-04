<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Api\GameManager;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\Api\CookieManager;

class GameController extends Controller {

  public const GRID_SIZE = 4;
  private $twig;
  private $gameRepository;
  private $gameManager;

  public function __construct(\Twig_Environment $twig, GameRepository $gameRepository) {
    $this->twig = $twig;
    $this->gameRepository = $gameRepository;
    $this->gameManager = new GameManager($gameRepository);
  }
  
  public function new() {
    $cookieManager = new CookieManager($this->gameRepository);
    $game = $this->gameManager->newGame(self::GRID_SIZE);
    $response = $this->redirectToRoute('game', array('idGame' => $game->getIdGame())); 
    $cookieManager->setPlayerId($response, $game, 1);
    return $response;    
  }

  public function current(Request $request, int $idGame) {
    $cookieManager = new CookieManager($this->gameRepository);
    $game = $this->gameRepository->findGameById($idGame);
    if ($game != NULL) {
      $playerInfo = $cookieManager->tryCreatePlayer2($request, $game);

      $response = new Response($this->twig->render('game.html.twig', [
        'game' => $game,
        'pieces' => $game->getAllPieces(),
        'canPlay' => ($playerInfo["playerId"] == 1 && $game->getIsPlayerOneTurn()) || ($playerInfo["playerId"] == 2 && $game->getIsPlayerOneTurn() == false),
        'playerId' => $playerInfo["playerId"]
      ]));

      if ($playerInfo["playerCreated"]) {
        $cookieManager->setPlayerId($response, $game, $playerInfo["playerId"]);
      }
      return $response;
    }
    return $this->redirectToRoute('all', array('req' => "error"));
  }

  public function select(int $idGame, int $piece) {
    $game = $this->gameRepository->findGameById($idGame);
    if ($game != NULL && 
    $piece > 0 &&
    $piece <= self::GRID_SIZE * self::GRID_SIZE &&
    $this->gameManager->playPieceSelection($game, $piece)
    ) {
      return $this->redirectToRoute('game', array('idGame' => $game->getIdGame()));   
    }
    return $this->redirectToRoute('all', array('req' => "error"));
  }

  public function place(int $idGame, int $x, int $y) {
    $game = $this->gameRepository->findGameById($idGame);
    if ($game != NULL && 
    $x >= 0 &&
    $x < self::GRID_SIZE && 
    $y >= 0 &&
    $y < self::GRID_SIZE &&
    $this->gameManager->playPiecePLacement($game, $x, $y)
    ) {
      return $this->redirectToRoute('game', array('idGame' => $game->getIdGame()));
    }
    return $this->redirectToRoute('all', array('req' => "error"));
  }
}
