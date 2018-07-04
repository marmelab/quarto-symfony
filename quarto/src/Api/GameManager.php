<?php

namespace App\Api;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Api\Piece;
use App\Api\AIGame;
use App\Api\ApiConsumerService;

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

    public function newGameSolo(int $size) : Game {
        $game = $this->newGame($size);
        $game->setNumberOfPlayers(2);
        $game->setSoloGame(true);
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

    public function submitToAI(Game $game) : bool {
        $jsonContent = $game->toAIGame()->toValidJsonString();

        $result = ApiConsumerService::CallAPI("POST", "http://192.168.86.248:8080/suggestMove", $jsonContent);
        $resultJsonContent = json_decode($result, true);
       
        $move = $resultJsonContent['Move'];
        $piece = $resultJsonContent['Piece'];

        $this->playPiecePLacement($game, $move[1], $move[0]);
        $this->playPieceSelection($game, $piece);
        return true;
    }
}
