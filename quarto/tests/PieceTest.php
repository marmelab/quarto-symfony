<?php

namespace Tests\App\Api;

use PHPUnit\Framework\TestCase;
use App\Api\Piece;

class PieceTest extends TestCase
{
    public function testUnSetUsed()
    {
        $piece = new Piece(1, true);

        $piece->setUsed(false);
        $this->assertEquals(false, $piece->getUsed());
    }

    public function testSetUsed()
    {
        $piece = new Piece(1, false);

        $piece->setUsed(true);
        $this->assertEquals(true, $piece->getUsed());
    }

    public function testSetId()
    {
        $piece = new Piece(3, false);

        $piece->setId(2);
        $this->assertEquals(2, $piece->getId());
    }
}
