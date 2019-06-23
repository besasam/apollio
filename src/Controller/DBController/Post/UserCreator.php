<?php


namespace App\Controller\DBController\Post;


use App\Entity\User;
use \Exception;
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
     * HTTP_INTERNAL_SERVER_ERROR: Error while handling database
     * Other response: See $this->validateNewUser
     */
    public function createNewUser(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $data = $_POST;
        if(isset($data['username']) && isset($data['password']) &&
            isset($data['passwordRepeat']) && isset($data['email']))
        {
            $validate = $this->
                validateNewUser($data['username'], $data['email'], $data['password'], $data['passwordRepeat']);
            if($validate !== Response::HTTP_OK)
                return new Response('{}', $validate, ['filetype' => 'json']);

            //Creating the user object, since we now know that the user data is valid
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $newUser = new User();
                $newUser->setUsername($data["username"]);
                $newUser->setPassword($this->passwordEncoder->encodePassword($newUser, $data["password"]));
                $newUser->setEmail($data["email"]);
                $entityManager->persist($newUser);
                $entityManager->flush();
            } catch (Exception $e) {
                return new Response($e, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
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
    /*
     * This function validates the User data
     * HTTP_PRECONDITION_FAILED: The password does not match with the repeated Password
     * HTTP_NOT_ACCEPTABLE: Email does not match standard email pattern
     * HTTP_FORBIDDEN: Username contains forbidden symbols
     * HTTP_CONFLICT: Email or Username is already taken
     * HTTP_OK: User data is valid
     */
    private function validateNewUser($username, $email, $password, $passwordRepeat)
    {
        if($password !== $passwordRepeat) return Response::HTTP_PRECONDITION_FAILED;
        $entityManager = $this->getDoctrine()->getRepository(User::class);
        if (!preg_match("#^[a-zA-Z0-9]+$#", $username))
            return Response::HTTP_FORBIDDEN;
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            return Response::HTTP_NOT_ACCEPTABLE;

        $checkEmail = $entityManager->findOneBy(['email' => $email]);
        $checkUsername = $entityManager->findOneBy(['username' => $username]);
        if(is_null($checkEmail) && is_null($checkUsername)) return Response::HTTP_OK;
        else return Response::HTTP_CONFLICT;
    }
}