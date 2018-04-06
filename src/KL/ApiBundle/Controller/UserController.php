<?php
/**
 * Created by PhpStorm.
 * User: dvine
 * Date: 06/04/2018
 * Time: 16:01
 */

namespace KL\ApiBundle\Controller;


use KL\ApiBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Get(
     *     path = "/users",
     *     name = "users_list")
     * @View
     */
    public function getUsersAction(Request $request){
        $users = $this->getDoctrine()
            ->getRepository('KLApiBundle:User')
            ->findAll();

        $content = array();
        foreach ($users as $user) {
            $content[] = [
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "nom" => $user->getNom(),
                "prenom" => $user->getPrenom(),
                "actif" => $user->getActif(),
                "dateCreation" => $user->getDateCreation()
            ];
        }
        return new JsonResponse($content);
    }

    /**
     * @Get(
     *     path = "/users/{id}",
     *     name = "users_list",
     *     requirements = {"id"="\d+"}
     * )
     */
    public function getOneUsersAction(Request $request){
        $users = $this->getDoctrine()
            ->getRepository('KLApiBundle:User')
            ->findOneBy('id');

        $content = array();
        foreach ($users as $user) {
            $content[] = [
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "nom" => $user->getNom(),
                "prenom" => $user->getPrenom(),
                "actif" => $user->getActif(),
                "dateCreation" => $user->getDateCreation()
            ];
        }
        return new JsonResponse($content);
    }


}