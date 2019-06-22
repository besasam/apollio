<?php


namespace App\Controller\DBController;

use App\Entity\Artwork;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $artist = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=>$username]);
        if(is_null($artist)) return new Response('{}',Response::HTTP_BAD_REQUEST);

        $artworks = $this->getDoctrine()->getManager()->getRepository(User::class)->
            getFirstNArtworks($count,$artist,$offset); //getFirstNArtworks is defined in UserRepository

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
        $artworks = $this->getDoctrine()->getManager()->getRepository(Artwork::class)->
            getArtworks($count,$offset); //getArtworks is defined in ArtworkRepository, returns first n=10 artworks starting at m=0
        return new Response(json_encode($artworks),Response::HTTP_OK, ['filetype' => 'json']);
    }
}