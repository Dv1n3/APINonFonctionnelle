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
use KL\ApiBundle\Form\Type\GroupType;
use KL\ApiBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends Controller
{
    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users")
     *
     */
    public function getUsersAction(Request $request)
    {
        $users = $this->getDoctrine()
            ->getRepository('KLApiBundle:User')
            ->findAll();
        if (empty($users)) {
            return View::create(['message' => 'Groupe not found'], Response::HTTP_NOT_FOUND);
        }

        return $users;
    }

    /**
     * @Rest\View(serializerGroups={"user"})
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
        if (empty($user)) {
            return View::create(['message' => 'Groupe not found'], Response::HTTP_NOT_FOUND);
        }

        return $user;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
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
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUserAction($request, false);
    }

    public function updateUserAction(Request $request, $clearMissing)
    {
        $user = $this->getDoctrine()
            ->getRepository('KLApiBundle:User')
            ->find($request->get('id'));

        if (empty($user)) {
            return View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
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
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users/{id}/groups")
     */
    public function getGroupsUsersAction(Request $request)
    {
        $user = $this->getDoctrine()->getRepository('KLApiBundle:Groupe')
            ->find($request->get('id'));

        if (empty($user)) {
            return View::create(['message' => 'Groupes not found'], Response::HTTP_NOT_FOUND);
        }
        return $user;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/users/{id}/groups")
     */
    public function postGroupsUsersAction(Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('KLApiBundle:Groupe')
            ->find($request->get('id'));

        if (empty($user)) {
            return View::create(['message' => 'Groupes not found'], Response::HTTP_NOT_FOUND);
        }

        $group = new Groupe();
        $user->setNomGroupe($group);
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

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/groups/{id}/users"})
     *
     * @param Request $request
     * @return View|UserController|User|\Symfony\Component\Form\FormInterface
     */
    public function getUsersInGroupAction(Request $request)
    {
        $group = $this->getDoctrine()
            ->getRepository('KLApiBundle:Groupe')
            ->findBy($request->get('id'));
        if (empty($group)) {
            return View::create(['message' => 'Groupe not found'], Response::HTTP_NOT_FOUND);
        }

        $user = new User();
        $user->addGroupe($group);

        $form = $this->createForm(GroupType::class, $user);
        $form->submit($request->request->all());
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }
}