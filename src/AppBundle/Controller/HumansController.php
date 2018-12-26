<?php

namespace AppBundle\Controller;

use AppBundle\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Person as Person;
use FOS\RestBundle\Controller\ControllerTrait;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use FOS\RestBundle\Controller\Annotations as Rest;

class HumansController extends AbstractController
{
    use ControllerTrait;

    
    /**
     * @Rest\View()
     */
    public function getHumansAction()
    {
        $people = $this->getDoctrine()->getRepository(Person::class)->findAll();

        return $people;
    }

    /**
     * @Rest\View(statusCode=201)
     * @ParamConverter("person", converter="fos_rest.request_body")
     * @Rest\NoRoute()
     */
    public function postHumansAction(Person $person, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($person);
        $em->flush();

        return $person;
    }

    /**
     * @Rest\View*()
     */
    public function deleteHumanAction(Person $person)
    {
        if (null === $person) {
            return $this->createNotFoundException('Ths person does not exist');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($person);
        $em->flush();
    }

    /**
     * @Rest\View()
     */
    public function getHumanAction(Person $person)
    {
        if (null ===$person)
        {
            //return $this->createNotFoundException('This person does not exist');
            // return $this->view(404, null);
        }

        return $person;
    }
}