<?php
namespace Tests\App\Api;

use PHPUnit\Framework\TestCase;
use App\Entity\Game;
use App\Api\Piece;

class WinningTest extends TestCase
{
    public function testGetPiecesRaw()
    {
        $grid = array(
            array(7, 2, 10, 4),
            array('.', '.', '.', '.'),
            array('.', '.', '.', '.'),
            array('.', '.', '.', '.')
        );
        $referenceGame = new Game(
            0,
            $grid,
            true,
            0,
            1,
            false,
            [],
            false
        );
        $this->assertEquals($referenceGame->getPiecesRaw(3, 0), [7,2,10,4]);
    }

    public function testGetPiecesRawEmpty()
    {
        $grid = array(
            array(7, 2, 10, 4),
            array('.', '.', '.', '.'),
            array(3, '.', '.', '.'),
            array('.', '.', '.', '.')
        );
        $referenceGame = new Game(
            0,
            $grid,
            true,
            0,
            1,
            false,
            [],
            false
        );
        $this->assertEquals($referenceGame->getPiecesRaw(1, 1), ['.','.','.','.']);
    }

    public function testGetPiecesColumn()
    {
        $grid = array(
            array(7, 2, 10, 4),
            array('.', '.', '.', '.'),
            array(3, '.', '.', '.'),
            array('.', '.', '.', '.')
        );
        $referenceGame = new Game(
            0,
            $grid,
            true,
            0,
            1,
            false,
            [],
            false
        );
        $this->assertEquals($referenceGame->getPiecesColumn(0, 3), [7,'.',3,'.']);
    }

    public function testGetPiecesSlashDiag()
    {
        $grid = array(
            array(7, 2, 10, 4),
            array('.', '.', '.', '.'),
            array('.', '.', 11, '.'),
            array('.', '.', '.', '.')
        );
        $referenceGame = new Game(
            0,
            $grid,
            true,
            0,
            1,
            false,
            [],
            false
        );
        $this->assertEquals($referenceGame->getPiecesSlashDiag(3, 3), [7,'.',11,'.']);
    }

    public function testGetPiecesBackSlashDiag()
    {
        $grid = array(
            array(7, 2, 10, 4),
            array('.', '.', '.', '.'),
            array('.', 12, '.', '.'),
            array(1, '.', '.', '.')
        );
        $referenceGame = new Game(
            0,
            $grid,
            true,
            0,
            1,
            false,
            [],
            false
        );
        $this->assertEquals($referenceGame->getPiecesBackSlashDiag(3, 0), [4, '.', 12,1]);
    }

    public function test1And2And3And4AlignedWins()
    {
        $this->assertEquals(Piece::isWinningLine([1,4,3,2]), true);
    }

    public function test4And8And12And16AlignedWins()
    {
        $this->assertEquals(Piece::isWinningLine([4,8,12,16]), true);
    }

    public function test1And2And3And13AlignedDontWins()
    {
        $this->assertEquals(Piece::isWinningLine([1,13,3,2]), false);
    }

    public function test4And8And12And13AlignedDontWins()
    {
        $this->assertEquals(Piece::isWinningLine([4,8,12,13]), false);
    }

    public function test1And2And3And4AlignedWinsOnThisGrid()
    {
        $grid = array(
            array(1, 2, 3, '.'),
            array('.', '.', 8, '.'),
            array(12, '.', 7, '.'),
            array('.', 16, '.', 11)
        );
        $referenceGame = new Game(
            0,
            $grid,
            true,
            4,
            1,
            false,
            [],
            false
        );

        $this->assertEquals($referenceGame->getWinningPosition(3, 0, 4), [1,2,3,4]);
    }

    public function test1And2And3And13AlignedDontWinsOnThisGrid()
    {
        $grid = array(
            array(1, 2, 3, '.'),
            array('.', '.', 8, '.'),
            array(12, '.', 7, '.'),
            array('.', 16, '.', 11)
        );
        $referenceGame = new Game(
            0,
            $grid,
            true,
            13,
            1,
            false,
            [],
            false
        );
        $this->assertEquals($referenceGame->getWinningPosition(3, 0, 13), []);
    }
}
