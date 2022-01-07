<?php

namespace App\Controller;

use App\Entity\Trick;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[
        Route('/'),
        Route('/home', name: 'home'),
        Route('/homepage', name: 'homepage'),
    ]
    public function index(Trick $trick): Response
    {
        $latestPosts = $tricks->findLatest($page);

        return $this->render(
            'home/index.html.twig',
            [
                'controller_name' => 'HomeController',
            ]
        );
    }
}
