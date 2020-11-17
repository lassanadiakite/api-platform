<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"product_read"}},
 *     denormalizationContext={"groups"={"product_write"}},
 *     paginationItemsPerPage=20,
 *     collectionOperations={
 *          "get"={},
 *          "post"={}
 *     },
 *     itemOperations={
 *          "get"={},
 *          "put"={"denormalization_context"={"groups"={"product_put"}}},
 *     }
 * )
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @Groups({"product_read"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"user_read"})
     * @Groups({"product_put"})
     * @Groups({"card_read"})
     * @Groups({"product_read", "product_write"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"user_read"})
     * @Groups({"product_put"})
     * @Groups({"card_read"})
     * @Groups({"product_read", "product_write"})
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @Groups({"user_read"})
     * @Groups({"card_read"})
     * @Groups({"product_read", "product_write"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reference;

    /**
     * @Groups({"user_read"})
     * @Groups({"product_put"})
     * @Groups({"product_read", "product_write"})
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Groups({"user_read"})
     * @Groups({"product_put"})
     * @Groups({"card_read"})
     * @Groups({"product_read", "product_write"})
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @Groups({"product_read"})
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="product", orphanRemoval=true)
     */
    private $comments;

    /**
     * @Groups({"product_read", "product_write"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;


    // GEDMO

    /**
     * @var \DateTime $created
     * @Groups({"product_read"})
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
     */
    private $created;

    /**
     * @var \DateTime $updated
     * @Groups({"product_read"})
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    /**
     * @var \DateTime $contentChanged
     * @ORM\Column(name="content_changed", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field={"title", "body"})
     */
    private $contentChanged;

    /**
     * @ORM\ManyToOne(targetEntity=Card::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $card;


    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated(\DateTime $updated): void
    {
        $this->updated = $updated;
    }

    /**
     * @return \DateTime
     */
    public function getContentChanged(): \DateTime
    {
        return $this->contentChanged;
    }

    /**
     * @param \DateTime $contentChanged
     */
    public function setContentChanged(\DateTime $contentChanged): void
    {
        $this->contentChanged = $contentChanged;
    }


    // END GEDMO

    public function __construct()
    {
        $this->comments = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getProduct() === $this) {
                $comment->setProduct(null);
            }
        }

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): self
    {
        $this->card = $card;

        return $this;
    }
}
