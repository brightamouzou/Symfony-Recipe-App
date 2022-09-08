<?php

namespace App\Entity;

use App\Repository\MarkRepository;
use Doctrine\DBAL\Schema\Constraint;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UniqueEntity(fields: {"user", "receipe"}, errorPath: 'user',message: 'Cet utilisateur a déjà noté cette recette',)
 * @ORM\Entity(repositoryClass=MarkRepository::class)
 */
class Mark
{


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\LessThan(6)
     * @Assert\Positive()
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=Receipt::class, inversedBy="marks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $receipt;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="marks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getReceipt(): ?Receipt
    {
        return $this->receipt;
    }

    public function setReceipt(?Receipt $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
