<?php
/**
 * Created by PhpStorm.
 * User: dvine
 * Date: 06/04/2018
 * Time: 16:01
 */

namespace KL\ApiBundle\Controller;


use FOS\RestBundle\View\View;
use KL\ApiBundle\Entity\Groupe;
use KL\ApiBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Get;
use KL\ApiBundle\Form\Type\GroupType;
use KL\ApiBundle\Form\Type\UserType;
use KL\ApiBundle\KLApiBundle;
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
     */
    public function getUsersAction(Request $request)
    {
        $users = $this->getDoctrine()
            ->getRepository('KLApiBundle:User')
            ->getUsersWithGroups();
        return $users;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/users/{id}")
     *
     * @param $id
     * @param Request $request
     * @return User|null|object
     */
    public function getUserAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('KLApiBundle:User')
            ->find($id);

        $groups = $em->getRepository('KLApiBundle:Groupe')
            ->findBy(array('id' => $user->getId()));

        return $user;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->submit($request->request->all());

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $user;
        } else
            return $form;
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }

    public function updateUser(Request $request, $clearMissing)
    {
        $user = $this->getDoctrine()
            ->getRepository('KLApiBundle:User')
            ->find($request->get('id'));

        if (empty($user)) {
            return View::create(['message' => 'Groupe not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(UserType::class, $user);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }


    /**
     * @Rest\View()
     * @Rest\Get("/users/{id}/groups")
     */
    public function getGroupsAction(Request $request)
    {
        $user = $this->getDoctrine()->getRepository('KLApiBundle:Groupe')
            ->find($request->get('id'));

        if (empty($user)) {
            return View::create(['message' => 'Groupes not found'], Response::HTTP_NOT_FOUND);
        }
        return $user->getUsers();
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/users/{id}/groups")
     */
    public function postGroupsAction(Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('KLApiBundle:Groupe')
            ->find($request->get('id'));

        if (empty($user)) {
            return View::create(['message' => 'Groupes not found'], Response::HTTP_NOT_FOUND);
        }

        $group = new Groupe();
        //$group->addUser($user);
        $form = $this->createForm(GroupType::class, $group);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();
            return $group;
        } else {
            return $form;
        }
    }
}