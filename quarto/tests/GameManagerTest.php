<?php

namespace Tests\App\Api;

use PHPUnit\Framework\TestCase;
use App\Entity\Game;
use App\Api\GameManager;
use App\Api\Piece;
use App\Repository\GameRepository;

class GameManagerTest extends TestCase {

  public function testNewGame4() {
    $mockGameRepository = $this->createMock(GameRepository::class);
    $gameManager = new GameManager($mockGameRepository);
    $game =  $gameManager->newGame(4);

    $referenceGame = new Game(0,
    array(
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.')
    ), true, 0, 1, []);
    $this->assertEquals($referenceGame, $game);
  }

  public function testPlayPieceSelection() {
    $mockGameRepository = $this->createMock(GameRepository::class);
    $gameManager = new GameManager($mockGameRepository);
    $game = new Game(0,
    array(
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.')
    ), true, 0, 1, []);
    $gameManager->playPieceSelection($game, 7);
    $this->assertEquals(7, $game->getSelectedPiece());
  }

  public function testPlayPiecePLacement() {
    $mockGameRepository = $this->createMock(GameRepository::class);
    $gameManager = new GameManager($mockGameRepository);
    $game = new Game(0,
    array(
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.')
    ), true, 10, 1, []);
    $gameManager->playPiecePLacement($game, 3, 0);
    $this->assertEquals(10, $game->getGrid()[0][3]);
  }
}
