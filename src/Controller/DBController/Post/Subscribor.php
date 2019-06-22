<?php


namespace App\Controller\DBController\Post;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use \Exception;
use Symfony\Component\Routing\Annotation\Route;

class Subscribor extends AbstractController
{
    /**
     * @param string $username
     * @return Response
     * @Route("/api/user/{username}/subscribe", methods="POST")
     */
    /*
     * HTTP_FORBIDDEN: Tried to subscribe to himself
     * HTTP_BAD_REQUEST: User does not exist
     * HTTP_INTERNAL_SERVER_ERROR: Subscription could not be added. Try again or inform an administrator.
     * HTTP_OK: Subscription successfully added; Returns sub-number for subscribed user
     */
    public function subscribe(string $username)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);

        $user = $this->getUser();
        if($user->getUsername() == $username)
            return new Response('{}', Response::HTTP_FORBIDDEN, ['filetype'=>'json']);

        $subscribed = $repo->findOneBy(['username'=>$username]);
        if(!$subscribed) return new Response('{}', Response::HTTP_BAD_REQUEST);

        try {
            $user->addSubscription($subscribed);
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response("{'subscribers':". $subscribed->getSubscribers()->count() ."}",
            Response::HTTP_OK, ['filetype'=>'json']);
    }
}