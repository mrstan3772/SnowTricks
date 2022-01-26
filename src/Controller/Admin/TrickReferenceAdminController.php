<?php

namespace App\Controller\Admin;

use App\Entity\TrickAttachment;
use App\Entity\Trick;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin/trick'), IsGranted('ROLE_ADMIN')]
class TrickReferenceAdminController extends AbstractController
{
    #[Route('/{id}/references', methods: ['POST'], name: 'admin_trick_add_reference')]
    #[IsGranted('edit', subject: 'trick', message: 'Les ressources connexes ne peuvent être ajoutés que par leurs auteurs.')]
    public function uploadTrickReference(Trick $trick, Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $entityManager)
    {
        $uploadedFile = $request->files->get('reference');

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
        $entityManager->flush();

        return $this->json(
            $trickReference,
            201,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    #[Route('/{id}/references', methods: ['GET'], name: 'admin_trick_list_references')]
    #[IsGranted('edit', subject: 'trick', message: 'Les ressources connexes ne peuvent être modifiés que par leurs auteurs.')]
    public function getTrickReferences(Trick $trick)
    {
        return $this->json(
            $trick->getTrickAttachments(),
            200,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    #[Route('/references/{id}', methods: ['DELETE'], name: 'admin_trick_delete_reference')]
    public function deleteTrickReference(TrickAttachment $reference, UploaderHelper $uploaderHelper, EntityManagerInterface $entityManager)
    {
        $trick = $reference->getTaTrick();
        $this->denyAccessUnlessGranted('edit', $trick);

        $entityManager->remove($reference);
        $entityManager->flush();

        $uploaderHelper->deleteFile($reference->getFilePath(), true);

        return new Response(null, 204);
    }

    #[Route('/references/{id}', methods: ['PUT'], name: 'admin_trick_update_reference')]
    public function updateArticleReference(TrickAttachment $reference, EntityManagerInterface $entityManager, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        $trick = $reference->getTaTrick();
        $this->denyAccessUnlessGranted('edit', $trick);
        $serializer->deserialize(
            $request->getContent(),
            TrickAttachment::class,
            'json',
            [
                'object_to_populate' => $reference,
                'groups' => ['input']
            ]
        );

        $violations = $validator->validate($reference);
        if ($violations->count() > 0) {
            return $this->json($violations, 400);
        }

        $entityManager->persist($reference);
        $entityManager->flush();
        return $this->json(
            $reference,
            200,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    #[Route('/references/{id}/download', methods: ['GET'], name: 'admin_trick_download_reference')]
    public function downloadTrickReference(TrickAttachment $reference, UploaderHelper $uploaderHelper)
    {
        $trick = $reference->getTaTrick();
        $this->denyAccessUnlessGranted('edit', $trick);
        $response = new StreamedResponse(
            function () use ($reference, $uploaderHelper) {
                $outputStream = fopen('php://output', 'wb');
                $fileStream = $uploaderHelper->readStream($reference->getFilePath(), true);
                stream_copy_to_stream($fileStream, $outputStream);
            }
        );

        $response->headers->set('Content-Type', $reference->getTaMimeType());
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $reference->getTaOriginalFilename()
        );

        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }
}
