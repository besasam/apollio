<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        // TODO - validierung und so
        $newUser = new User();
        $newUser->setUsername($data["username"]);
        $newUser->setPassword(password_hash($data["password"], PASSWORD_DEFAULT));
        $newUser->setEmail($data["email"]);
        $entityManager->persist($newUser);
        $entityManager->flush();
        return new Response("New user created!", Response::HTTP_OK);
    }
}