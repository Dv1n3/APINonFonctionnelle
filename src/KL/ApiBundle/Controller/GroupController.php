<?php
/**
 * Created by PhpStorm.
 * User: dvine
 * Date: 07/04/2018
 * Time: 11:51
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

class GroupController extends Controller
{
    /**
     * @Route("/groups", name="show_all_groups")
     * @Method({"GET"})
     *
     * @return Response
     */
    public function showAllGroupsAction()
    {
        $groups = $this->getDoctrine()
            ->getRepository('KLApiBundle:Groupe')
            ->findAll();

        $data = $this->get('serializer')->serialize($groups, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @Route("/groups/{id}", name="show_group")
     * @Method({"GET"})
     */
    public function showOneGroupAction(Groupe $group)
    {
        $group = $this->getDoctrine()
            ->getRepository('KLApiBundle:Groupe')
            ->findOneBy(array('id' => $group->getId()));

        $data = $this->get('serializer')
            ->serialize($group, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
}