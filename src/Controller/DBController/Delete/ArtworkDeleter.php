<?php


namespace App\Controller\DBController\Delete;


use App\Entity\Artwork;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use \Exception;
use Symfony\Component\Routing\Annotation\Route;

class ArtworkDeleter extends AbstractController
{
    /**
     * @param int $id
     * @return Response
     * @Route("/api/artwork/{id}/delete", methods={"DELETE"})
     */
    /*
     * HTTP_NOT_FOUND: Artwork id doesn't exist in Database
     * HTTP_METHOD_NOT_ALLOWED: User is not artist of the artwork
     * HTTP_INTERNAL_SERVER_ERROR: Database failed deleting properly.
     *    This should result in a retry, because the data might now be corrupted
     * HTTP_OK: Artwork successfully deleted
     */
    public function deleteArtwork(int $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $user = $this->getUser(); //later needed to check if it's the artwork's creator who wants to delete it

        $repo = $this->getDoctrine()->getRepository(Artwork::class);
        /** @var Artwork $artwork */
        $artwork = $repo->find($id);
        if(!$artwork) return new Response('{}',Response::HTTP_NOT_FOUND);
            //not found if artwork id doesn't exist
        if($artwork->getArtist() !== $user) return new Response('{}', Response::HTTP_METHOD_NOT_ALLOWED);
            //not allowed if user is not the artist
        try {
            unlink($this->getParameter('upload_directory'). '/' . $artwork->getFilelink());
            $mngr = $this->getDoctrine()->getManager();
            $artwork->getArtist()->removeArtwork($artwork);
            $mngr->remove($artwork);
            $mngr->flush();
        } catch (Exception $e) {
            return new Response('{}', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new Response('{}', Response::HTTP_OK);
    }
}