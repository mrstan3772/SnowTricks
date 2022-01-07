<?php

namespace App\Controller;

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

    #[Route('/tricks/details/{slug}', methods: ['GET'], name: 'trick')]
    public function trickShow(Trick $trick): Response
    {
        // Symfony's 'dump()' function is an improved version of PHP's 'var_dump()' but
        // it's not available in the 'prod' environment to prevent leaking sensitive information.
        // It can be used both in PHP files and Twig templates, but it requires to
        // have enabled the DebugBundle. Uncomment the following line to see it in action:
        //
        // dump($post, $this->getUser(), new \DateTime());

        return $this->render('trick/trick_show.html.twig', ['trick' => $trick]);
    }
}
