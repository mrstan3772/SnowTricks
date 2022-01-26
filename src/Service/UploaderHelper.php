<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File as ConstraintsFile;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UploaderHelper
{
    const ARTICLE_IMAGE = 'article_image';
    const TRICK_IMAGE_REFERENCE = 'images';
    const TRICK_VIDEO_REFERENCE = 'videos';

    private $filesystem;

    private $privateFilesystem;

    private $requestStackContext;

    private $logger;

    private $publicAssetBaseUrl;

    public function __construct(Filesystem $publicUploadsFilesystem, Filesystem $privateUploadsFilesystem, RequestStackContext $requestStackContext, LoggerInterface $logger, string $uploadedAssetsBaseUrl, ValidatorInterface $validator)
    {
        $this->filesystem = $publicUploadsFilesystem;
        $this->privateFilesystem = $privateUploadsFilesystem;
        $this->requestStackContext = $requestStackContext;
        $this->logger = $logger;
        $this->publicAssetBaseUrl = $uploadedAssetsBaseUrl;
        $this->validator = $validator;
    }

    public function uploadTrickReference(File $file, $location, $public = true): string
    {
        return $this->uploadFile($file, $location, $public);
    }

    public function getPublicPath(string $path): string
    {
        // needed if you deploy under a subdirectory
        return $this->requestStackContext
            ->getBasePath() . $this->publicAssetBaseUrl . '/' . $path;
    }

    /**
     * @return resource
     */
    public function readStream(string $path, bool $isPublic = true)
    {
        $filesystem = $isPublic ? $this->filesystem : $this->privateFilesystem;
        $resource = $filesystem->readStream($path);
        if ($resource === false) {
            throw new \Exception(sprintf('Erreur lors de l\'ouverture du flux pour "%s"', $path));
        }
        return $resource;
    }


    private function uploadFile(File $file, string $directory, bool $isPublic)
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }
        $newFilename = Urlizer::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)) . '-' . uniqid() . '.' . $file->guessExtension();

        $filesystem = $isPublic ? $this->filesystem : $this->privateFilesystem;

        $stream = fopen($file->getPathname(), 'r');
        $result = $filesystem->writeStream(
            $directory . '/' . $newFilename,
            $stream
        );

        if ($result === false) {
            throw new \Exception(sprintf('Impossible d\'écrire le fichier téléchargé : "%s"', $newFilename));
        }

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $newFilename;
    }

    public function deleteFile(string $path, bool $isPublic = true)
    {
        $filesystem = $isPublic ? $this->filesystem : $this->privateFilesystem;
        $result = $filesystem->delete($path);
        if ($result === false) {
            throw new \Exception(sprintf('Erreur lors de la suppression de "%s"', $path));
        }
    }

    public function validateRefFile(File $uploadedFile)
    {
        $violations = $this->validator->validate(
            $uploadedFile,
            [
                new NotBlank(
                    [
                        'message' => 'Veuillez sélectionner un fichier à télécharger'
                    ]
                ),
                new ConstraintsFile(
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

        return $violations;
    }

    public function defType($file): String
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
