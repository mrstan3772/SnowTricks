<?php

namespace App\Controller;

use App\Repository\TrickAttachmentRepository;
use App\Repository\TrickRepository;
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
    public function index(TrickRepository $tricks): Response
    {
        $latestPosts = $tricks->findLatest(1);
        $tricks->f

        return $this->render(
            'home/index.html.twig',
            [
                'controller_name' => 'HomeController',
                'paginator' => $latestPosts
            ]
        );
    }
}
