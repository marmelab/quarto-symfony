<?php

namespace App\Controller\GameApi;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Api\GameManager;
use App\Api\TokenManager;
use App\Entity\Game;
use App\Repository\GameRepository;

class GameApiController extends Controller {

  public const GRID_SIZE = 4;
  private $gameRepository;
  private $gameManager;
  private $serializer;
  private $headers = array('access-control-allow-origin' => '*');

  public function __construct(GameRepository $gameRepository) {
    $this->gameRepository = $gameRepository;
    $this->gameManager = new GameManager($gameRepository);

    $encoders = array(new XmlEncoder(), new JsonEncoder());
    $normalizers = array(new ObjectNormalizer());

    $this->serializer = new Serializer($normalizers, $encoders);
  }
  
  public function new() {
    $game = $this->gameManager->newGame(self::GRID_SIZE);
    $jsonContent = $this->serializer->serialize($game, 'json');
    $response = new JsonResponse($jsonContent, 200, $this->headers, true);
    return $response;    
  }

  public function newSolo() {
    $game = $this->gameManager->newGameSolo(self::GRID_SIZE);
    $jsonContent = $this->serializer->serialize($game, 'json');
    $response = new JsonResponse($jsonContent, 200, $this->headers, true);
    return $response;    
  }

  public function current(Request $request, int $idGame) {
    $register = $request->query->get('register');
    $registerContent = json_decode($register, true);
    $token = $request->query->get('token');
    $game = $this->gameRepository->findGameById($idGame);
    if ($game != NULL) {
      if ($registerContent==1) {
        $game->setTokenPlayerTwo(TokenManager::generate())->setNumberOfPlayers(2);
        $token = $game->getTokenPlayerTwo();
        $this->gameRepository->save($game);
      }
      if ($registerContent == NULL) $registerContent = 0;
      if ($token == NULL) $token = '';
      $jsonContent = $this->serializer->serialize($game->winningInformation($token)->securiseGameBeforeReturn($token, $registerContent), 'json');
      $response = new JsonResponse($jsonContent, 200, $this->headers, true);

      return $response;
    }
    return new JsonResponse("{}", 404, [], true);
  }

  public function openedList(Request $request) {
    $tokenList = $request->query->get('tokenList');
    $tokenContent = json_decode($tokenList, true);
    if (!$tokenContent) $tokenContent = [];
    $games = $this->gameRepository->getOpenedGamesList($tokenContent);
    if ($games != NULL) {
      
      $jsonContent = $this->serializer->serialize($games, 'json');
      $response = new JsonResponse($jsonContent, 200, $this->headers, true);
      return $response;

    }
    return new JsonResponse("{}", 200, $this->headers, true);
  }

  public function currentList(Request $request) {
    $tokenList = $request->query->get('tokenList');
    $tokenContent = json_decode($tokenList, true);
    if (!$tokenContent) $tokenContent = [];
    $games = $this->gameRepository->getCurrentGamesList($tokenContent);
    if ($games != NULL) {
      
      $jsonContent = $this->serializer->serialize($games, 'json');
      $response = new JsonResponse($jsonContent, 200, $this->headers, true);
      return $response;

    }
    return new JsonResponse("{}", 200, $this->headers, true);
  }

  public function onlywatchList(Request $request) {
    $tokenList = $request->query->get('tokenList');
    $tokenContent = json_decode($tokenList, true);
    if (!$tokenContent) $tokenContent = [];
    $games = $this->gameRepository->getOnlySpectateGamesList($tokenContent);
    if ($games != NULL) {
      
      $jsonContent = $this->serializer->serialize($games, 'json');
      $response = new JsonResponse($jsonContent, 200, $this->headers, true);
      return $response;

    }
    return new JsonResponse("{}", 200, $this->headers, true);
  }

  public function select(Request $request, int $idGame, int $piece) {
    $token = $request->query->get('token');
    $game = $this->gameRepository->findGameById($idGame);
    if ($game != NULL && 
    $piece > 0 &&
    $piece <= self::GRID_SIZE * self::GRID_SIZE &&
    $this->gameManager->playPieceSelection($game, $piece)
    ) {
      if ($token == NULL) $token = '';
      $jsonContent = $this->serializer->serialize($game->securiseGameBeforeReturn($token), 'json');
      return new JsonResponse($jsonContent, 200, $this->headers, true); 
    }
    return new JsonResponse("{}", 404, [], true);
  }

  public function place(Request $request, int $idGame, int $x, int $y) {
    $token = $request->query->get('token');
    $game = $this->gameRepository->findGameById($idGame);
    if ($game != NULL && 
    $x >= 0 &&
    $x < self::GRID_SIZE && 
    $y >= 0 &&
    $y < self::GRID_SIZE &&
    $this->gameManager->playPiecePLacement($game, $x, $y)
    ) {
      if ($token == NULL) $token = '';
      $jsonContent = $this->serializer->serialize($game->winningInformation($token)->securiseGameBeforeReturn($token), 'json');
      return new JsonResponse($jsonContent, 200, $this->headers, true);
    }
    return new JsonResponse("{}", 404, [], true);
  }

  public function submitToAI(Request $request, int $idGame) {
    $token = $request->query->get('token');
    $game = $this->gameRepository->findGameById($idGame);
    if ($game != NULL && 
    $this->gameManager->submitToAI($game)
    ) {
      if ($token == NULL) $token = '';
      $jsonContent = $this->serializer->serialize($game->winningInformation($token)->securiseGameBeforeReturn($token), 'json');
      return new JsonResponse($jsonContent, 200, [], true);
    }
    else {
      return new JsonResponse("{}", 404, [], true);
    }
  }

  public function submitToAI(Request $request, int $idGame) {
    $token = $request->query->get('token');
    $game = $this->gameRepository->findGameById($idGame);
    if ($game != NULL && 
    $this->gameManager->submitToAI($game)
    ) {
      if ($token == NULL) $token = '';
      $jsonContent = $this->serializer->serialize($game->winningInformation($token)->securiseGameBeforeReturn($token), 'json');
      return new JsonResponse($jsonContent, 200, $this->headers, true);
    }
    else {
      return new JsonResponse("{}", 404, [], true);
    }
  }
}
