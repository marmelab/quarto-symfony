<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Game;
use App\Api\GameLogic;

class GameRepository extends EntityRepository {

  private $em;

  public function __construct(EntityManagerInterface $em) {
    $this->em = $em;
  }

  public function findGameById(string $id) {
    return $this->em->find('App:Game', $id);
  }

  public function getOpenedGamesList(string $playerToken) {
    $query = $this->em->createQuery("SELECT g FROM App:Game g WHERE g.number_players = 1 AND g.closed=false");
    return $query->getResult();
  }

  public function remove(string $id) {
    $game = $this->em->getReference('App:Game', $id);
    $this->em->remove($game);
    $this->em->flush();
  }
  
  public function save($game) {
    $this->em->persist($game);
    $this->em->flush();
  }
}
