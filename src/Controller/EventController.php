<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{

    /**
     * @Route("/register/success")
     */
    public function registerSuccess() {
        return $this->render('alert.html.twig', [
            "type" => "success",
            "message" => "Your account has successfully been created. You can now <a href='/login' class='alert-link'>log in</a> with your credentials."
        ]);
    }

    /**
     * @Route("/upload/success/{id}", name="uploadSuccess")
     */
    public function uploadSuccess($id) {
        $un = $this->getUser()->getUsername();
        return $this->render('alert.html.twig', [
            "type" => "success",
            "message" => "Your artwork has successfully been uploaded. <a href='/u/".$un."/view/".$id."' class='alert-link'>Click here</a> to view it."
        ]);
    }

}