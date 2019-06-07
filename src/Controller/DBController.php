<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DBController extends AbstractController
{
    /**
     * @Route("/user/new", methods="POST")
     */
    public function createNewUser()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $data = $_POST;
        //TODO - validierung
        $newUser = new User();
        $newUser->setUsername($data["username"]);
        $newUser->setPassword(password_hash($data["password"], PASSWORD_DEFAULT));
        $newUser->setEmail($data["email"]);
        $entityManager->persist($newUser);
        $entityManager->flush();
        return new Response("New user created!", Response::HTTP_CREATED);
    }

    /**
     * @Route("/user/login/password-validation", methods="POST")
     */
    public function passwordValidation()
    {
        if(is_null($_POST["username"]) || is_null($_POST["password"]))
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