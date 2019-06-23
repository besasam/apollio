<?php


namespace App\Controller\DBController\Put;


use App\Controller\DBController\Post\PasswordVerification;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use \Exception;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserUpdater extends AbstractController
{
    private $encoder;
    //The encoder is given by the configs, and is required here.
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

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
     * HTTP_I_AM_A_TEAPOT: This function has a bug
     */
    public function updateEmail()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $data = $_POST;
        if(!isset($data['email']))
            return new Response('{}', Response::HTTP_BAD_REQUEST);
            //can't change email into nothing

        $email = $data['email'];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) //filters email to match standard email format
            return new Response('{"email":"' . $email . '"}', Response::HTTP_NOT_ACCEPTABLE);

        /** @var User $user */
        $user = $this->getUser(); //the user who changes their email

        return $this->updateUser("email",$email,$user);
    }

    /**
     * @return Response
     * @Route("/api/update/password", methods={"POST"})
     */
    /*
     * HTTP_BAD_REQUEST: A field is missing (password, passwordRepeat, oldPassword required)
     * HTTP_PRECONDITION_FAILED: password does not match passwordRepeat
     * HTTP_FORBIDDEN: old password is wrong
     * HTTP_INTERNAL_SERVER_ERROR: database error
     * HTTP_OK: successfully updated
     * HTTP_I_AM_A_TEAPOT: This function has a bug
     */
    public function changePassword()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        $data = $_POST;
        if(!( isset($data['password']) && isset($data['passwordRepeat']) && isset($data['oldPassword']) ))
            return new Response('{}', Response::HTTP_BAD_REQUEST);
        //password always needs a second verification password to be changed; also verification from user using old PW

        //Confirm that the password and its repeat match
        $password = $data['password'];
        $passwordRepeat = $data['passwordRepeat'];
        if($password !== !$passwordRepeat)
            return new Response('{}', Response::HTTP_PRECONDITION_FAILED);

        //Check if the user is allowed to change this password by verifying the old password
        /** @var User $user */
        $user = $this->getUser();
        $username = $user->getUsername();
        $oldPassword = $data['oldPassword'];
        $pv = new PasswordVerification(); //using the password verification function from another controller
        if(!$pv->verifyPassword($username,$oldPassword))
            return new Response('{}', Response::HTTP_FORBIDDEN);

        return $this->updateUser("password", $password, $user);
    }

    /**
     * @param string $field
     * @param string $value
     * @param User $user
     * @return Response
     */
    /*
     * HTTP_I_AM_A_TEAPOT: field is not valid
     * HTTP_INTERNAL_SERVER_ERROR: Error with handling database or with the encoder
     * HTTP_OK: Successfully updated
     */
    private function updateUser(string $field, string $value, User $user)
    {
        try {
            //All the Database stuff to update the user in the database
            $mngr = $this->getDoctrine()->getManagerForClass(User::class);
            switch ($field) {
                case "email":
                    $user->setEmail($value);
                    break;
                case "password":
                    $user->setPassword($this->encoder->encodePassword($user,$value));
                    break;
                default:
                    return new Response('{}', Response::HTTP_I_AM_A_TEAPOT); //should never happen
            }
            $mngr->persist($user);
            $mngr->flush();
            return new Response('{}', Response::HTTP_OK);
        } catch (Exception $e) { //catching because Database can always fail
            return new Response('{}', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}