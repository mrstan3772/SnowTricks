<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickAttachmentRepository;
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
    public function index(TrickAttachmentRepository $tricks): Response
    {
        $latestPosts = $tricks->findLatest(2));

        return $this->render(
            'home/index.html.twig',
            [
                'controller_name' => 'HomeController',
            ]
        );
    }
}
