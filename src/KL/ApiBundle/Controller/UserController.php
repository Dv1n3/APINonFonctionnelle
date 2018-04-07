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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends Controller
{
    /**
     * @Route("/users", name="show_all_users")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function showAllUsersAction()
    {
        $users = $this->getDoctrine()
            ->getRepository('KLApiBundle:User')
            ->findAll();

        $data = $this->get('serializer')->serialize($users, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @Route("/users/{id}", name="show_user")
     * @Method({"GET"})
     */
    public function showOneUserAction(User $user)
    {
        $user = $this->getDoctrine()->getRepository('KLApiBundle:User')->findOneBy(array('id' => $user->getId()));
        $data = $this->get('serializer')->serialize($user, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /**
     * @Route("/users", name="create_user")
     * @Method({"POST"})
     */
    public function createUserAction(Request $request)
    {
        $data = $request->getContent();
        $user = $this->get('serializer')
            ->deserialize($data, 'KLApiBundle\Entity\User', 'json');

        dump($user);
        /*$em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response('', Response::HTTP_CREATED);
    }




    /**
     * @Route("/users/{id}", name="show_user")
     * @Method({"GET"})
     */
        public function editUserAction(User $user)
    {
        $user = $this->getDoctrine()->getRepository('KLApiBundle:User')->findOneBy(array('id' => $user->getId()));
        $data = $this->get('serializer')->serialize($user, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }



}
