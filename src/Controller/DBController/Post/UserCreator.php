<?php


namespace App\Controller\DBController;


use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreator extends AbstractController
{

    private $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     * @Route("/api/user/new", methods="POST")
     */
    /*
     * HTTP_CREATED: User was created successfully
     * HTTP_BAD_REQUEST: Not All Required Fields are set
     * HTTP_PRECONDITION_FAILED: Password and repeat-password don't match
     * HTTP_NOT_ACCEPTABLE: Username contains forbidden symbols
     * HTTP_FORBIDDEN: Username is already taken
     */
    public function createNewUser(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $data = $_POST;
        if(isset($data['username']) && isset($data['password']) &&
            isset($data['passwordRepeat']) && isset($data['email']))
        {
            $validate = $this->validateNewUser($data['username'], $data['email'], $data['password'], $data['passwordRepeat']);
            if($validate !== Response::HTTP_OK)
                return new Response('{}', $validate, ['filetype' => 'json']);
            $entityManager = $this->getDoctrine()->getManager();
            $newUser = new User();
            $newUser->setUsername($data["username"]);
            $newUser->setPassword($this->passwordEncoder->encodePassword($newUser, $data["password"]));
            $newUser->setEmail($data["email"]);
            $entityManager->persist($newUser);
            $entityManager->flush();
            return new Response("New user created!", Response::HTTP_CREATED);
        } else {
            return new Response('{}', Response::HTTP_BAD_REQUEST, ['filetype' => 'json']);
        }
    }

    /**
     * @param $username
     * @param $email
     * @param $password
     * @param $passwordRepeat
     * @return int
     */
    private function validateNewUser($username, $email, $password, $passwordRepeat)
    {
        if($password !== $passwordRepeat) return Response::HTTP_PRECONDITION_FAILED;
        $entityManager = $this->getDoctrine()->getRepository(User::class);
        $checkUser = $entityManager->findOneBy(['email' => $email]);
        if (!preg_match("#^[a-zA-Z0-9]+$#", $username))
            return Response::HTTP_NOT_ACCEPTABLE;
        if(is_null($checkUser)) return Response::HTTP_OK;
        else return Response::HTTP_FORBIDDEN;
    }
}