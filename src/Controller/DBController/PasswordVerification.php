<?php


namespace App\Controller\DBController;


use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PasswordVerification extends AbstractController
{

    /**
     * @Route("/api/user/login/password-verification", methods="POST")
     */
    public function passwordVerification()
    {
        if(!isset($_POST["username"]) || !isset($_POST["password"]))
        {
            return new Response('{}', Response::HTTP_BAD_REQUEST,
                ['content-type' => 'json']);
        } else {
            $username = $_POST["username"];
            $password = $_POST["password"];
            $entityManager = $this->getDoctrine()->getRepository(User::class);
            $checkUser = $entityManager->findOneBy(['username' => $username]);
            if(!$checkUser)
                return new Response('{}', Response::HTTP_NOT_FOUND,
                    ['content-type'=>'json']);
            else
            {
                if(password_verify($password, $checkUser->getPassword()))
                    return new Response($checkUser->getId(), Response::HTTP_OK);
                else
                    return new Response('{}', Response::HTTP_FORBIDDEN,
                        ['content-type'=>'json']);
            }
        }
    }
}