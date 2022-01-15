<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Event\CommentCreatedEvent;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
        $comments = $this->sortComments($trick);

        return $this->render(
            'trick/trick_show.html.twig',
            [
                'trick' => $trick,
                'comments' => $comments
            ]
        );
    }

    #[Route('/comment/{trick_slug}/new', methods: ['POST'], name: 'comment_new')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function commentNew(Request $request, Trick $trick, EventDispatcherInterface $eventDispatcher, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $comment->setCommentCreationDate(new \DateTime());
        $comment->setCommentAuthor($this->getUser());
        $trick->addTrickComment($comment);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            $eventDispatcher->dispatch(new CommentCreatedEvent($comment));

            return $this->redirectToRoute('trick_detail', ['trick_slug' => $trick->getTrickSlug()]);
        }
    }

    #[Route('/comment/{trick_slug}/thread/{index}', methods: ['GET'], name: 'comment_thread')]
    public function commentThread(Trick $trick, Int $index): Response
    {
        $comments = $this->sortComments($trick);

        // $start_index = $index * 4;
        // $end_index = ($index * 4) + 5;

        // dd($start_index, $end_index);

        // $comments = array_slice($comments, $start_index, $end_index);

        return $this->render(
            'trick/comment_thread.html.twig',
            [
                'comments' => $comments,
                'index' => $index
            ]
        );
    }

    public function commentForm(Trick $trick): Response
    {
        $form = $this->createForm(CommentType::class);

        return $this->render(
            'trick/_comment_form.html.twig',
            [
                'trick' => $trick,
                'form' => $form->createView(),
            ]
        );
    }

    private function sortComments(Trick $trick): array
    {
        $comments = $trick->getTrickComments()->toArray();

        usort(
            $comments,
            function ($a, $b) {
                return $a->getCommentCreationDate()->getTimestamp() - $b->getCommentCreationDate()->getTimestamp();
            }
        );

        return $comments;
    }
}
