<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PublicationRepository")
 */
class Publication
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"group1"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Groups({"group1"})
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"group1"})
     */
    private $filepath;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"group1"})
     */
    private $createDate;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"group1"})
     */
    private $valid;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Groups({"group1"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypePublication", inversedBy="publications")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"group1"})
     * @SerializedName("categorie")
     */
    private $id_type;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"group1"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="publications")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"group1"})
     */
    private $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getFilepath(): string
    {
        return $this->filepath;
    }

    public function setFilepath(string $filepath): self
    {
        $this->filepath = $filepath;

        return $this;
    }

    public function getCreateDate(): \DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;

        return $this;
    }

    public function getValid(): bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdType(): TypePublication
    {
        return $this->id_type;
    }

    public function setIdType(TypePublication $id_type): self
    {
        $this->id_type = $id_type;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
