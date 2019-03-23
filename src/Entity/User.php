<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as ASSERT;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @ASSERT\NotBlank()
     * @ASSERT\Length(max="25",min="4")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @ASSERT\Length(max="25", min="3")
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255)
     * @ASSERT\NotBlank()
     * @ASSERT\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @ASSERT\NotBlank()
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department", inversedBy="messages")
     * @ASSERT\NotBlank()
     */
    private $destination;

    public function getId(): ?int
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getDestination(): ?Department
    {
        return $this->destination;
    }

    public function setDestination(?Department $destination): self
    {
        $this->destination = $destination;

        return $this;
    }
}
