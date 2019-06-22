<?php


namespace App\Controller\DBController\Get;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileGetter extends AbstractController
{
    /**
     * @param string $username
     * @param int $offset
     * @return Response
     * @Route("/api/user/{username}/profile/{offset}", methods={"GET"})
     */
    public function getProfile(string $username, int $offset)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        /** @var User $user */
        $user = $repo->findOneBy(['username'=>$username]);
        $subCount = $user->getSubscribers()->count(); //Number of Subscribers to show on the subscribe-button

        $ag = new ArtworkGetter();
        $artworks = json_decode($ag->getArtworksPerUser($username,10,$offset)->getContent())["artworks"];
            //all artworks of the user that are shown on one page
        $returnArray = ["subCount" => $subCount, "artworks" => $artworks];
        return new Response(json_encode($returnArray), Response::HTTP_OK, ['filetyoe'=>'json']);
            //The attributes of the returned json object can be used to build the page in FE
    }

    /**
     * @param string $username
     * @return Response
     * @Route("/api/user/{username}/profile", methods={"GET"})
     */
    public function getDefaultProfile(string $username)
    {
        return $this->getProfile($username, 0);
    }
}