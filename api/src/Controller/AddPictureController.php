<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Image;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ServerException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class AddPictureController
{
    private $em, $uploader;

    public function __construct(EntityManagerInterface $manager, FileUploader $uploader)
    {
        $this->em = $manager;
        $this->uploader = $uploader;
    }

    public function __invoke(Request $request)
    {
        $picture = new Image();
        $category = $request->get('category');
        if ($category == Image::CATEGORY_NEW) {
            $picture->category = Image::CATEGORY_NEW;
        } elseif ($category == Image::CATEGORY_POPULAR) {
            $picture->category = Image::CATEGORY_POPULAR;
        }
        $picture->description = $request->get('category');
        $picture->name = $request->get('name');
        /* @var $file UploadedFile */
        $file = $request->files->get('picture');
        if (!$file) {
            throw new HttpException(400, 'File not found');
        }
        $mime = $file->getMimeType();
        if ($mime != 'image/jpeg' && $mime != 'image/png') {
            throw new HttpException(400, 'Wrong mime type');
        }
        $filePath = $this->uploader->upload($file);
        if (!$filePath) {
            throw new HttpException(400, "Can't write image file");
        }
        $uploadedFile = new File();
        $uploadedFile->name = $file->getClientOriginalName();
        $uploadedFile->path = $filePath;
        $this->em->persist($uploadedFile);
        $this->em->flush();
        $picture->file = $uploadedFile;
        $this->em->persist($picture);
        $this->em->flush();

        return "Created";
    }
}
