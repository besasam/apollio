<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index() {
        $data = ['1', '2', '3'];
        return $this->render('home.html.twig', ['data' => $data]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register() {
        return $this->render('register.html.twig', ['action' => '/api/user/new']);
    }

}