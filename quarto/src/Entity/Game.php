<?php

namespace App\Entity;

use App\Api\TokenManager;
use App\Api\Piece;
use App\Api\AIGame;
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

    /** @ORM\Column(type="boolean") */
    private $solo_game;

    /** @ORM\Column(type="json_array") */
    private $winning_line;

    /** @ORM\Column(type="boolean") */
    private $closed;

    /** @ORM\Column(type="string") */
    private $token_player_one;

    /** @ORM\Column(type="string") */
    private $token_player_two;

    public $locked;

    public $watch_only;

    public $you_won;

    public $winner_id;

    public function __construct(int $id_game, Array $grid, bool $is_player_one_turn, int $selected_piece, int $number_players, bool $solo_game, Array $winning_line, bool $closed=false, string $token_player_one='', string $token_player_two='')
    {
        $this->id_game = $id_game;
        $this->grid = $grid;
        $this->is_player_one_turn = $is_player_one_turn;
        $this->selected_piece = $selected_piece;
        $this->number_players = $number_players;
        $this->solo_game = $solo_game;
        $this->winning_line = $winning_line;
        $this->closed = $closed;
        $this->token_player_one = $token_player_one;
        $this->token_player_two = $token_player_two;
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

    public function getSoloGame() : bool
    {
        return $this->solo_game;
    }

    public function getWinningLine() : Array
    {
        return $this->winning_line;
    }

    public function getClosed() : bool
    {
        return $this->closed;
    }

    public function getTokenPlayerOne() : string
    {
        return $this->token_player_one;
    }

    public function getTokenPlayerTwo() : string
    {
        return $this->token_player_two;
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

    public function setSoloGame(bool $solo_game) : Game
    {
        $this->solo_game = $solo_game;
        return $this;
    }

    public function setWinningLine(Array $winning_line) : Game
    {
        $this->winning_line = $winning_line;
        return $this;
    }

    public function setClosed(bool $closed) : Game
    {
        $this->closed = $closed;
        return $this;
    }

    public function setTokenPlayerOne(string $token_player_one) : Game
    {
        $this->token_player_one = $token_player_one;
        return $this;
    }

    public function setTokenPlayerTwo(string $token_player_two) : Game
    {
        $this->token_player_two = $token_player_two;
        return $this;
    }

    static function new(int $size) : Game {
        $grid = [];

        for ($i = 0; $i < $size; $i++) {
            $grid[$i] = [];
            for ($j = 0; $j < $size; $j++) {
                $grid[$i][$j] = '.';
            }
        }

        $token = TokenManager::generate();

        return new Game(0, $grid, true, 0, 1, false, [], false, $token);
    }

    public function getAllPieces() : Array {
        $pieces = [];
        $size = count($this->grid);

        if ($size > 0) {
            for ($i = 1; $i <= $size * $size; $i++) {
                $pieces[$i] = new Piece($i, false);
                foreach($this->grid as $raw) {
                    foreach($raw as $item) {
                        if ($item === $i) {
                            $pieces[$i]->setUsed(true);
                        }
                    }
                }
            }
        }
        return $pieces;
    }

    public function getRemainingPieces() : Array {
        $pieces = [];
        $size = count($this->grid);

        if ($size > 0) {
            for ($i = 1; $i <= $size * $size; $i++) {
                foreach($this->grid as $raw) {
                    foreach($raw as $item) {
                        if ($item !== $i) {
                            $pieces[$i] = new Piece($i, false);
                        }
                    }
                }
            }
        }
        return $pieces;
    }

    public function changeTurn() : Game {
        $this->setIsPlayerOneTurn(!$this->getIsPlayerOneTurn());
        return $this;
    }

    public function selectNextPiece(int $id_piece) : Game {
        $this->setSelectedPiece($id_piece);
        return $this;
    }

    public function placePiece(int $x, int $y) : Game {
        $winningLine = $this->getWinningPosition($x, $y, $this->getSelectedPiece());
        $grid = $this->getGrid();
        $grid[$y][$x] = $this->getSelectedPiece();
        $this->setGrid($grid);
        $this->setSelectedPiece(0);
        $this->setWinningLine($winningLine);
        $this->setClosed(count($winningLine)>0 || count($this->getRemainingPieces())==0);
        return $this;
    }

    public function getWinningPosition(int $x, int $y, int $piece) : array {
        $testGame = clone $this;
        $grid = $testGame->getGrid();
        $grid[$y][$x] = $piece;
        $testGame->setGrid($grid);
        $piecesLine = $testGame->getPiecesRaw($x, $y);
        if (Piece::isWinningLine($piecesLine)) {
            return $piecesLine;
        }
        $piecesLine = $testGame->getPiecesColumn($x, $y);
        if (Piece::isWinningLine($piecesLine)) {
            return $piecesLine;
        }
        $piecesLine = $testGame->getPiecesSlashDiag($x, $y);
        if (Piece::isWinningLine($piecesLine)) {
            return $piecesLine;
        }
        $piecesLine = $testGame->getPiecesBackSlashDiag($x, $y);
        if (Piece::isWinningLine($piecesLine)) {
            return $piecesLine;
        }
        return [];
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
        if ($x == count($this->getGrid())-$y-1) {
            for ($i = 0; $i < count($this->getGrid()); $i++) {
                $piecesLine[$i] = $this->getGrid()[$i][count($this->grid)-$i-1];
            }
        }
        return $piecesLine;
    }

    public function securiseGameBeforeReturn(string $token, int $register = 0) : Game {
        if (($this->getTokenPlayerOne() != $token && $this->getIsPlayerOneTurn())
            || ($this->getTokenPlayerTwo() != $token && !$this->getIsPlayerOneTurn())) {
            $this->locked = true;
            if ($this->getTokenPlayerOne() != $token && $this->getTokenPlayerTwo() != $token && $register != 1) {
                $this->watch_only = true;
            }
        }
        else {
            $this->locked = false;
        }
        $this->setTokenPlayerOne('');
        if ($register!=1) {
            $this->setTokenPlayerTwo('');
        }
        return $this;
    }

    public function winningInformation(string $token) : Game {
        if ($this->getWinningLine()!= [])
        {
            if ($this->getTokenPlayerOne() == $token && $this->getIsPlayerOneTurn()) {
                $this->you_won = true;
                $this->winner_id = 1;
            }
            else if ($this->getTokenPlayerTwo() == $token && !$this->getIsPlayerOneTurn()) {
                $this->you_won = true;
                $this->winner_id = 2;
            }
            else if ($this->getTokenPlayerOne() == $token && !$this->getIsPlayerOneTurn()) {
                $this->you_won = false;
                $this->winner_id = 1;
            }
            else {
                $this->you_won = false;
                $this->winner_id = 2;
            }
        }
        else {
            $this->you_won = false;
            $this->winner_id = 0;
        }
        return $this;
    }

    public function toAIGame() : AIGame {
        return new AIGame($this->grid, $this->selected_piece, []);
    }

    /* public function importAIGame(AIGame $aiGame) : Game {
        $grid = $this->getGrid();

        $this->setGrid($aiGame.getGrid());
        $this->setGrid($grid);
        $this->setSelectedPiece($aiGame.getPiece());
        return $this;
    } */
}
