<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class AbstractFileUploader {

    private string $projectDir;
    private string $targetDirectory;
    private SluggerInterface $slugger;

    public function __construct($targetDirectory, $projectDir, SluggerInterface $slugger) {
        $this->targetDirectory = $targetDirectory;
        $this->projectDir = $projectDir;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file): string {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getFinalTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // TODO
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getFinalTargetDirectory(): string {

        return $this->projectDir . " /public " . $this->targetDirectory;
    }

}