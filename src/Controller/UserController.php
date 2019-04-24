<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 * @Route("/api", name="api_")
 */
class UserController extends AbstractFOSRestController
{

    /**
     * Lists all Users.
     * @Rest\Get("/users")
     *
     * @return Response
     */
    public function getUsersAction(): Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findall();

        return $this->handleView($this->view($users));
    }

    /**
     * Lists all Users.
     * @Rest\Get("/movies/user/{id}")
     *
     * @param string $id
     * @return Response
     */
    public function getMoviesUserAction(string $id): Response
    {

        $movies = $this->getDoctrine()->getRepository(User::class)->findMoviesByUser($id);

        if($movies){
            return $this->handleView($this->view($movies, Response::HTTP_OK));
        }else{
            return $this->handleView($this->view('No movies found for this user', Response::HTTP_NOT_FOUND));
        }
    }

    /**
     * Create User.
     * @Rest\Post("/user")
     *
     * @param Request $request
     * @return Response
     */
    public function newUserAction(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * @Rest\Patch("/user/{id}")
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function patchUserAction(Request $request, string $id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $user->setMovies(new ArrayCollection());

        $form = $this->createForm(UserType::class, $user, ['method' => 'PATCH']);
        $data = json_decode($request->getContent(), true);

        $form->submit($data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_OK));
        }

        return $this->handleView($this->view($form->getErrors()));
    }

}
