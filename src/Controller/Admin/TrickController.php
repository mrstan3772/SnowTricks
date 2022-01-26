<?php

namespace App\Controller\Admin;

use App\Entity\Trick;
use App\Entity\TrickAttachment;
use App\Form\TrickType;
use App\Repository\GroupRepository;
use App\Repository\TrickRepository;
use App\Security\PostVoter;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;

#[Route('/admin/trick'), IsGranted('ROLE_ADMIN')]
class TrickController extends AbstractController
{
    private Slugify $slugify;

    public function __construct(
        Slugify $slugify,
    ) {
        $this->slugify = $slugify;
    }

    #[
        Route('/', methods: ['GET'], name: 'admin_index'),
        Route('/', methods: ['GET'], name: 'admin_trick_index'),
    ]
    public function index(TrickRepository $tricks): Response
    {
        $authorPosts = $tricks->findBy(['trick_author' => $this->getUser()], ['trick_creation_date' => 'DESC']);

        return $this->render('admin/trick/index.html.twig', ['tricks' => $authorPosts]);
    }

    #[Route('/new', methods: ['GET', 'POST'], name: 'admin_trick_new')]
    public function new(Request $request, GroupRepository $groups, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper): Response
    {
        $trick = new Trick();

        $trick->setTrickAuthor($this->getUser());
        $trick->setTrickCreationDate(new \DateTime());

        // See https://symfony.com/doc/current/form/multiple_buttons.html
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        $data = $form->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            $group = $groups->findOneBy(
                [
                    'id' => $data->getTrickGroupId(),
                ]
            );

            $trick->setTrickGroup($group);

            $trick->setTrickSlug($this->slugify->slugify($trick->getTrickName()));

            $entityManager->persist($trick);

            // UPLOAD REFERENCES
            $uploadList = $request->files->get('reference');

            foreach ($uploadList as $uploadedFile) {

                $violations = $uploaderHelper->validateRefFile($uploadedFile);

                if ($violations->count() > 0) {
                    return $this->json($violations, 400);
                }

                $trickType = $uploaderHelper->defType($uploadedFile);

                $location = $trickType === 'image' ? UploaderHelper::TRICK_IMAGE_REFERENCE : UploaderHelper::TRICK_VIDEO_REFERENCE;

                $filename = $uploaderHelper->uploadTrickReference($uploadedFile, $location, true);

                $trickReference = new TrickAttachment($trick);
                $trickReference->setTaFilename($filename);
                $trickReference->setTaType($trickType);
                $trickReference->setTaOriginalFilename($uploadedFile->getClientOriginalName() ?? $filename);
                $trickReference->setTaMimeType($uploadedFile->getMimeType() ?? 'application/octet-stream');

                $entityManager->persist($trickReference);
            }

            $entityManager->flush();

            return $this->json(
                $trick->getTrickName(),
                200,
            );

            // $this->addFlash('success', 'Création de la figure <strong>' . $trick->getTrickName() . '</stong> achevé !');

            // return $this->redirectToRoute('admin_trick_index');
        }

        return $this->render(
            'admin/trick/new.html.twig',
            [
                'trick' => $trick,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route('/{trick_slug}', methods: ['GET'], name: 'admin_trick_show')]
    public function show(Trick $trick): Response
    {
        $this->denyAccessUnlessGranted(PostVoter::SHOW, $trick, 'La figure ne peut être affiché que si vous en êtes l\'auteur.');

        return $this->render(
            'admin/trick/show.html.twig',
            [
                'trick' => $trick,
            ]
        );
    }

    #[Route('/{trick_slug}/edit', methods: ['GET', 'POST'], name: 'admin_trick_edit')]
    #[IsGranted('edit', subject: 'trick', message: 'Les figures ne peuvent être modifiés que par leurs auteurs.')]
    public function edit(Request $request, Trick $trick, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trick->setTrickSlug($this->slugify->slugify($trick->getTrickName()));
            $trick->setTrickUpdateDate(new \DateTime);
            $entityManager->flush();
            $this->addFlash('success', 'Mise à jour réalisé avec succès pour la figure ' . $trick->getTrickName() . '!');

            return $this->redirectToRoute('admin_trick_edit', ['trick_slug' => $trick->getTrickSlug()]);
        }

        return $this->render(
            'admin/trick/edit.html.twig',
            [
                'trick' => $trick,
                'form' => $form->createView(),
            ]
        );
    }

    #[Route('/{trick_slug}/delete', methods: ['POST'], name: 'admin_trick_delete')]
    #[IsGranted('delete', subject: 'trick')]
    public function delete(Request $request, Trick $trick, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper): Response
    {
        if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('admin_trick_index');
        }

        $trickAttachments = $trick->getTrickAttachments();
        $trickComments = $trick->getTrickComments();

        foreach ($trickAttachments as $attachment) {
            $entityManager->remove($attachment);
            $uploaderHelper->deleteFile($attachment->getFilePath(), true);
        }

        foreach ($trickComments as $comment) {
            $entityManager->remove($comment);
        }

        $trickAttachments->clear();
        $trickComments->clear();

        $entityManager->remove($trick);
        $entityManager->flush();

        $this->addFlash('success', 'La figure a été supprimé avec succès !');

        return $this->redirectToRoute('admin_trick_index');
    }
}
