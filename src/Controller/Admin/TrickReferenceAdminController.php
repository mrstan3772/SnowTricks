<?php

namespace App\Controller\Admin;

use App\Entity\TrickAttachment;
use App\Entity\Trick;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin/trick'), IsGranted('ROLE_ADMIN')]
class TrickReferenceAdminController extends AbstractController
{
    /**
     * @Route("/{id}/references", name="admin_trick_add_reference", methods={"POST"})
     * @IsGranted("edit", subject="trick")
     */
    public function uploadTrickReference(Trick $trick, Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $uploadedFile = $request->files->get('reference');

        $violations = $validator->validate(
            $uploadedFile,
            [
                new NotBlank(
                    [
                        'message' => 'Veuillez sélectionner un fichier à télécharger'
                    ]
                ),
                new File(
                    [
                        'maxSize' => '250M',
                        'mimeTypes' => [
                            'image/png',
                            'image/webp',
                            'image/gif',
                            'image/jpeg',
                            'video/webm',
                            'video/3gpp',
                            'video/3gpp2',
                            'video/mpeg',
                            'video/ogg',
                            'video/mp4'
                        ]
                    ]
                )
            ]
        );
        if ($violations->count() > 0) {
            $violation = $violations[0];
            $this->addFlash('error', $violation->getMessage());
            return $this->redirectToRoute(
                'admin_trick_edit',
                [
                    'trick_slug' => $trick->getTrickSlug(),
                ]
            );
        }

        $trickType = $this->defType($uploadedFile);

        $location = $trickType === 'image' ? UploaderHelper::TRICK_IMAGE_REFERENCE : UploaderHelper::TRICK_VIDEO_REFERENCE;
        
        $filename = $uploaderHelper->uploadTrickReference($uploadedFile, $location, true);

        $trickReference = new TrickAttachment($trick);
        $trickReference->setTaFilename($filename);
        $trickReference->setTaType($trickType);
        $trickReference->setTaOriginalFilename($uploadedFile->getClientOriginalName() ?? $filename);
        $trickReference->setTaMimeType($uploadedFile->getMimeType() ?? 'application/octet-stream');

        $entityManager->persist($trickReference);
        $entityManager->flush();

        return $this->redirectToRoute(
            'admin_trick_edit',
            [
                'trick_slug' => $trick->getTrickSlug(),
            ]
        );
    }

    private function defType($file): String
    {
        $image_type = [
            'image/png',
            'image/webp',
            'image/gif',
            'image/jpeg',
        ];

        $video_type = [
            'video/webm',
            'video/3gpp',
            'video/3gpp2',
            'video/mpeg',
            'video/ogg',
            'video/mp4'
        ];

        if (in_array($file->getMimeType(), $image_type)) {
            return 'image';
        }

        if (in_array($file->getMimeType(), $video_type)) {
            return 'video';
        }
    }
}
