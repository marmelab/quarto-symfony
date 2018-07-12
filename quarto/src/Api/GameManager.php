<?php

namespace App\Api;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Api\Piece;

class GameManager {

    private $gameRepository;

    public function __construct(GameRepository $gameRepository) {
        $this->gameRepository = $gameRepository;
    }

    public function newGame(int $size) : Game {
        $game = Game::new($size);
        $this->gameRepository->save($game);
        return $game;
    }

    public function playPieceSelection(Game $game, int $piece) : bool {
        if ($game->getSelectedPiece() != 0) {
            return false;
        }
        $game->selectNextPiece($piece);
        $game->changeTurn();
        $this->gameRepository->save($game);
        return true;
    }

    public function playPiecePLacement(Game $game, int $x, int $y) : bool {
        $grid = $game->getGrid();
        if ($grid[$y][$x] != ".") {
            return false;
        }
        $game->placePiece($x, $y);
        $this->gameRepository->save($game);
        return true;
    }
}
