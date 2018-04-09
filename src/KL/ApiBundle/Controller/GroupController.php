<?php
/**
 * Created by PhpStorm.
 * User: dvine
 * Date: 07/04/2018
 * Time: 11:51
 */

namespace KL\ApiBundle\Controller;

use FOS\RestBundle\View\View;
use KL\ApiBundle\Entity\Groupe;
use KL\ApiBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\Get;
use KL\ApiBundle\Form\Type\GroupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\SerializerInterface;

class GroupController extends Controller
{
    /**
     * @Rest\View()
     * @Rest\Get("/groups")
     *
     * @param Request $request
     * @return View
     */
    public function getGroupsAction(Request $request)
    {
        $groups = $this->getDoctrine()
            ->getRepository('KLApiBundle:Groupe')
            ->findAll();

        if (empty($groups)) {
            return View::create(['message' => 'Groupes not found'], Response::HTTP_NOT_FOUND);
        }

        return $groups;
    }

    /**
     * @Rest\View()
     * @Rest\Get("/groups/{id}")
     *
     * @param $id
     * @param Request $request
     * @return View
     */
    public function getGroupAction($id, Request $request)
    {
        $group = $this->getDoctrine()
            ->getRepository('KLApiBundle:Groupe')
            ->find($id);

        if (empty($group)) {
            return View::create(['message' => 'Groupe not found'], Response::HTTP_NOT_FOUND);
        }
        return $group;

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/groups")
     */
    public function postGroupsAction(Request $request)
    {
        $groupe = new Groupe();
        $groupe->setNom($request->get('nom'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($groupe);
        $em->flush();

        return $groupe;
    }

    /**
     * @Rest\View()
     * @Rest\Patch("/groups/{id}")
     */
    public function patchGroupAction(Request $request)
    {
        return $this->updateGroup($request, false);
    }


    public function updateGroup(Request $request, $clearMissing)
    {
        $groupe = $this->getDoctrine()
            ->getRepository('KLApiBundle:Groupe')
            ->find($request->get('id'));

        if (empty($groupe)) {
            return View::create(['message' => 'Groupe not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(GroupType::class, $groupe);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($groupe);
            $em->flush();
            return $groupe;
        } else
            return $form;
    }
}