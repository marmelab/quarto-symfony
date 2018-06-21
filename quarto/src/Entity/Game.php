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

    /** @ORM\Column(type="json_array") */
    private $winning_line;

    public function __construct(int $id_game, Array $grid, bool $is_player_one_turn, int $selected_piece, Array $winning_line)
    {
        $this->id_game = $id_game;
        $this->grid = $grid;
        $this->is_player_one_turn = $is_player_one_turn;
        $this->selected_piece = $selected_piece;
        $this->winning_line = $winning_line;
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

    public function getWinningLine() : Array
    {
        return $this->winning_line;
    }

    public function setIdGame(int $id_game)
    {
        $this->id_game = $id_game;
    }

    public function setGrid(Array $grid) : Game
    {
        $this->grid = $grid;
        return $this;
    }

    public function setIsPlayerOneTurn(bool $is_player_one_turn) : Game
    {
        $this->is_player_one_turn = $is_player_one_turn;
        return $this;
    }

    public function setSelectedPiece(int $selected_piece) : Game
    {
        $this->selected_piece = $selected_piece;
        return $this;
    }

    public function setWinningLine(Array $winning_line) : Game
    {
        $this->winning_line = $winning_line;
        return $this;
    }
}
