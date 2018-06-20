<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Api\GameApi;
use App\Entity\Game;
use App\Repository\GameRepository;

class GameController extends Controller {

  public const GRID_SIZE = 4;
  private $api;
  private $twig;

  public function __construct(\Twig_Environment $twig, GameApi $gameApi, GameRepository $gameRepository) {
    $this->twig = $twig;
    $this->api = $gameApi;
    $this->gameRepository = $gameRepository;
  }
  
  public function new() {
    $game = $this->api->new(self::GRID_SIZE);

    $this->gameRepository->save($game);

    return $this->redirectToRoute('game', array('id_game' => $game->getIdGame()));    
  }

  public function current($id_game) {
    $game = $this->gameRepository->findGameById($id_game);
      
    return new Response($this->twig->render('game.html.twig', [
      'game' => $game,
      'pieces' => $this->api->getAllPieces($game)
    ]));
  }

  public function select($id_game, $piece) {
    $game = $this->gameRepository->findGameById($id_game);
    $this->api->selectNextPiece($game, $piece);
    $this->api->changeTurn($game);
    $this->gameRepository->save($game);
    return $this->redirectToRoute('game', array('id_game' => $game->getIdGame()));    
  }

  public function place($id_game, $x, $y) {
    $game = $this->gameRepository->findGameById($id_game);
    $this->api->placePiece($game, $x, $y);
    $this->gameRepository->save($game);
    return $this->redirectToRoute('game', array('id_game' => $game->getIdGame()));    
  }
}
