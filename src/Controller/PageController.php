<?php


namespace App\Controller;


use App\Controller\DBController\Get\ArtworkGetter;
use App\Controller\DBController\Get\ProfileGetter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index() {
        $ag = new ArtworkGetter();
        $data = (array) json_decode($ag->getAllArtworks(10, 0, $this->getDoctrine())->getContent());
        return $this->render('home.html.twig', [
            "artworks" => $data["artworks"]
        ]);
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
     * @Route("/u/{user}/{page}", name="profile", defaults={"page"=1})
     */
    public function profile($user, $page) {
        $pg = new ProfileGetter();
        if($page == 1) {
            $data = (array) json_decode($pg->getProfile($user, 0, $this->getDoctrine(), $this->getUser())->getContent());
        } else {
            $offset = ($page - 1) * 10;
            $data = (array) json_decode($pg->getProfile($user, $offset, $this->getDoctrine(), $this->getUser())->getContent());
        }
        $pages = intdiv($data["awCount"], 10) + 1;
        return $this->render('profile.html.twig', [
            "artist" => $user,
            "currentPage" => $page,
            "pages" => $pages,
            "subCount" => $data["subCount"],
            "subscribed" => $data["subscribed"],
            "awCount" => $data["awCount"],
            "artworks" => $data["artworks"]
        ]);
    }

    /**
     * @Route("/u/{user}/view/{artwork}", name="viewArtwork")
     */
    public function view($user, $artwork) {
        $ag = new ArtworkGetter();
        $data = (array) json_decode($ag->getArtworkById($artwork, $this->getDoctrine())->getContent());
        if($data["artist"] == $user) {
            return $this->render('artwork.html.twig', [
                "artist" => $data["artist"],
                "title" => $data["title"],
                "file" => $data["file"],
                "date" => $data["date"]->date
            ]);
        } else {
            return $this->render('alert.html.twig', [
                "type" => "error",
                "message" => "Artwork not found for user " . $user,
            ]);
        }
    }

    /**
     * @Route("/subscriptions", name="subscriptions")
     */
    public function subscriptions() {
        return $this->render('subscriptions.html.twig', [
            "subscribed" => $this->getUser()->getSubscriptionsAsArray()
        ]);
    }

}