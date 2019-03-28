<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as ASSERT;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentRepository")
 */
class Department
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
     * @ASSERT\Length(max="25",maxMessage="Character length must be in 3-25 range",
     *                  min="3",minMessage="Character length must be in 3-25 range")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @ASSERT\NotBlank()
     * @ASSERT\Email()
     */
    private $email1;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @ASSERT\NotBlank()
     * @ASSERT\Email()
     */
    private $email2;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="destination")
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

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

    public function getEmail1(): ?string
    {
        return $this->email1;
    }

    public function setEmail1(string $email): self
    {
        $this->email1 = $email;

        return $this;
    }
    public function getEmail2(): ?string
    {
        return $this->email2;
    }

    public function setEmail2(string $email): self
    {
        $this->email2 = $email;

        return $this;
    }

    public function getEmails()
    {
        $emails = ($this->getEmail2() != null)?[
            $this->email1,
            $this->email2
        ]:$this->email1;
        return $emails;
    }

    /**
     * @return Collection|User[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(User $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setDestination($this);
        }

        return $this;
    }

    public function removeMessage(User $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getDestination() === $this) {
                $message->setDestination(null);
            }
        }

        return $this;
    }
}
