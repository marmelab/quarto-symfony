<?php

namespace App\Api;

use App\Entity\Game;
use App\Repository\GameRepository;
use App\Api\Piece;
use App\Api\AIGame;
use App\Api\ApiConsumerService;

class GameManager
{

    private $nodeIsomorphicUrl = "http://192.168.86.248:3000/";
    private $aiApiUrl = "http://192.168.86.248:8080/suggestMove";
    private $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function newGame(int $size, $playerName = '') : Game
    {
        $game = Game::new($size, $playerName);
        $this->gameRepository->save($game);
        return $game;
    }

    public function newGameSolo(int $size, $playerName = '') : Game
    {
        $game = $this->newGame($size, $playerName);
        $game->setNumberOfPlayers(2);
        $game->setSoloGame(true);
        $this->gameRepository->save($game);
        return $game;
    }

    public function playPieceSelection(Game $game, int $piece) : bool
    {
        if ($game->getSelectedPiece() != 0) {
            return false;
        }
        $game->selectNextPiece($piece);
        $game->changeTurn();
        $this->gameRepository->save($game);
        try {
            ApiConsumerService::callAPI("GET", $this->nodeIsomorphicUrl.$game->getIdGame().'/updated', 1);
        } catch (Exception $e) {
            //No node responded, then ignore
        }
        return true;
    }

    public function playPiecePLacement(Game $game, int $x, int $y) : bool
    {
        $grid = $game->getGrid();
        if ($grid[$y][$x] != ".") {
            return false;
        }
        $game->placePiece($x, $y);
        $this->gameRepository->save($game);
        try {
            ApiConsumerService::callAPI("GET", $this->nodeIsomorphicUrl.$game->getIdGame().'/updated', 1);
        } catch (Exception $e) {
            //No node responded, then ignore
        }
        return true;
    }
    
    public function submitToAI(Game $game) : bool
    {
        $jsonContent = $game->toAIGame()->toValidJsonString();

        $result = ApiConsumerService::callAPI("POST", $this->aiApiUrl, 30, $jsonContent);
        $resultJsonContent = json_decode($result, true);
       
        $move = $resultJsonContent['Move'];
        $piece = $resultJsonContent['Piece'];

        $this->playPiecePLacement($game, $move[1], $move[0]);
        $this->playPieceSelection($game, $piece);

        //Patch for API Go wich give back the hand after player even if it wins
        //That leads to client to trust he win but in fact not
        if ($game->getClosed() === true) {
            $game->setIsPlayerOneTurn(false);
            $this->gameRepository->save($game);
        }
        try {
            ApiConsumerService::callAPI("GET", $this->nodeIsomorphicUrl.$game->getIdGame().'/updated', 1);
        } catch (Exception $e) {
            //No node responded, then ignore
        }
        return true;
    }
}
