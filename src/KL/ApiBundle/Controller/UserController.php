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
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users")
     *
     */
    public function getUsersAction(Request $request)
    {
        $listUsers = $this->getDoctrine()
            ->getRepository('KLApiBundle:User')
            ->getUsersWithGroups();
        //->findAll();
        //$listGroups = $em->getRepository('KLApiBundle:Groupe');
        //var_dump($listUsers);


        return $listUsers;
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users/{id}")
     *
     * @param $id
     * @param Request $request
     */
    public function getUserAction($id, Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('KLApiBundle:User')
            ->getUsersWithGroups();
        //->find($id);
        //var_dump($user);
        //$listUser = $repository->findById($id);

        //var_dump($listUser); die;

        /*$group = $em->getRepository('KLApiBundle:Groupe')
            ->myFindGroup($id);*/


        //->getUserWithGroups(array($request->get('user_id'), 'groupe_id'));
        /*$groups = $this->getDoctrine()->getManager()
            ->getRepository('KLApiBundle:Groupe')
            ->getGroups($id);*/

        //var_dump($groups);


        /*if (empty($user)) {
            return new JsonResponse(["message", 'User not found'], Response::HTTP_NOT_FOUND);
        }*/
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
}