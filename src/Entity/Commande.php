<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("cmd")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="commandes")
     * @Groups("cmd")
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups("cmd")

     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups("cmd")
     *
     */
    private $msg;

    /**
     * @ORM\Column(type="datetime")
     *  @Groups("cmd")
     *
     */
    private $create_at;
    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="commande",cascade={"remove"})
     */
    private $oeuvres;
    public function __construct()
    {
        $this->oeuvres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOeuvres()
    {
        return $this->oeuvres;
    }

    /**
     * @param mixed $oeuvres
     */
    public function setOeuvres($oeuvres): void
    {
        $this->oeuvres = $oeuvres;
    }

    /**
     * @return Collection|Oeuvre[]
     */
    public function getOeuvre(): Collection
    {
        return $this->Oeuvre;
    }

    public function addOeuvre(Comment $oeuvre): self
    {
        if (!$this->Oeuvre->contains($oeuvre)) {
            $this->Oeuvre[] = $oeuvre;
        }

        return $this;
    }

    public function removeOeuvre(Comment $oeuvre): self
    {
        $this->Oeuvre->removeElement($oeuvre);

        return $this;
    }

    public function getDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(bool $done): self
    {
        $this->done = $done;

        return $this;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param mixed $msg
     */
    public function setMsg($msg): void
    {
        $this->msg = $msg;
    }

    /**
     * @return mixed
     */
    public function getCreateAt()
    {
        return $this->create_at;
    }

    /**
     * @param mixed $create_at
     */
    public function setCreateAt($create_at): void
    {
        $this->create_at = $create_at;
    }






}
