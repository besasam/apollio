<?php


namespace App\Controller\DBController;

use App\Entity\Artwork;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtworkGetter extends AbstractController
{

    private $data;

    /**
     * @Route("/api/user/{username}/artworks", methods={"GET"})
     */
    public function getArtworksPerUser(string $username)
    {
        //TODO: Insert Files into Array
        $artist = $this->getDoctrine()->getRepository(User::class)->findOneBy(['usename'=>$username]);
        if(is_null($artist)) return new Response('{}',Response::HTTP_BAD_REQUEST);

        $artworks = $this->getDoctrine()->getManager()->getRepository(User::class)->
            getFirstNArtworks(10,$artist); //getFirstNArtworks is defined in UserRepository

        return new Response(json_encode($artworks), Response::HTTP_OK, ['filetype' => 'json']);
    }

    /**
     * @Route("/api/profile/artworks", methods={"GET"})
     */
    public function getOwnArtworks()
    {
        $user = $this->getUser();
        return $this->getArtworksPerUser($user);
    }
}