<?php


namespace App\Controller\DBController\Post;


use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PasswordVerification extends AbstractController
{

    /**
     * @return Response
     * @Route("/api/user/login/password-verification", methods="POST")
     */
    /*
     * HTTP_BAD_REQUEST: username or password wasn't sent
     * HTTP_NOT_FOUND: user doesn't exist
     * HTTP_FORBIDDEN: wrong password
     * HTTP_OK: password correct
     */
    public function verifyLoginPassword()
    {
        if(!isset($_POST["username"]) || !isset($_POST["password"]))
            return new Response('{}', Response::HTTP_BAD_REQUEST, ['content-type' => 'json']);

        $username = $_POST["username"];
        $password = $_POST["password"];
        return $this->verifyPassword($username, $password);
    }

    /**
     * @param string $username
     * @param string $password
     * @return Response
     */
    public function verifyPassword(string $username, string $password)
    {
        $entityManager = $this->getDoctrine()->getRepository(User::class);
        $checkUser = $entityManager->findOneBy(['username' => $username]);
        if(!$checkUser)
            return new Response('{}', Response::HTTP_NOT_FOUND, ['content-type'=>'json']);
        else
        {
            if(password_verify($password, $checkUser->getPassword()))
                return new Response($checkUser->getId(), Response::HTTP_OK);
            else
                return new Response('{}', Response::HTTP_FORBIDDEN, ['content-type'=>'json']);
        }
    }
}