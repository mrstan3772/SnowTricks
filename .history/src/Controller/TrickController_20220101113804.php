<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

#[Route('/tricks')]
class TrickController extends AbstractController
{
    #[
        Route('/', defaults: ['page' => '1', '_format' => 'html'], methods: ['GET'], name: 'tricks_index'),
        Route('/rss.xml', defaults: ['page' => '1', '_format' => 'xml'], methods: ['GET'], name: 'tricks_rss'),
        Route('/page/{page<[1-9]\d*>}', defaults: ['_format' => 'html'], methods: ['GET'], name: 'tricks_index_paginated'),
    ]
    #[Cache(smaxage: 10)]
    public function index(): Response
    {
        return $this->render('trick/index.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }
}
