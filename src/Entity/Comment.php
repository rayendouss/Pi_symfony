<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("cmd")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups("cmd")
     */
    private $msg;

    /**
     * @ORM\Column(type="datetime")
     *  @Groups("cmd")
     */
    private $create_at;
    /**
     * @ORM\ManyToOne(targetEntity=Subject::class, inversedBy="oeuvres")
     * @Groups("cmd")
     */
    private $commande;
    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return mixed
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * @param mixed $commande
     */
    public function setCommande($commande): void
    {
        $this->commande = $commande;
    }

}
