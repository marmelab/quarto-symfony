<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Api\GameManager;
use App\Entity\Game;
use App\Repository\GameRepository;
use App\Api\CookieManager;

class DefaultController extends Controller
{
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }
    
    public function index(Request $request)
    {
        return new Response($this->twig->render('unknown.html.twig'));
    }
}
