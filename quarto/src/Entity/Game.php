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

    /** @ORM\Column(type="integer") */
    private $number_players;

    /** @ORM\Column(type="json_array") */
    private $winning_line;

    public function __construct(int $id_game, Array $grid, bool $is_player_one_turn, int $selected_piece, int $number_players, Array $winning_line)
    {
        $this->id_game = $id_game;
        $this->grid = $grid;
        $this->is_player_one_turn = $is_player_one_turn;
        $this->selected_piece = $selected_piece;
        $this->number_players = $number_players;
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

    public function getNumberOfPlayers() : int
    {
        return $this->number_players;
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

    public function setNumberOfPlayers(int $number_players) : Game
    {
        $this->number_players = $number_players;
        return $this;
    }

    public function setWinningLine(Array $winning_line) : Game
    {
        $this->winning_line = $winning_line;
        return $this;
    }

    public function getPiecesRaw(int $x, int $y) : array {
        return $this->grid[$y];
    }

    public function getPiecesColumn(int $x, int $y) : array {
        $piecesLine = [];
        for ($i = 0; $i < count($this->grid); $i++) {
            $piecesLine[$i] = $this->grid[$i][$x];
        }
        return $piecesLine;
    }

    public function getPiecesSlashDiag(int $x, int $y) : array {
        $piecesLine = [];
        if ($x == $y) {
            for ($i = 0; $i < count($this->grid); $i++) {
                $piecesLine[$i] = $this->grid[$i][$i];
            }
        }
        return $piecesLine;
    }

    public function getPiecesBackSlashDiag(int $x, int $y) : array {
        $piecesLine = [];
        if ($x == count($this->grid)-$y-1) {
            for ($i = 0; $i < count($this->grid); $i++) {
                $piecesLine[$i] = $this->grid[$i][count($this->grid)-$i-1];
            }
        }
        return $piecesLine;
    }
}
