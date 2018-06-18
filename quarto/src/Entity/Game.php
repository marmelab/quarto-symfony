<?php
namespace Quarto\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @Entity
 */
class Game
{
    /** @Id @Column(type="integer") */
    private $id_game;

    /** @Column(type="bool") */
    private $is_player_one_turn;

    /** @Column(type="integer") */
    private $selected_piece;

    public function __construct(int $id_game, bool $is_player_one_turn, int $selected_piece)
    {
        $this->id_game = $id_game;
        $this->is_player_one_turn = $is_player_one_turn;
        $this->selected_piece = $selected_piece;
    }

    public function getIdGame() : int
    {
        return $this->id_game;
    }

    public function getIsPlayerOneTurn() : bool
    {
        return $this->is_player_one_turn;
    }

    public function getSelectedPiece() : int
    {
        return $this->selected_piece;
    }

    public function setIsPlayerOneTurn(int $is_player_one_turn)
    {
        $this->is_player_one_turn = $is_player_one_turn;
    }

    public function setSelectedPiece(int $selected_piece)
    {
        $this->selected_piece = $selected_piece;
    }
}