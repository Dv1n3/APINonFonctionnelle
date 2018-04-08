<?php
/**
 * Created by PhpStorm.
 * User: dvine
 * Date: 06/04/2018
 * Time: 16:01
 */

namespace KL\ApiBundle\Controller;


use KL\ApiBundle\Entity\Groupe;
use KL\ApiBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/users")
     *
     * @return JsonResponse|Response
     */
    public function getUsersAction(Request $requests)
    {
        $users = $this->getDoctrine()
            ->getRepository('KLApiBundle:User')
            ->findAll();
        //->getUserWithGroups();

        if (empty($users)) {
            return new JsonResponse(["message", 'Users not found'], Response::HTTP_NOT_FOUND);
        }

        return $users;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/users/{id}")
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function getUserAction($id, Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('KLApiBundle:User')
            ->find($id);

        if (empty($user)) {
            return new JsonResponse(["message", 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request)
    {
        $user = new User();
        $user->setEmail($request->get('email'))
            ->setNom($request->get('nom'))
            ->setPrenom($request->get('prenom'))
            ->setActif($request->get('actif'))
            ->setDateCreation(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }
}
