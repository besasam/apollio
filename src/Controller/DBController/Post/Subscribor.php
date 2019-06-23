<?php


namespace App\Controller\DBController\Post;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use \Exception;
use Symfony\Component\Routing\Annotation\Route;

class Subscribor extends AbstractController
{
    /**
     * @param User $user
     * @param User $subscribed
     * @return Response
     */
    /*
     * HTTP_FORBIDDEN: Tried to subscribe to himself
     * HTTP_BAD_REQUEST: User does not exist
     * HTTP_INTERNAL_SERVER_ERROR: Subscription could not be added. Try again or inform an administrator.
     * HTTP_OK: Subscription successfully added; Returns sub-number for subscribed user
     */
    public function subscribe(User $user, User $subscribed)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $subscribed->addSubscriber($user);
            $em->persist($subscribed);
            $em->flush();
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //return new Response("{'subscribers':". $subscribed->getSubscribers()->count() ."}",
        //    Response::HTTP_OK, ['filetype'=>'json']);
        return new RedirectResponse("/u/" . $subscribed->getUsername());
    }

    /**
     * @param User $user
     * @param User $subscribed
     * @return Response
     */
    /*
     * HTTP_FORBIDDEN: You can't sub yourself
     * HTTP_BAD_REQUEST: User doesn't exist
     * HTTP_INTERNAL_SERVER_ERROR: Database error OR Subscription doesn't exist
     * HTTP_OK: Successfully removed
     */
    public function unsubscribe(User $user, User $subscribed)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $subscribed->removeSubscriber($user);
            $em->persist($subscribed);
            $em->flush();
        } catch (Exception $e) {
            return new Response($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //return new Response("{'subscribers':". $subscribed->getSubscribers()->count() ."}",
        //    Response::HTTP_OK, ['filetype'=>'json']);
        return new RedirectResponse("/u/" . $subscribed->getUsername());
    }

    /**
     * @param $username
     * @return Response
     * @Route("api/user/{username}/togglesubscribe", methods={"GET"})
     */
    public function toggleSubscribe($username)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        /** @var User $user */
        $user = $this->getUser();
        if($user->getUsername() == $username)
            return new Response('{}', Response::HTTP_FORBIDDEN, ['filetype'=>'json']);
        /** @var User $subscribed */
        $subscribed = $repo->findOneBy(['username'=>$username]);
        if(!$subscribed) return new Response('{}', Response::HTTP_BAD_REQUEST);

        if($subscribed->getSubscribers()->contains($user))
            return $this->unsubscribe($user,$subscribed);
        else
            return $this->subscribe($user,$subscribed);
    }
}