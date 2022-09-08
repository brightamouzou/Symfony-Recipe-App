<?php

namespace App\Entity;

use App\Repository\ReceiptRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

/**
 * @ORM\Entity(repositoryClass=ReceiptRepository::class)
 * @Vich\Uploadable
 */
class Receipt
{

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->createdAt=new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
        $this->marks = new ArrayCollection();
    }


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    private $name;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\LessThanOrEqual(1440)
     * 
     */
    private $duration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * Assert\LessThan(50)
     */
    private $nbPersons;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\LessThanOrEqual(5)
     */
    private $difficulty;

    /**
     * Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Ingredient::class)
     */
    private $ingredients;


    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isFavorite=false;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isPublic = false;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="receipts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Mark::class, mappedBy="receipt", orphanRemoval=true)
     */
    private $marks;

    /**
     * @Vich\UploadableField(mapping="recette_images", fileNameProperty="imageName")
     * @var File|null
     */
    private ?File $imageFile = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @var string|null
     */
    private ?string $imageName = null;


    /**
     * 
     * @ORM\PrePersist()
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new DateTimeImmutable();
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

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getNbPersons(): ?int
    {
        return $this->nbPersons;
    }

    public function setNbPersons(?int $nbPersons): self
    {
        $this->nbPersons = $nbPersons;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setIsFavorite($isFavorite){
        $this->isFavorite=$isFavorite;
    }


    public function getIsFavorite()
    {
        return $this->isFavorite;
    }

    public function addIngredient(Ingredient $ingredient):self{
        if(!$this->ingredients->contains(($ingredient))){
        $this->ingredients[]=$ingredient;
        }

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }



    // public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    // {
    //     $this->updatedAt = $updatedAt;

    //     return $this;
    // }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of isPublic
     */ 
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set the value of isPublic
     *
     * @return  self
     */ 
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }
    

    /**
     * @return Collection<int, Mark>
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(Mark $mark): self
    {
        if (!$this->marks->contains($mark)) {
            $this->marks[] = $mark;
            $mark->setReceipt($this);
        }

        return $this;
    }

    public function removeMark(Mark $mark): self
    {
        if ($this->marks->removeElement($mark)) {
            // set the owning side to null (unless already changed)
            if ($mark->getReceipt() === $this) {
                $mark->setReceipt(null);
            }
        }

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
        } 
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }
}
