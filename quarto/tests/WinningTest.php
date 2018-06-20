<?php
namespace Tests\App\Api;

use PHPUnit\Framework\TestCase;
use App\Entity\Game;
use App\Api\GameApi;
use App\Api\Piece;

class WinningTest extends TestCase {

  public function testGetPiecesRaw() {
    $referenceGame = new Game(0,
      array(
        array(7, 2, 10, 4),
        array('.', '.', '.', '.'),
        array('.', '.', '.', '.'),
        array('.', '.', '.', '.')
      ), true, 0, []);
    $this->assertEquals(GameApi::getPiecesRaw($referenceGame, 3, 0), [7,2,10,4]);
  }

  public function testGetPiecesRawEmpty() {
    $referenceGame = new Game(0,
      array(
        array(7, 2, 10, 4),
        array('.', '.', '.', '.'),
        array(3, '.', '.', '.'),
        array('.', '.', '.', '.')
      ), true, 0, []);
    $this->assertEquals(GameApi::getPiecesRaw($referenceGame, 1, 1), ['.','.','.','.']);
  }

  public function testGetPiecesColumn() {
    $referenceGame = new Game(0,
      array(
        array(7, 2, 10, 4),
        array('.', '.', '.', '.'),
        array(3, '.', '.', '.'),
        array('.', '.', '.', '.')
      ), true, 0, []);
    $this->assertEquals(GameApi::getPiecesColumn($referenceGame, 0, 3), [7,'.',3,'.']);
  }

  public function testGetPiecesSlashDiag() {
    $referenceGame = new Game(0,
      array(
        array(7, 2, 10, 4),
        array('.', '.', '.', '.'),
        array('.', '.', 11, '.'),
        array('.', '.', '.', '.')
      ), true, 0, []);
    $this->assertEquals(GameApi::getPiecesSlashDiag($referenceGame, 3, 3), [7,'.',11,'.']);
  }

  public function testGetPiecesBackSlashDiag() {
    $referenceGame = new Game(0,
      array(
        array(7, 2, 10, 4),
        array('.', '.', '.', '.'),
        array('.', 12, '.', '.'),
        array(1, '.', '.', '.')
      ), true, 0, []);
    $this->assertEquals(GameApi::getPiecesBackSlashDiag($referenceGame, 3, 0), [4, '.', 12,1]);
  }

  public function test1And2And3And4AlignedWins() {
    $this->assertEquals(Piece::isWinningLine([1,4,3,2]), true);
  }

  public function test4And8And12And16AlignedWins() {
    $this->assertEquals(Piece::isWinningLine([4,8,12,16]), true);
  }

  public function test1And2And3And13AlignedDontWins() {
    $this->assertEquals(Piece::isWinningLine([1,13,3,2]), false);
  }

  public function test4And8And12And13AlignedDontWins() {
    $this->assertEquals(Piece::isWinningLine([4,8,12,13]), false);
  }

  public function test1And2And3And4AlignedWinsOnThisGrid() {
    $referenceGame = new Game(0,
        array(
          array(1, 2, 3, '.'),
          array('.', '.', 8, '.'),
          array(12, '.', 7, '.'),
          array('.', 16, '.', 11)
        ), true, 4, []);


    $this->assertEquals(GameApi::getWinningPosition($referenceGame, 3, 0, 4), [1,2,3,4]);
  }

  public function test1And2And3And13AlignedDontWinsOnThisGrid() {
    $referenceGame = new Game(0,
        array(
          array(1, 2, 3, '.'),
          array('.', '.', 8, '.'),
          array(12, '.', 7, '.'),
          array('.', 16, '.', 11)
        ), true, 13, []);


    $this->assertEquals(GameApi::getWinningPosition($referenceGame, 3, 0, 13), []);
  }
}
