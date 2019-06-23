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
     * @Route("/api/user/{username}/profile/{offset}", methods={"GET"}, defaults={"offset"=0})
     */
    public function getProfile($username, $offset, $doctrine = NULL)
    {
        /** @var User $user */
        if(!$doctrine) {
            $doctrine = $this->getDoctrine();
        }
        $user = $doctrine->getRepository(User::class)->findOneBy(['username'=>$username]);
        try {
            $subCount = $user->getSubscribers()->count(); //Number of Subscribers to show on the subscribe-button
        } catch(Exception $e) {
            $subCount = 0;
        }
        try {
            $awCount = $user->getArtworks()->count(); //Number of artworks to construct the pagination on profile
            //$artworks = json_decode(file_get_contents('/api/user/'.$username.'/artworks/10/'.$offset)); //all artworks of the user that are shown on one page
            $artworks = $doctrine->getManager()->getRepository(User::class)->getFirstNArtworks(10,$user,$offset);
            $artworksArr = [];
            foreach ($artworks as $artwork) {
                $artworksArr[] = ["id" => $artwork->getId(),"title" => $artwork->getTitle(), "file" => $artwork->getFilelink(), "date" => $artwork->getCreatedAt()];
            }
        } catch(Exception $e) {
            $awCount = 0;
            $artworks = [];
        }
        $returnArray = ["subCount" => $subCount, "awCount" => $awCount, "artworks" => $artworksArr];
        return new Response(json_encode($returnArray), Response::HTTP_OK, ['filetype'=>'json']);
            //The attributes of the returned json object can be used to build the page in FE
    }

    /*
    /**
     * @param string $username
     * @return Response
     * @Route("/api/user/{username}/profile", methods={"GET"})
     */ /*
    public function getDefaultProfile(string $username)
    {
        return $this->getProfile($username, 0);
    }*/
}