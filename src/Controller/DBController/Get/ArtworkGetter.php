<?php


namespace App\Controller\DBController\Get;

use App\Entity\Artwork;
use App\Entity\User;
use App\Repository\ArtworkRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Exception;

class ArtworkGetter extends AbstractController
{

    /**
     * @param string $username
     * @param int $count
     * @param int $offset
     * @return Response
     * @Route("/api/user/{username}/artworks/{count}/{offset}", methods={"GET"})
     */
    public function getArtworksPerUser(string $username, int $count, int $offset)
    {
        //TODO: Insert Files into Array
        /** @var User $artist */
        $artist = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=>$username]);
        if(is_null($artist)) return new Response('{}',Response::HTTP_BAD_REQUEST);
        /** @var UserRepository $repo */
        $repo = $this->getDoctrine()->getManager()->getRepository(User::class);
        $artworks = $repo->getFirstNArtworks($count,$artist,$offset); //getFirstNArtworks is defined in UserRepository

        return new Response(json_encode($artworks), Response::HTTP_OK, ['filetype' => 'json']);
    }

    /**
     * @param int $count
     * @param int $offset
     * @return Response
     * @Route("/api/profile/artworks/{count}/{offset}", methods={"GET"})
     */
    public function getOwnArtworks(int $count, int $offset)
    {
        $user = $this->getUser();
        return $this->getArtworksPerUser($user, $count, $offset);
    }

    /**
     * @param int $count
     * @param int $offset
     * @return Response
     * @Route("/api/artworks/{count}/{offset}", methods={"GET"})
     */
    public function getAllArtworks(int $count, int $offset)
    {
        /** @var ArtworkRepository $repo */
        $repo = $this->getDoctrine()->getManager()->getRepository(Artwork::class);
        $artworks = $repo->getArtworks($count,$offset);
            //getArtworks is defined in ArtworkRepository
            //returns first n=10 artworks starting at m=0
        return new Response(json_encode($artworks),Response::HTTP_OK, ['filetype' => 'json']);
    }

    /**
     * @param int $id
     * @return Response
     * @Route("/api/artwork/{id}", methods={"GET"})
     */
    /*
     * HTTP_NOT_FOUND: Artwork doesn't exist in database
     * HTTP_OK: Artwork has successfully been returned (in return body)
     * HTTP_INTERNAL_SERVER_ERROR: Error in connection to database
     */
    public function getArtworkById(int $id)
    {
        try {
            //find Artwork in Database
            $artwork = $this->getDoctrine()->getManager()->getRepository(Artwork::class)->find($id);
            //returns 404 if not found, or artwork and 200 if found
            if(!$artwork) return new Response('{}', Response::HTTP_NOT_FOUND);
            return new Response(json_encode($artwork), Response::HTTP_OK);
        } catch (Exception $e) {
            return new Response('{}', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}