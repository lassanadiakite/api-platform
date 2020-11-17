<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"card_read"}},
 *     denormalizationContext={"groups"={"card_write"}},
 *     paginationItemsPerPage=20,
 *     collectionOperations={
 *          "get"={},
 *          "post"={}
 *     },
 *     itemOperations={
 *          "get"={},
 *          "put"={"denormalization_context"={"groups"={"card_put"}}},
 *     }
 * )
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
class Card
{
    /**
     * @Groups({"card_read"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"card_read", "card_write"})
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="card", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_id;

    /**
     * @Groups({"card_read", "card_write"})
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @Groups({"card_read", "card_write"})
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="card")
     */
    private $products;

    public function __construct()
    {
        $this->product = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id): self
    {
        $this->user_id = $user_id;

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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setCard($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getCard() === $this) {
                $product->setCard(null);
            }
        }

        return $this;
    }
}
