<?php

namespace App\Api;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Api\Piece;

class GameManager {

    private $game;
    private $gameRepository;

    public function __construct(GameRepository $gameRepository) {

        $this->game = NULL;
        $this->gameRepository = $gameRepository;
    }

    public function newGame(int $size) : Game {
        $grid = [];

        for ($i = 0; $i < $size; $i++) {
            $grid[$i] = [];
            for ($j = 0; $j < $size; $j++) {
                $grid[$i][$j] = '.';
            }
        }
        $this->game = new Game(0, $grid, true, 0, 1, []);
        return  $this->game;
    }

    public function setGame(int $idGame) {
        $this->game = $this->gameRepository->findGameById($idGame);
    }

    public function getGame() : Game {
        return $this->game;
    }

    public function saveGame() {
        $this->gameRepository->save($this->game);
    }

    public function getAllPieces() : Array {
        $pieces = [];
        $size = count($this->game->getGrid());

        if ($size > 0) {
            for ($i = 1; $i <= $size * $size; $i++) {
                $pieces[$i] = new Piece($i, false);
                foreach($this->game->getGrid() as $raw) {
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

    public function changeTurn() {
        $this->game->setIsPlayerOneTurn(!$this->game->getIsPlayerOneTurn());
    }

    public function selectNextPiece(int $id_piece) {
        $this->game->setSelectedPiece($id_piece);
    }

    public function placePiece(int $x, int $y) {
        $winningLine = $this->getWinningPosition($x, $y, $this->game->getSelectedPiece());
        $grid = $this->game->getGrid();
        $grid[$y][$x] = $this->game->getSelectedPiece();
        $this->game->setGrid($grid);
        $this->game->setSelectedPiece(0);
        $this->game->setWinningLine($winningLine);
    }

    public function getWinningPosition(int $x, int $y, int $piece) : array {
        $testGame = clone $this->game;
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

    public function setNumberOfPlayers(int $number) {
        $this->game->setNumberOfPlayers($number);
    }

    public function getNumberOfPlayers() : int {
        return $this->game->getNumberOfPlayers();
    }
}