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

    /**
     * @Route("/upload", name="upload")
     */
    public function upload() {
        return $this->render('upload.html.twig', ['action' => '/api/artwork/new']);
    }

    /**
     * @Route("/settings", name="settings")
     */
    public function settings() {
        return $this->render('settings.html.twig', ['action' => '/api/user/delete']);
    }

    /**
     * @Route("/u/{user}", name="profile")
     */
    public function profile($user) {
        return $this->render('profile.html.twig', [
            "artist" => $user,
            "subCount" => 12, "artworks" => [
                ["id" => "id", "title" => "title", "filelink" => "filelink", "artist" => "artist", "created_at" => "created_at"]
            ]]);
    }

}