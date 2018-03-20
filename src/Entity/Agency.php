<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AgencyRepository")
 * @ApiResource(
 * collectionOperations={"get"},
 * itemOperations={"get"}
 *     )
 */
class Agency
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Flight", mappedBy="agency", cascade={"persist","remove"})
     */
    private $flights;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Hotel", mappedBy="agency", cascade={"persist","remove"})
     */
    private $hotels;

    public function __construct()
    {
        $this->flights = new ArrayCollection();
        $this->hotels = new ArrayCollection();
    }

    /**
     * @return Collection|Flight[]
     */
    public function getFlights()
    {
        return $this->flights;
    }

    /**
     * @return Collection|Hotel[]
     */
    public function getHotels()
    {
        return $this->hotels;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
