<?php

namespace Tests\App\Api;

use PHPUnit\Framework\TestCase;
use App\Entity\Game;
use App\Api\GameApi;
use App\Api\Piece;

class GameApiTest extends TestCase {

  public function testNew3() {
    $gameApi = new GameApi();

    $referenceGame = new Game(1,
      array(
        array('.', '.', '.'),
        array('.', '.', '.'),
        array('.', '.', '.')
      ), true, 0);

    $game= $gameApi->new(3);
    $this->assertEquals($referenceGame, $game);
  }

  public function testNew4() {
    $gameApi = new GameApi();

    $referenceGame = new Game(1,
    array(
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.')
    ), true, 0);

    $game= $gameApi->new(4);
    $this->assertEquals($referenceGame, $game);
  }

  public function testGetAllPieces9() {
    $gameApi = new GameApi();

    $game= $gameApi->new(3);
    $referencePieces = array(
      1 => new Piece(1, false),
      2 => new Piece(2, false),
      3 => new Piece(3, false),
      4 => new Piece(4, false),
      5 => new Piece(5, false),
      6 => new Piece(6, false),
      7 => new Piece(7, false),
      8 => new Piece(8, false),
      9 => new Piece(9, false)
      );

    $this->assertEquals($referencePieces, $gameApi->getAllPieces($game));
  }

  public function testGetAllPieces16() {
    $gameApi = new GameApi();

    $game= $gameApi->new(4);
    $referencePieces = array(
        1 => new Piece(1, false),
        2 => new Piece(2, false),
        3 => new Piece(3, false),
        4 => new Piece(4, false),
        5 => new Piece(5, false),
        6 => new Piece(6, false),
        7 => new Piece(7, false),
        8 => new Piece(8, false),
        9 => new Piece(9, false),
        10 => new Piece(10, false),
        11 => new Piece(11, false),
        12 => new Piece(12, false),
        13 => new Piece(13, false),
        14 => new Piece(14, false),
        15 => new Piece(15, false),
        16 => new Piece(16, false)
      );

    $this->assertEquals($referencePieces, $gameApi->getAllPieces($game));
  }

  public function testGetAllPieces16With7thUsed() {
    $gameApi = new GameApi();

    $game = $gameApi->new(4);
    $grid = $game->getGrid();
    $grid[1][0] = 7;
    $game->setGrid($grid);
    $referencePieces = array(
      1 => new Piece(1, false),
      2 => new Piece(2, false),
      3 => new Piece(3, false),
      4 => new Piece(4, false),
      5 => new Piece(5, false),
      6 => new Piece(6, false),
      7 => new Piece(7, true),
      8 => new Piece(8, false),
      9 => new Piece(9, false),
      10 => new Piece(10, false),
      11 => new Piece(11, false),
      12 => new Piece(12, false),
      13 => new Piece(13, false),
      14 => new Piece(14, false),
      15 => new Piece(15, false),
      16 => new Piece(16, false)
      );

    $this->assertEquals($referencePieces, $gameApi->getAllPieces($game));
  }

  public function testChangeTurn() {
    $gameApi = new GameApi();

    $game= $gameApi->new(4);

    $nextGame = $gameApi->changeTurn($game);
    $this->assertEquals($nextGame->getIsPlayerOneTurn(), !$game->getIsPlayerOneTurn());
  }

  public function testSelectNextPiece() {
    $gameApi = new GameApi();

    $game= $gameApi->new(4);

    $nextGame = $gameApi->selectNextPiece($game, 9);
    $this->assertEquals(9, $nextGame->getSelectedPiece());
  }
}
