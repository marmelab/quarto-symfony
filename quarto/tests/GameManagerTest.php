<?php

namespace Tests\App\Api;

use PHPUnit\Framework\TestCase;
use App\Entity\Game;
use App\Api\GameManager;
use App\Api\Piece;

class GameManagerTest extends TestCase {

  public function testNewGame4() {
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
    $gameManager = new GameManager($mockGameRepository);
    $game = new Game(0,
    array(
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.')
    ), true, 0, 1, []);
    playPieceSelection($game, 7);
    $this->assertEquals(7, $game->getSelectedPiece());
  }

  public function testPlayPiecePLacement() {
    $gameManager = new GameManager($mockGameRepository);
    $game = new Game(0,
    array(
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.')
    ), true, 10, 1, []);
    playPiecePLacement($game, 3, 0);
    $this->assertEquals(10, $game->getGrid()[0][3]);
  }
}
