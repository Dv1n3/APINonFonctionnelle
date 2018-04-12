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
use KL\ApiBundle\Form\Type\GroupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class GroupController extends Controller
{
    public function __construct()
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @Rest\View(serializerGroups={"group"})
     * @Rest\Get("/groups/{id}")
     */
    public function getGroupAction($id, Request $request)
    {
        $group = $this->getDoctrine()
            ->getRepository('KLApiBundle:Groupe')
            ->find($id);

        if (empty($group)) {
            return View::create(['message' => 'Groupe not found'], Response::HTTP_NOT_FOUND);
        }
        $data = $this->get('serializer')->serialize($group, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Rest\View(serializerGroups={"group"})
     * @Rest\Get("/groups")
     */
    public function getGroupsAction(Request $request)
    {
        $groups = $this->getDoctrine()
            ->getRepository('KLApiBundle:Groupe')
            ->findAll();

        if (empty($groups)) {
            return View::create(['message' => 'Groupes not found'], Response::HTTP_NOT_FOUND);
        }
        //$jsonContent = $this->get('serializer')->serialize($groups, 'json');

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getGroupes();
        });
        $serializer = new Serializer(array($normalizer), array($encoder));

        /* $data = $this->get('serializer');
        $data = $data->setCircularReferenceLimit(3);
        $data = $data->serialize($groups, 'json');

        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        */
        return $serializer->serialize($groups, 'json');

    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"group"})
     * @Rest\Post("/groups")
     */
    public function postGroupsAction(Request $request)
    {
        $groupe = new Groupe();
        $groupe->setNomGroupe($request->get('nom'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($groupe);
        $em->flush();

        return $groupe;
    }

    /**
     * @Rest\View(serializerGroups={"group"})
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