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
     * @var string
     *
     * @ORM\Column(name="duree", type="decimal", precision=3, scale=1)
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
     * @return string
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
}
