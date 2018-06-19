<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 * @ORM\Table
 */
class Game {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id_game;

    /** @ORM\Column(type="json_array") */
    private $grid;

    /** @ORM\Column(type="boolean") */
    private $is_player_one_turn;

    /** @ORM\Column(type="integer") */
    private $selected_piece;

    public function __construct(int $id_game, Array $grid, bool $is_player_one_turn, int $selected_piece)
    {
        $this->id_game = $id_game;
        $this->grid = $grid;
        $this->is_player_one_turn = $is_player_one_turn;
        $this->selected_piece = $selected_piece;
    }

    public function getIdGame() : int
    {
        return $this->id_game;
    }

    public function getGrid() : Array
    {
        return $this->grid;
    }

    public function getIsPlayerOneTurn() : bool
    {
        return $this->is_player_one_turn;
    }

    public function getSelectedPiece() : int
    {
        return $this->selected_piece;
    }

    public function setGrid(Array $grid)
    {
        $this->grid = $grid;
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
