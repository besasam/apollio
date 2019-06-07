<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\ORMException;
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
        $data = $_POST;
        $validate = $this->validateNewUser($data['username'], $data['password'], $data['passwordRepeat']);
        if($validate !== Response::HTTP_OK)
            return new Response('{}', $validate, ['filetype' => 'json']);
        $entityManager = $this->getDoctrine()->getManager();
        try {
            $newUser = new User();
            $newUser->setUsername($data["username"]);
            $newUser->setPassword(password_hash($data["password"], PASSWORD_DEFAULT));
            $newUser->setEmail($data["email"]);
            $entityManager->persist($newUser);
            $entityManager->flush();
        } catch (ORMException $e){
            return new Response($e, Response::HTTP_BAD_REQUEST);
        }
        return new Response("New user created!", Response::HTTP_CREATED);
    }

    private function validateNewUser($username, $password, $passwordRepeat)
    {
        if($password !== $passwordRepeat) return Response::HTTP_PRECONDITION_FAILED;
        $entityManager = $this->getDoctrine()->getRepository(User::class);
        $checkUser = $entityManager->findOneBy(['username' => $username]);
        if(is_null($checkUser)) return Response::HTTP_OK;
        else return Response::HTTP_FORBIDDEN;
    }

    /**
     * @Route("/user/login/password-validation", methods="POST")
     */
    public function passwordValidation()
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