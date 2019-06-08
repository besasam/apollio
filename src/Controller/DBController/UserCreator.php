<?php


namespace App\Controller\DBController;


use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserCreator extends AbstractController
{
    /**
    * @Route("/user/new", methods="POST")
    */
    public function createNewUser()
    {
        $data = $_POST;
        if(isset($data['username']) && isset($data['password']) &&
            isset($data['passwordRepeat']) && isset($data['email']))
        {
            $validate = $this->validateNewUser($data['username'], $data['password'], $data['passwordRepeat']);
            if($validate !== Response::HTTP_OK)
                return new Response('{}', $validate, ['filetype' => 'json']);
            $entityManager = $this->getDoctrine()->getManager();
            $newUser = new User();
            $newUser->setUsername($data["username"]);
            $newUser->setPassword(password_hash($data["password"], PASSWORD_DEFAULT));
            $newUser->setEmail($data["email"]);
            $entityManager->persist($newUser);
            $entityManager->flush();
            return new Response("New user created!", Response::HTTP_CREATED);
        } else {
            return new Response('{}', Response::HTTP_BAD_REQUEST, ['filetype' => 'json']);
        }
    }

    private function validateNewUser($username, $password, $passwordRepeat)
    {
        if($password !== $passwordRepeat) return Response::HTTP_PRECONDITION_FAILED;
        $entityManager = $this->getDoctrine()->getRepository(User::class);
        $checkUser = $entityManager->findOneBy(['username' => $username]);
        if(is_null($checkUser)) return Response::HTTP_OK;
        else return Response::HTTP_FORBIDDEN;
    }
}