<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tache
 *
 * @ORM\Table(name="tache")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TacheRepository")
 */
class Tache
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
     * @var \DateTime
     *
     * @ORM\Column(name="jour", type="date")
     */
    private $jour;

    /**
     * @var decimal
     *
     * @ORM\Column(name="duree", type="decimal", precision=4, scale=2)
     */
    private $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


    /**
     * @ORM\ManyToOne(targetEntity="Activite")
     * @ORM\JoinColumn(name="activite_id", referencedColumnName="id")
     */
    private $activite;

    /**
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="date")
     */
    private $dateCreation;


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
     * Set jour
     *
     * @param \DateTime $jour
     *
     * @return Tache
     */
    public function setJour($jour)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * Get jour
     *
     * @return \DateTime
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * Set duree
     *
     * @param string $duree
     *
     * @return Tache
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return decimal
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Tache
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get description
     */
    public function __construct()
    {
        $this->jour = new \DateTime();
        $this->dateCreation = new \DateTime();
    }

    /**
     * Set activite
     *
     * @param \AppBundle\Entity\Activite $activite
     *
     * @return Tache
     */
    public function setActivite(\AppBundle\Entity\Activite $activite = null)
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * Get activite
     *
     * @return \AppBundle\Entity\Activite
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Tache
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Tache
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Tache
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
