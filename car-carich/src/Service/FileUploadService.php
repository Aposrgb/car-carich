<?php

namespace App\Service;

use App\Helper\Exception\ApiException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploadService
{
    public const CAR_IMAGES_PATH = '/uploads/car/images/';
    public const STAMP_ICON_PATH = '/uploads/stamp/icon/';
    public const CONTRACT_PATH = '/uploads/contract/';
    public const TEMP_PATH = '/uploads/';
    public const MIME_TYPES = [
        'image/jpeg' => 'jpg',
        'image/jpg' => 'jpg',
        'image/webp' => 'webp',
        'image/png' => 'png',
        'image/svg+xml' => 'svg'
    ];

    public function __construct(
        protected SluggerInterface $slugger,
        protected Filesystem       $fileSystem,
        protected string           $dirPublic
    )
    {
    }

    public function getUniqueFileName(): string
    {
        return str_replace(' ', '_',
            microtime() . '_' . uniqid(prefix: true, more_entropy: true)
        );
    }


    public function saveOutput(string $fileName, string $output, $targetDirectory): string
    {
        $path = $targetDirectory . $fileName;
        $targetDirectory = $this->dirPublic . $targetDirectory;
        $this->checkDirectory($targetDirectory);
        if (!file_put_contents($targetDirectory . $fileName, $output)) {
            throw new ApiException('Ошибка при загрузке файла в хранилище');
        }

        return $path;
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
            $this->checkDirectory($fullTargetDirectory);
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

    private function checkDirectory($targetDirectory): void
    {
        if (!$this->fileSystem->exists($targetDirectory)) {
            $this->fileSystem->mkdir($targetDirectory);
        }
    }

}