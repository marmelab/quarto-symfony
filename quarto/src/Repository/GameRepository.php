<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;
use App\Api\GameLogic;

class GameRepository extends EntityRepository
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findGameById(string $id)
    {
        return $this->em->find('App:Game', $id);
    }

    public function getOpenedGamesList(array $tokenList)
    {
        if (count($tokenList) === 0) {
            $tokenList=["NOTHING"];
        }
        $query = $this->em->createQuery("SELECT g FROM App:Game g  " .
        "WHERE g.closed=false " .
        "AND g.number_players = 1 " .
        "AND g.token_player_one NOT IN (:player_one_token) " .
        "AND g.token_player_two NOT IN (:player_two_token) " .
        "ORDER BY g.id_game ASC");
        $query->setParameter('player_one_token', $tokenList);
        $query->setParameter('player_two_token', $tokenList);
        return $query->getResult();
    }

    public function getCurrentGamesList(array $tokenList)
    {
        $query = $this->em->createQuery("SELECT g FROM App:Game g  " .
        "WHERE g.closed=false  " .
        "AND (g.token_player_one IN (:player_one_token)  " .
        "  OR g.token_player_two IN (:player_two_token))  " .
        "ORDER BY g.id_game ASC");
        $query->setParameter('player_one_token', $tokenList);
        $query->setParameter('player_two_token', $tokenList);
        return $query->getResult();
    }

    public function getOnlySpectateGamesList(array $tokenList)
    {
        if (count($tokenList) === 0) {
            $tokenList=["NOTHING"];
        }
        $query = $this->em->createQuery("SELECT g FROM App:Game g  " .
        "WHERE g.closed=false  " .
        "AND g.number_players = 2 " .
        "AND g.token_player_one NOT IN (:player_one_token) " .
        "AND g.token_player_two NOT IN (:player_two_token) " .
        "ORDER BY g.id_game ASC");
        $query->setParameter('player_one_token', $tokenList);
        $query->setParameter('player_two_token', $tokenList);
        return $query->getResult();
    }

    public function remove(string $id)
    {
        $game = $this->em->getReference('App:Game', $id);
        $this->em->remove($game);
        $this->em->flush();
    }
    
    public function save($game)
    {
        $this->em->persist($game);
        $this->em->flush();
    }
}
