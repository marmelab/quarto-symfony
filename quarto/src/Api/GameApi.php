<?php
namespace App\Api;

use App\Entity\Game;

class GameApi {

  public function __construct() {

  }

  public function new(int $size) : Game {
    $grid = [];

    for ($i = 0; $i < $size; $i++) {
        $grid[$i] = [];
        for ($j = 0; $j < $size; $j++) {
            $grid[$i][$j] = '.';
        }
    }
    return new Game(1, $grid, true, 0);
  }

  public function getAllPieces(Game $game) : Array {
    $pieces = [];
    $size = count($game->getGrid());

    if ($size > 0) {
        for ($i = 1; $i <= $size * $size; $i++) {
            $pieces[$i] = new Piece($i, false);
            foreach($game->getGrid() as $raw) {
                foreach($raw as $item) {
                    if ($item == $i) {
                        $pieces[$i]->$used = true;
                    }
                }
            }
        }
    }
    return $pieces;
  }

  public function changeTurn(Game $game) : Game {
    $nextGame = clone $game;
    $nextGame->$is_player_one_turn = !$is_player_one_turn;
    return $nextGame;
  }

  public function selectNextPiece(Game $game, int $id_piece) : Game {
    $nextGame = clone $game;
    $nextGame->selected_piece = $id_piece;
    return $nextGame;
  }

}