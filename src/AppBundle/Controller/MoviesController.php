<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;
use AppBundle\Entity\Movie as Movie;
use AppBundle\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;


class MoviesController extends AbstractController
{
    use ControllerTrait;

    /**
     * @Rest\View()
     */
    public function getMoviesAction()
    {
        $movies = $this->getDoctrine()->getRepository(Movie::class)->findAll();

        return $movies;
    }

    /**
     * @Rest\View(statusCode=201)
     * @ParamConverter("movie", converter="fos_rest.request_body")
     * @Rest\NoRoute()
     */
    public function postMoviesAction(Movie $movie, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($movie);
        $em->flush();

        return $movie;
    }

    /**
     * @Rest\View*()
     */
    public function deleteMovieAction(Movie $movie)
    {
        if (null === $movie) {
            return $this->createNotFoundException('Ths movie does not exist');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($movie);
        $em->flush();
    }

    /**
     * @Rest\View()
     */
    public function getMovieAction(Movie $movie)
    {
        if (null ===$movie)
        {
            return $this->createNotFoundException('This movie does not exist');
        }

        return $movie;
    }
}