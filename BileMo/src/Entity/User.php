<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Client;

/**
 * @ApiResource()
 * @UniqueEntity(fields={"email"}, message="Cet email est déjà utilisé")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
	 * @Groups({"users:read", "users:create"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(message="Le champ surname ne peut pas être vide")
	 * @Groups({"users:read", "users:create"})
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
	 * @Groups({"users:read", "users:create"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(message="Le champ email ne peut pas être vide")
	 * @Assert\Email(message="Veuillez entrer une adresse mail valide")
	 * @Groups({"users:read", "users:create"})
     */
    private $email;

    /**
     * @ORM\Column(type="datetime")
	 * @Groups({"users:read", "users:create"})
     */
    private $registeredAt;

    /**
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
	 * @Groups({"users:read"})
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
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
}
