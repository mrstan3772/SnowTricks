<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    public function __construct(Filesystem $publicUploadsFilesystem, Filesystem $privateUploadsFilesystem, RequestStackContext $requestStackContext, LoggerInterface $logger, string $uploadedAssetsBaseUrl)
    {
        $this->filesystem = $publicUploadsFilesystem;
        $this->privateFilesystem = $privateUploadsFilesystem;
        $this->requestStackContext = $requestStackContext;
        $this->logger = $logger;
        $this->publicAssetBaseUrl = $uploadedAssetsBaseUrl;
    }

    public function uploadTrickReference(File $file, $location, $public = true): string
    {
        return $this->uploadFile($file, $location, $public);
    }

    public function getPublicPath(string $path): string
    {
        // needed if you deploy under a subdirectory
        return $this->requestStackContext
            ->getBasePath().$this->publicAssetBaseUrl.'/'.$path;
    }

    private function uploadFile(File $file, string $directory, bool $isPublic)
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }
        $newFilename = Urlizer::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)).'-'.uniqid().'.'.$file->guessExtension();

        $filesystem = $isPublic ? $this->filesystem : $this->privateFilesystem;

        $stream = fopen($file->getPathname(), 'r');
        $result = $filesystem->writeStream(
            $directory.'/'.$newFilename,
            $stream
        );

        if ($result === false) {
            throw new \Exception(sprintf('Could not write uploaded file "%s"', $newFilename));
        }

        if (is_resource($stream)) {
            fclose($stream);
        }

        return $newFilename;
    }
}