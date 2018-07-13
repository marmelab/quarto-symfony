<?php
namespace App\Api;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AIGame
{

    private $Grid;
    private $Piece;
    private $Move;

    public function __construct(array $grid, int $piece, array $move)
    {
        $this->Grid = $grid;
        $this->Piece = $piece;
        $this->Move = $move;
    }

    public function getGrid() : array
    {
        return $this->Grid;
    }

    public function getPiece() : int
    {
        return $this->Piece;
    }

    public function getMove() : array
    {
        return $this->Move;
    }

    public function setGrid(array $grid)
    {
        $this->Grid = $grid;
        return $this;
    }

    public function setPiece(int $piece)
    {
        $this->Piece = $piece;
        return $this;
    }

    public function setMove(array $move)
    {
        $this->Move = $move;
        return $this;
    }

    public function toValidJsonString() : string
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($this, 'json');
        return str_replace("\".\"", "0", $jsonContent);
    }
}
