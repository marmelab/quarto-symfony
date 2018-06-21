<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Api\GameApi;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\Api\CookieApi;

class GameController extends Controller {

  public const GRID_SIZE = 4;
  private $twig;

  public function __construct(\Twig_Environment $twig, GameRepository $gameRepository) {
    $this->twig = $twig;
    $this->gameRepository = $gameRepository;
  }
  
  public function new() {
    $game = GameApi::new(self::GRID_SIZE);

    $this->gameRepository->save($game);
    $response = $this->redirectToRoute('game', array('id_game' => $game->getIdGame())); 
    CookieApi::setPlayerId($response, $game, 1);
    return $response;    
  }

  public function current(Request $request, $id_game) {
    $game = $this->gameRepository->findGameById($id_game);
    $playerId = CookieApi::whichPlayerAmI($request, $id_game);
    $setCookiePlayer2 = false;
    if ($playerId == NULL && $game->getNumberPlayers() == 1) {
      $playerId = 2;
      $game->setNumberPlayers(2);
      $this->gameRepository->save($game);
      $setCookiePlayer2 = true;
    }
    $response = new Response($this->twig->render('game.html.twig', [
      'game' => $game,
      'pieces' => GameApi::getAllPieces($game),
      'playerId' => $playerId
    ]));

    if ($setCookiePlayer2) {
      CookieApi::setPlayerId($response, $game, $playerId);
    }
      
    return $response;
  }

  public function select($id_game, $piece) {
    $game = $this->gameRepository->findGameById($id_game);
    GameApi::selectNextPiece($game, $piece);
    GameApi::changeTurn($game);
    $this->gameRepository->save($game);
    return $this->redirectToRoute('game', array('id_game' => $game->getIdGame()));    
  }

  public function place($id_game, $x, $y) {
    $game = $this->gameRepository->findGameById($id_game);
    GameApi::placePiece($game, $x, $y);
    $this->gameRepository->save($game);
    return $this->redirectToRoute('game', array('id_game' => $game->getIdGame()));
  }
}
