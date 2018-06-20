<?php

namespace Tests\App\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Game;

class GameTest extends TestCase {

  public function testUnSetIsPlayerOneTurn() {
    $game = new Game(1,
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
      ), true, 0);

    $game->setIsPlayerOneTurn(false);
    $this->assertEquals(false, $game->getIsPlayerOneTurn());
  }

  public function testSetIsPlayerOneTurn() {
    $game = new Game(1,
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
      ), false, 0);

    $game->setIsPlayerOneTurn(true);
    $this->assertEquals(true, $game->getIsPlayerOneTurn());
  }

  public function testSetSelectedPiece() {
    $game = new Game(1,
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
      ), true, 0);

    $game->setSelectedPiece(6);
    $this->assertEquals(6, $game->getSelectedPiece());
  }

  public function testUnSetSelectedPiece() {
    $game = new Game(1,
      array(
        array(1, 2, 3),
        array(4, 5, 6),
        array(7, 8, 9)
      ), true, 9);

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
      ), true, 0);

    $game->setGrid($referenceArray);
    $this->assertEquals($referenceArray, $game->getGrid());
  }
}
