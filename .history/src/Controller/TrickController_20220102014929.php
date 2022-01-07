<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Component\HttpFoundation\Request;

#[Route('/tricks')]
class TrickController extends AbstractController
{
    #[
        Route('/', defaults: ['page' => '1', '_format' => 'html'], methods: ['GET'], name: 'trick_index'),
        Route('/rss.xml', defaults: ['page' => '1', '_format' => 'xml'], methods: ['GET'], name: 'trick_rss'),
        Route('/page/{page<[1-9]\d*>}', defaults: ['_format' => 'html'], methods: ['GET'], name: 'trick_index_paginated'),
    ]
    #[Cache(smaxage: 10)]
    public function index(Request $request, int $page, string $_format, TrickRepository $tricks): Response
    {
        $latestPosts = $tricks->findLatest($page);

        return $this->render(
            'trick/index.' . $_format . '.twig',
            [
                'paginator' => $latestPosts
            ]
        );
    }

    #[Route('/details/{trick_slug}', methods: ['GET'], name: 'trick_detail')]
    public function trickShow(Trick $trick): Response
    {
        return $this->render('trick/trick_show.html.twig', ['trick' => $trick]);
    }
}
