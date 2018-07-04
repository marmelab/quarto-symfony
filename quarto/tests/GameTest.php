<?php

namespace Tests\App\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Game;
use App\Api\Piece;

class GameTest extends TestCase {

  public function testUnSetIsPlayerOneTurn() {
    $game = new Game(1,
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
      ), true, 0, 1, [], false);

    $game->setIsPlayerOneTurn(false);
    $this->assertEquals(false, $game->getIsPlayerOneTurn());
  }

  public function testSetIsPlayerOneTurn() {
    $game = new Game(1,
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
      ), false, 0, 1, [], false);

    $game->setIsPlayerOneTurn(true);
    $this->assertEquals(true, $game->getIsPlayerOneTurn());
  }

  public function testSetSelectedPiece() {
    $game = new Game(1,
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
      ), true, 0, 1, [], false);

    $game->setSelectedPiece(6);
    $this->assertEquals(6, $game->getSelectedPiece());
  }

  public function testUnSetSelectedPiece() {
    $game = new Game(1,
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
      ), true, 9, 1, [], false);

    $game->setSelectedPiece(0);
    $this->assertEquals(0, $game->getSelectedPiece());
  }

  public function testSetGrid() {
    $referenceArray = array(
        array(1, 2),
        array(3, 4));

    $game = new Game(1,
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
      ), true, 0, 1, [], false);

    $game->setGrid($referenceArray);
    $this->assertEquals($referenceArray, $game->getGrid());
  }

  public function testSetIdGame() {
    $game = new Game(1,
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
      ), true, 0, 1, [], false);

    $game->setIdGame(121);
    $this->assertEquals(121, $game->getIdGame());
  }

  public function testSetWinningLine() {
    $referenceArray = [1, 7, 3];

    $game = new Game(1,
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
      ), true, 0, 1, [], false);

    $game->setWinningLine($referenceArray);

    $game->setGrid($referenceArray);
    $this->assertEquals($referenceArray, $game->getWinningLine());
  }


  public function testNew3() {
    $referenceGame = new Game(0,
      array(
        array('.', '.', '.'),
        array('.', '.', '.'),
        array('.', '.', '.')
      ), true, 0, 1, [], false);

    $game = Game::new(3);
    $referenceGame->setTokenPlayerOne($game->getTokenPlayerOne());
    $this->assertEquals($referenceGame, $game);
  }

  public function testNew4() {
    $referenceGame = new Game(0,
    array(
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.'),
      array('.', '.', '.', '.')
    ), true, 0, 1, [], false);

    $game= Game::new(4);
    $referenceGame->setTokenPlayerOne($game->getTokenPlayerOne());
    $this->assertEquals($referenceGame, $game);
  }

  public function testGetAllPieces9() {
    $game = Game::new(3);
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

    $this->assertEquals($referencePieces, $game->getAllPieces());
  }

  public function testGetAllPieces16() {
    $game = Game::new(4);
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

    $this->assertEquals($referencePieces, $game->getAllPieces());
  }

  public function testGetAllPieces16With7thUsed() {
    $game = Game::new(4);
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

    $this->assertEquals($referencePieces, $game->getAllPieces());
  }

  public function testChangeTurn() {
    $game = Game::new(4);
    $referenceGame= Game::new(4);

    $game->changeTurn();
    $this->assertEquals($referenceGame->getIsPlayerOneTurn(), !$game->getIsPlayerOneTurn());
  }

  public function testSelectNextPiece() {
    $game = Game::new(4);

    $game->selectNextPiece(9);
    $this->assertEquals(9, $game->getSelectedPiece());
  }

  public function testPlacePiece() {
    $game = Game::new(4);
    $game->setSelectedPiece(9);

    $game->placePiece(2, 1);
    $grid = $game->getGrid();
    $this->assertEquals(0, $game->getSelectedPiece());
    $this->assertEquals(9, $grid[1][2]);
  }
}
