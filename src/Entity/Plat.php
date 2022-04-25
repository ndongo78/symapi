<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PlatRepository::class)]
#[Vich\Uploadable]
class Plat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message: " name ne peut etre null"
    ),
        Assert\Regex(
            pattern: "/\d/",
            message: "name ne peut pas contenir des chiffres",
            match: false
        ),
        Assert\Length(
            min: 2,
            max:20,
            minMessage: "Minimum 4 caracteres pour le  name",
            maxMessage:"Maximum 20 caracteres sont autorisés name"
        )
    ]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message: "Description ne peut etre null"
    ),
        Assert\Regex(
            pattern: "/\d/",
            message: "Description ne peut pas contenir des chiffres",
            match: false
        ),
        Assert\Length(
            min: 2,
            max:150,
            minMessage: "Minimum 4 caracteres pour le Description",
            maxMessage:"Maximum 20 caracteres sont autorisés Description"
        )
    ]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message: "Price ne peut etre null"
    ),
        Assert\Length(
            min: 2,
            max:150,
            minMessage: "Minimum 4 caracteres pour le price",
            maxMessage:"Maximum 20 caracteres sont autorisés price"
        )
    ]
    private $price;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imagePath;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(
        message: "Ce champs est requise"
    )]
    private $category;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;


    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface|null $updatedAt
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getImageFile(): ?string
    {
        return $this->imagePath;
    }

    public function setImageFile($imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
