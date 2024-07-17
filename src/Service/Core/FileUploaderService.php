<?php

declare(strict_types=1);

namespace App\Service\Core;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final class FileUploaderService
{
    public function __construct(
        private string $targetDirectory,
        private readonly SluggerInterface $slugger
    ) {
    }

    public function upload(UploadedFile $file, ?string $filename = null): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $safeFilename = $this->slugger->slug($originalFilename, '_');

        $fileName = $filename ?? $safeFilename . '_' . date('YmdHis') . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            //
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }

    public function setTargetDirectory(string $targetDirectory): FileUploaderService
    {
        $this->targetDirectory = $targetDirectory;

        return $this;
    }
}