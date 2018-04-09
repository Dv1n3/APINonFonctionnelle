<?php

namespace KL\ApiBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Groupe
 *
 * @ORM\Table(name="groupe")
 * @ORM\Entity(repositoryClass="KL\ApiBundle\Repository\GroupeRepository")
 */
class Groupe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity="\KL\ApiBundle\Entity\User", mappedBy="groupes")
     * @ORM\JoinTable(name="user_groupe")
     */
    private $users;

    /**
     * Groupe constructor.
     * @param $users
     */
    public function __construct($users)
    {
        $this->users = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Groupe
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Add user
     *
     * @param \KL\ApiBundle\Entity\User $user
     *
     * @return Groupe
     */
    public function addUser(\KL\ApiBundle\Entity\User $user)
    {

        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \KL\ApiBundle\Entity\User $user
     */
    public function removeUser(\KL\ApiBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
