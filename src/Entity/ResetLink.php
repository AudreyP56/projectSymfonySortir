<?php

namespace App\Entity;

use App\Repository\ResetLinkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResetLinkRepository::class)
 */
class ResetLink
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $linkExtension;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="resetLink",)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkExtension(): ?string
    {
        return $this->linkExtension;
    }

    public function setLinkExtension(string $linkExtension): self
    {
        $this->linkExtension = $linkExtension;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
