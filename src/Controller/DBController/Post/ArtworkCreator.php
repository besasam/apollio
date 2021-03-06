<?php


namespace App\Controller\DBController\Post;

use \Exception;
use App\Entity\Artwork;
use App\Entity\User;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtworkCreator extends AbstractController
{
    /*
     * Required: title, file
     */
    /**
     * @Route("/api/artwork/new", methods="POST")
     * @return Response
     */
    public function createArtwork()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        /** @var User $user */
        $user = $this->getUser();
        $data = $_POST;
        $newArtwork = new Artwork();
        $file = $_FILES["file"];
        $fileExtension = substr($file["name"], strrpos($file["name"], '.') + 1);
        if(!in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
            return new Response(
                '{fileExtension:'.$fileExtension.'}',
                Response::HTTP_UNSUPPORTED_MEDIA_TYPE,
                ['filetype'=>'json']
            );

        $fileName = md5(uniqid()) . '.' . $fileExtension; //unique file name

        try {
            /*$file["tmp_name"]->move(
                $this->getParameter('file_directory'),
                $fileName
            );*/
            copy($file["tmp_name"], $this->getParameter('upload_directory') . '/' . $fileName);
        } catch (FileException $e) {
            return new Response('{"errorField":"file"}', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $datetime = date_create();

        try{
            $newArtwork->setCreatedAt($datetime);
        } catch (Exception $e) {
            return new Response(
                '{"errorField":"datetime"}',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['filetype'=>'json']
            );
        }

        $newArtwork->setTitle($data['title']);
        $newArtwork->setFilelink($fileName);
        $newArtwork->setArtist($user);

        try{
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newArtwork);
            $entityManager->flush();
        } catch (ORMException $e) {
            unlink($this->getParameter('upload_directory') . '/' . $fileName);
            return new Response(
                '{}',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['filetype'=>'json']
            );
        }

        //return new Response($newArtwork->getId(), Response::HTTP_OK);
        return new RedirectResponse("/upload/success/" . $newArtwork->getId());
    }
}