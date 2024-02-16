<?php

namespace App\Entity;

use App\Config\SiteConfig;
use App\Entity\SubCategory;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[UniqueEntity('publicRef', message: 'La référence publique doit être unique')]
#[UniqueEntity('privateRef', message: 'La référence privée doit être unique')]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'La référence publique est obligatoire')]
    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[ORM\Column(length: 255)]
    private ?string $publicRef = null;

    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $privateRef = null;

    #[Assert\NotBlank(message: 'La désignation est obligatoire')]
    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[Assert\Positive(message: 'Le prix doit être supérieur à 0')]
    #[ORM\Column]
    private ?int $price = null;

    #[Assert\PositiveOrZero(message: 'Le stock doit être supérieur ou égal à 0')]
    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Picture::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $pictures;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;

    #[Assert\Count(max: 7, maxMessage: 'Vous ne pouvez pas ajouter plus de 7 produits suggérés')]
    #[ORM\ManyToMany(targetEntity: self::class)]
    private Collection $suggestedProducts;

    #[ORM\ManyToOne(inversedBy: 'products', targetEntity: Category::class)]
    private ?Category $category = null;
    
    #[ORM\ManyToOne(inversedBy: 'products', targetEntity: SubCategory::class)]
    private ?SubCategory $subCategory = null;

    #[Assert\Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Slug invalide (format requis : slug-d-un-produit)')]
    #[Assert\NotBlank(message: 'Le slug est obligatoire')]
    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?int $countViews = null;

    #[ORM\Column(nullable: true)]
    private ?int $countCarts = null;

    private ?Picture $firstPicture = null;

    #[ORM\Column(nullable: true)]
    private ?int $countSales = null;

    #[Assert\NotBlank(message: 'La désignation EN est obligatoire')]
    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $enDesignation = null;

    #[Assert\Regex('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', message: 'Slug invalide (format requis : slug-d-un-produit)')]
    #[Assert\NotBlank(message: 'Le slug EN est obligatoire')]
    #[Assert\Length(max: 200, maxMessage: '200 caractères maximum')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $enSlug = null;
    
    #[Assert\Length(max: 2500, maxMessage: '2500 caractères maximum')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Assert\Length(max: 2500, maxMessage: '2500 caractères maximum')]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $enDescription = null;

    #[Assert\Length(max: 2500, maxMessage: '2500 caractères maximum')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $metaDescription = null;

    #[Assert\Length(max: 2500, maxMessage: '2500 caractères maximum')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $enMetaDescription = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalDimension = null;

    #[ORM\Column(nullable: true)]
    private ?int $cabochonDimension = null;

    #[ORM\Column(nullable: true)]
    private ?int $weight = null;

    #[ORM\Column(nullable: true)]
    private ?int $fingerSize = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $material = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $color = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $enColor = null;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->suggestedProducts = new ArrayCollection();
    }

    public function countDescriptionWords(): int
    {
        if(!$this->description)
        {
            return 0;
        }
        return count(explode(' ', $this->description));
    }
    public function countEnDescriptionWords(): int
    {
        if(!$this->enDescription)
        {
            return 0;
        }
        return count(explode(' ', $this->enDescription));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaterialLabel(): ?string 
    {
        if($this->material === null)
        {
            return null;
        }
    }

    public function getPublicRef(): ?string
    {
        return $this->publicRef;
    }

    public function setPublicRef(string $publicRef): self
    {
        $this->publicRef = $publicRef;

        return $this;
    }

    public function getPrivateRef(): ?string
    {
        return $this->privateRef;
    }

    public function setPrivateRef(?string $privateRef): self
    {
        $this->privateRef = $privateRef;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, Picture>
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures->add($picture);
            $picture->setProduct($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getProduct() === $this) {
                $picture->setProduct(null);
            }
        }

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

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSuggestedProducts(): Collection
    {
        return $this->suggestedProducts;
    }

    public function addSuggestedProduct(self $suggestedProduct): self
    {
        if (!$this->suggestedProducts->contains($suggestedProduct)) {
            $this->suggestedProducts->add($suggestedProduct);
        }

        return $this;
    }

    public function removeSuggestedProduct(self $suggestedProduct): self
    {
        $this->suggestedProducts->removeElement($suggestedProduct);

        return $this;
    }

    /**
     * @return Category|null
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category
     *
     * @return  self
     */ 
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return SubCategory|null
     */ 
    public function getSubCategory()
    {
        return $this->subCategory;
    }

    /**
     * @param SubCategory
     *
     * @return  self
     */ 
    public function setSubCategory($subCategory)
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCountViews(): ?int
    {
        return $this->countViews;
    }

    public function setCountViews(?int $countViews): self
    {
        $this->countViews = $countViews;

        return $this;
    }

    public function getCountCarts(): ?int
    {
        return $this->countCarts;
    }

    public function setCountCarts(?int $countCarts): self
    {
        $this->countCarts = $countCarts;

        return $this;
    }

    /**
     * Get the value of firstPicture
     */ 
    public function getFirstPicture()
    {
        return $this->firstPicture;
    }

    /**
     * Set the value of firstPicture
     *
     * @return  self
     */ 
    public function setFirstPicture($firstPicture)
    {
        $this->firstPicture = $firstPicture;

        return $this;
    }

    public function getCountSales(): ?int
    {
        return $this->countSales;
    }

    public function setCountSales(?int $countSales): self
    {
        $this->countSales = $countSales;

        return $this;
    }

    public function getEnDesignation(): ?string
    {
        return $this->enDesignation;
    }

    public function setEnDesignation(string $enDesignation): self
    {
        $this->enDesignation = $enDesignation;

        return $this;
    }

    public function getEnSlug(): ?string
    {
        return $this->enSlug;
    }

    public function setEnSlug(string $enSlug): self
    {
        $this->enSlug = $enSlug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEnDescription(): ?string
    {
        return $this->enDescription;
    }

    public function setEnDescription(?string $enDescription): self
    {
        $this->enDescription = $enDescription;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getEnMetaDescription(): ?string
    {
        return $this->enMetaDescription;
    }

    public function setEnMetaDescription(?string $enMetaDescription): self
    {
        $this->enMetaDescription = $enMetaDescription;

        return $this;
    }

    public function getTotalDimension(): ?int
    {
        return $this->totalDimension;
    }

    public function setTotalDimension(?int $totalDimension): self
    {
        $this->totalDimension = $totalDimension;

        return $this;
    }

    public function getCabochonDimension(): ?int
    {
        return $this->cabochonDimension;
    }

    public function setCabochonDimension(?int $cabochonDimension): self
    {
        $this->cabochonDimension = $cabochonDimension;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getFingerSize(): ?int
    {
        return $this->fingerSize;
    }

    public function setFingerSize(?int $fingerSize): self
    {
        $this->fingerSize = $fingerSize;

        return $this;
    }

    public function getMaterial(): ?string
    {
        return $this->material;
    }

    public function setMaterial(?string $material): self
    {
        $this->material = $material;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getEnColor(): ?string
    {
        return $this->enColor;
    }

    public function setEnColor(?string $enColor): self
    {
        $this->enColor = $enColor;

        return $this;
    }
}
