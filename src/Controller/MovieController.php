<?php

namespace App\Controller;

use App\Entity\Movie;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 * @Route("/api", name="api_")
 */
class MovieController extends AbstractFOSRestController
{

    /**
     * @Rest\Get("/movies/top")
     * @return Response
     */
    public function getTopMoviesAction(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $repository->findTopMovies();

        if($movies){
            return $this->handleView($this->view($movies, Response::HTTP_OK));
        }else{
            //@todo customize error return
            return $this->handleView($this->view('No poll in database', Response::HTTP_NOT_FOUND));
        }

    }

    /**
     * @Rest\Post("/movie/users")
     * @param Request $request
     * @return Response
     */
    public function getUsersMovieAction(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $repository = $this->getDoctrine()->getRepository(Movie::class);

        /** @var Movie $movie */
        $movie = $repository->findOneBy(['imdbId' => $data["imdbId"]]);

        if($movie){
            return $this->handleView($this->view($movie->getUsers(), Response::HTTP_OK));
        }else{
            //@todo customize error return
            return $this->handleView($this->view('No user found for this movie', Response::HTTP_NOT_FOUND));
        }
    }
}