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

  public function __construct(\Twig_Environment $twig, GameRepository $gameRepository) {
    $this->twig = $twig;
    $this->gameRepository = $gameRepository;
  }
  
  public function new() {
    $gameManager = new GameManager($this->gameRepository);
    $cookieManager = new CookieManager($gameManager);
    $gameManager->newGame(self::GRID_SIZE);
    $gameManager->saveGame();
    $response = $this->redirectToRoute('game', array('idGame' => $gameManager->getGame()->getIdGame())); 
    $cookieManager->setPlayerId($response, 1);
    return $response;    
  }

  public function current(Request $request, $idGame) {
    $gameManager = new GameManager($this->gameRepository);
    $cookieManager = new CookieManager($gameManager);
    $gameManager->setGame($idGame);
    $playerInfo = $cookieManager->tryCreatePlayer2($request);

    $response = new Response($this->twig->render('game.html.twig', [
      'game' => $gameManager->getGame(),
      'pieces' => $gameManager->getAllPieces(),
      'playerId' => $playerInfo["playerId"]
    ]));

    if ($playerInfo["playerCreated"]) {
      $cookieManager->setPlayerId($response, $playerInfo["playerId"]);
    }
      
    return $response;
  }

  public function select($idGame, $piece) {
    $gameManager = new GameManager($this->gameRepository);
    $gameManager->setGame($idGame);
    $gameManager->selectNextPiece($piece);
    $gameManager->changeTurn();
    $gameManager->saveGame();
    return $this->redirectToRoute('game', array('idGame' => $gameManager->getGame()->getIdGame()));    
  }

  public function place($idGame, $x, $y) {
    $gameManager = new GameManager($this->gameRepository);
    $gameManager->setGame($idGame);
    $gameManager->placePiece($x, $y);
    $gameManager->saveGame();
    return $this->redirectToRoute('game', array('idGame' => $gameManager->getGame()->getIdGame()));
  }
}
