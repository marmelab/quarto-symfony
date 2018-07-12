<?php

namespace App\Controller;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Api\GameManager;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\Api\CookieManager;

class GameApiController extends Controller {

  public const GRID_SIZE = 4;
  private $gameRepository;
  private $gameManager;
  private $serializer;

  public function __construct(GameRepository $gameRepository) {
    $this->gameRepository = $gameRepository;
    $this->gameManager = new GameManager($gameRepository);

    $encoders = array(new XmlEncoder(), new JsonEncoder());
    $normalizers = array(new ObjectNormalizer());

    $this->serializer = new Serializer($normalizers, $encoders);
  }
  
  public function new() {
    $cookieManager = new CookieManager($this->gameRepository);
    $game = $this->gameManager->newGame(self::GRID_SIZE);
    $jsonContent = $this->serializer->serialize($game, 'json');
    $response = new JsonResponse($jsonContent, 200, [], true);
    $cookieManager->setPlayerId($response, $game, 1);
    return $response;    
  }

  public function current(Request $request, int $idGame) {
    $cookieManager = new CookieManager($this->gameRepository);
    $game = $this->gameRepository->findGameById($idGame);
    if ($game != NULL) {
      $playerInfo = $cookieManager->tryCreatePlayer2($request, $game);
      $jsonContent = $this->serializer->serialize($game, 'json');
      $response = new JsonResponse($jsonContent, 200, [], true);

      if ($playerInfo["playerCreated"]) {
        $cookieManager->setPlayerId($response, $game, $playerInfo["playerId"]);
      }
      return $response;

    }
    else {
      return new JsonResponse("{}", 404, [], true);
    }
  }

  public function select(int $idGame, int $piece) {
    $game = $this->gameRepository->findGameById($idGame);
    if ($game != NULL && 
    $piece > 0 &&
    $piece <= self::GRID_SIZE * self::GRID_SIZE &&
    $this->gameManager->playPieceSelection($game, $piece)
    ) {
      $jsonContent = $this->serializer->serialize($game, 'json');
      return new JsonResponse($jsonContent, 200, [], true); 
    }
    else {
      return new JsonResponse("{}", 404, [], true);
    } 
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
      $jsonContent = $this->serializer->serialize($game, 'json');
      return new JsonResponse($jsonContent, 200, [], true);
    }
    else {
      return new JsonResponse("{}", 404, [], true);
    }
  }
}
