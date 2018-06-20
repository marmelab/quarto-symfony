<?php

namespace App\Api;

use App\Entity\Game;
use App\Api\Piece;

class GameApi {

    public function __construct() {

    }

    function new(int $size) : Game {
        $grid = [];

        for ($i = 0; $i < $size; $i++) {
            $grid[$i] = [];
            for ($j = 0; $j < $size; $j++) {
                $grid[$i][$j] = '.';
            }
        }
        return new Game(0, $grid, true, 0);
    }

    function getAllPieces(Game $game) : Array {
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

    function changeTurn(Game $game) {
        $game->setIsPlayerOneTurn(!$game->getIsPlayerOneTurn());
    }

    function selectNextPiece(Game $game, int $id_piece) {
        $game->setSelectedPiece($id_piece);
    }

    function placePiece(Game $game, int $x, int $y) {
        $grid = $game->getGrid();
        $grid[$y][$x] = $game->getSelectedPiece();
        $game->setGrid($grid);
        $game->setSelectedPiece(0);
    }
}