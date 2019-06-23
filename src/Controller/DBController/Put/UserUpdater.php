<?php


namespace App\Controller\DBController\Put;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use \Exception;
use Symfony\Component\Routing\Annotation\Route;

class UserUpdater extends AbstractController
{
    /**
     * @return Response
     * @Route("/api/update/email", methods={"POST"})
     */
    /*
     * HTTP_BAD_REQUEST: No email field in $_POST
     * HTTP_NOT_ACCEPTABLE: Email field does not contain a valid email
     * HTTP_INTERNAL_SERVER_ERROR: Something went wrong with handling the database
     *    Since flushing is the last step, this should not impact the integrity of the database
     * HTTP_OK: Email was successfully updated
     */
    public function updateEmail()
    {
        $data = $_POST;
        if(!isset($data['email']))
            return new Response('{}', Response::HTTP_BAD_REQUEST);
            //can't change email into nothing

        $email = $data['email'];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) //filters email to match standard email format
            return new Response('{"email":"' . $email . '"}', Response::HTTP_NOT_ACCEPTABLE);

        /** @var User $user */
        $user = $this->getUser(); //the user who changes their email

        try {
            //All the Database stuff to update the user in the database
            $mngr = $this->getDoctrine()->getManagerForClass(User::class);
            $user->setEmail($email);
            $mngr->persist($user);
            $mngr->flush();
            return new Response('{}', Response::HTTP_OK);
        } catch (Exception $e) { //catching because Database can always fail
            return new Response('{}', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}