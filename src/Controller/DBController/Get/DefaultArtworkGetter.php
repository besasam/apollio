<?php


namespace App\Controller\DBController\Get;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultArtworkGetter
{
    /**
     * @param string $username
     * @param int $count
     * @return Response
     * @Route("/api/user/{username}/artworks/{count}", methods={"GET"})
     */
    public function getFirstArtworksPerUser(string $username, int $count)
    {
        $aw = new ArtworkGetter();
        return $aw->getArtworksPerUser($username, $count, 0);
    }

    /**
     * @param string $username
     * @return Response
     * @Route("/api/user/{username}/artworks", methods={"GET"})
     */
    public function getDefaultArtworksPerUser(string $username)
    {
        $aw = new ArtworkGetter();
        return $aw->getArtworksPerUser($username, 10, 0);
    }

    /**
     * @param int $count
     * @return Response
     * @Route("/api/profile/artworks/{count}", methods={"GET"})
     */
    public function getFirstOwnArtworks(int $count)
    {
        $aw = new ArtworkGetter();
        return $aw->getOwnArtworks($count, 0);
    }

    /**
     * @return Response
     * @Route("/api/profile/artworks", methods={"GET"})
     */
    public function getDefaultOwnArtworks()
    {
        $aw = new ArtworkGetter();
        return $aw->getOwnArtworks(10, 0);
    }

    /**
     * @param int $count
     * @return Response
     * @Route("/api/artworks/{count}", methods={"GET"})
     */
    public function getFirstArtworks(int $count)
    {
        $aw = new ArtworkGetter();
        return $aw->getAllArtworks($count, 0);
    }

    /**
     * @return Response
     * @Route("/api/artworks", methods={"GET"})
     */
    public function getDefaultArtworks()
    {
        $aw = new ArtworkGetter();
        return $aw->getAllArtworks(10,0);
    }
}