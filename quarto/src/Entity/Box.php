<?php
namespace Quarto\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 */
class Box
{
    /** @Id @Column(type="integer") */
    private $id_game;
    /** @Id @Column(type="integer") */
    private $x;
    /** @Id @Column(type="integer") */
    private $y;

    /** @Column(type="integer") */
    private $id_piece;

    public function __construct(int $id_game, int $x, int $y, int $id_piece)
    {
        $this->id_game = $id_game;
        $this->x = $x;
        $this->y = $y;
        $this->id_piece = $id_piece;
    }

    public function getIdGame() : int
    {
        return $this->id_game;
    }

    public function getX() : int
    {
        return $this->x;
    }

    public function getY() : int
    {
        return $this->y;
    }

    public function getIdPiece() : int
    {
        return $this->id_piece;
    }

    public function setIdPiece(int $id_piece)
    {
        $this->id_piece = $id_piece;
    }
}