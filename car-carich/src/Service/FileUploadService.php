<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadService
{
    public const CAR_IMAGES_PATH = '/uploads/car/images/';
    public const STAMP_ICON_PATH = '/uploads/stamp/icon/';
    public const CONTRACT_PATH = '/uploads/contract/';

    public function __construct(
        protected SluggerInterface $slugger,
        protected Filesystem       $fileSystem,
        protected string           $dirPublic
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function upload(UploadedFile $file, $targetDirectory): string
    {
        $fullTargetDirectory = $this->dirPublic . $targetDirectory;
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid('', true) . '.' . $file->guessExtension();
        $filePath = $targetDirectory . $fileName;
        try {
            if (!$this->fileSystem->exists($fullTargetDirectory)) {
                $this->fileSystem->mkdir($fullTargetDirectory);
            }
            $file->move($fullTargetDirectory, $fileName);
        } catch (FileException $e) {
            throw new \Exception($e->getMessage());
        }

        return $filePath;
    }

    public function deleteFile($fileName): bool
    {
        $fullFilePath = $this->dirPublic . $fileName;
        if ($this->fileSystem->exists($fullFilePath)) {
            $this->fileSystem->remove($fullFilePath);
        }
        return true;
    }
}