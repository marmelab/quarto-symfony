<?php

namespace Tests\App\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit\Framework\TestCase;
use App\Entity\Game;
use App\Api\GameManager;
use App\Api\Piece;
use App\Repository\GameRepository;

class ControlerTest extends WebTestCase {

  public function testMainPageResponds()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
