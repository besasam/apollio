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
     * @Route("/user/login/password-validation", methods="GET")
     */
    public function passwordValidation($data)
    {
        if(is_null($data["username"]) || is_null($data["password"]))
        {
            return new Response("Missing Username or Password", Response::HTTP_BAD_REQUEST);
        } else {
            $username = $data["username"];
            $password = $data["password"];
            $entityManager = $this->getDoctrine()->getRepository(User::class);
            $checkUser = $entityManager->findOneBy(['username' => $username]);
            if(!$checkUser)
                return new Response("User " . $username . " does not exist.");
            else
            {
                if(password_verify($password, $checkUser->getPassword()))
                    return new Response("Password Correct", Response::HTTP_ACCEPTED);
                else
                    return new Response("Username or Password wrong", Response::HTTP_NOT_ACCEPTABLE);
            }
        }
    }
}