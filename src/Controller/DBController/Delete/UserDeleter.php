<?php


namespace App\Controller\DBController\Delete;


use App\Entity\Artwork;
use App\Entity\User;
use \Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserDeleter extends AbstractController
{
    /**
     * @return Response
     * @Route("/api/user/delete", methods={"POST"})
     */
    /*
     * HTTP_BAD_REQUEST usernameConfirmation was not submitted
     * HTTP_FORBIDDEN usernameConfirmation is not the same as the own Username
     * HTTP_INTERNAL_SERVER_ERROR some step in the deletion process failed. Some deletions may have been executed.
     *    This should always lead to a retry, since the User is now possibly corrupted.
     * HTTP_OK The deletion was successful.
     */
    public function deleteCurrentUser()
    {
        $data = $_POST;
        if(!isset($data["usernameConfirmation"])) return new Response('{}', Response::HTTP_BAD_REQUEST);
        $confirmation = $data["usernameConfirmation"];
        //field in deletion form, to make sure one actually wants to delete their account...

        /** @var User $user */
        $user = $this->getUser();
        $username = $user->getUsername();
        if($username !== $confirmation) return new Response('{}',Response::HTTP_FORBIDDEN);
        //...and its verification

        $mngr = $this->getDoctrine()->getManager();
        try{
            $artworks = $mngr->getRepository(Artwork::class)->findBy(["artist"=>$user]);
            /** @var Artwork $aw */
            foreach($artworks as $aw) //delete all the artworks that belong to this user
            {
                unlink($aw->getFilelink());
                $mngr->remove($aw);
            }

            $subscribers = $user->getSubscribers();
            foreach($subscribers as $sub) //remove all subscribers...
                $user->removeSubscriber($sub);

            $subscriptions = $user->getSubscriptions();
            foreach($subscriptions as $sub) //...and all subscriptions
                $user->removeSubscription($sub);

            $mngr->remove($user); //Then, remove the user themselves

        } catch (Exception $e) {
            return new Response('{}', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new Response('{}', Response::HTTP_OK);
    }
}