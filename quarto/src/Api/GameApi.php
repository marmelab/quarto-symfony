<?php

namespace App\Api;

use App\Entity\Game;
use App\Api\Piece;

class GameApi {

    public function __construct() {

    }

    static function new(int $size) : Game {
        $grid = [];

        for ($i = 0; $i < $size; $i++) {
            $grid[$i] = [];
            for ($j = 0; $j < $size; $j++) {
                $grid[$i][$j] = '.';
            }
        }
        return new Game(0, $grid, true, 0, []);
    }

    static function getAllPieces(Game $game) : Array {
        $pieces = [];
        $size = count($game->getGrid());

        if ($size > 0) {
            for ($i = 1; $i <= $size * $size; $i++) {
                $pieces[$i] = new Piece($i, false);
                foreach($game->getGrid() as $raw) {
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

    static function changeTurn(Game $game) {
        $game->setIsPlayerOneTurn(!$game->getIsPlayerOneTurn());
    }

    static function selectNextPiece(Game $game, int $id_piece) {
        $game->setSelectedPiece($id_piece);
    }

    static function placePiece(Game $game, int $x, int $y) {
        $winningLine = GameApi::getWinningPosition($game, $x, $y, $game->getSelectedPiece());
        $grid = $game->getGrid();
        $grid[$y][$x] = $game->getSelectedPiece();
        $game->setGrid($grid);
        $game->setSelectedPiece(0);
        $game->setWinningLine($winningLine);
    }

    static function getWinningPosition(Game $game, int $x, int $y, int $piece) : array {
        $testGame = clone $game;
        $grid = $testGame->getGrid();
        $grid[$y][$x] = $piece;
        $testGame->setGrid($grid);
        $piecesLine = GameApi::getPiecesRaw($testGame, $x, $y);
        if (Piece::isWinningLine($piecesLine)) {
            return $piecesLine;
        }
        $piecesLine = GameApi::getPiecesColumn($testGame, $x, $y);
        if (Piece::isWinningLine($piecesLine)) {
            return $piecesLine;
        }
        $piecesLine = GameApi::getPiecesSlashDiag($testGame, $x, $y);
        if (Piece::isWinningLine($piecesLine)) {
            return $piecesLine;
        }
        $piecesLine = GameApi::getPiecesBackSlashDiag($testGame, $x, $y);
        if (Piece::isWinningLine($piecesLine)) {
            return $piecesLine;
        }
        return [];
    }

    static function getPiecesRaw(Game $game, int $x, int $y) : array {
        return $game->getGrid()[$y];
    }

    static function getPiecesColumn(Game $game, int $x, int $y) : array {
        $piecesLine = [];
        $grid = $game->getGrid();
        for ($i = 0; $i < count($grid); $i++) {
            $piecesLine[$i] = $grid[$i][$x];
        }
        return $piecesLine;
    }

    static function getPiecesSlashDiag(Game $game, int $x, int $y) : array {
        $piecesLine = [];
        $grid = $game->getGrid();
        if ($x == $y) {
            for ($i = 0; $i < count($grid); $i++) {
                $piecesLine[$i] = $grid[$i][$i];
            }
        }
        return $piecesLine;
    }

    static function getPiecesBackSlashDiag(Game $game, int $x, int $y) : array {
        $piecesLine = [];
        $grid = $game->getGrid();
        if ($x == count($grid)-$y-1) {
            for ($i = 0; $i < count($grid); $i++) {
                $piecesLine[$i] = $grid[$i][count($grid)-$i-1];
            }
        }
        return $piecesLine;
    }
}