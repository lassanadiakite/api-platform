<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"user_read"}},
 *     denormalizationContext={"groups"={"user_write"}},
 *     paginationItemsPerPage=20,
 *     collectionOperations={
 *          "get"={},
 *          "post"={}
 *     },
 *     itemOperations={
 *          "get"={},
 *          "put"={},
 *     }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    /**
     * @Groups({"user_read"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @Groups({
	 *     "user_read",
	 *     "user_write",
	 *     "product_read",
	 *     "card_read",
	 *     "comment_read",
	 *     "comment_read",
	 *     "product_read"
	 * })
	 * @ORM\Column(type="string", length=255)
	 */
	private $firstName;

	/**
	 * @Groups({
	 *     "user_read",
	 *     "user_write",
	 *     "product_read",
	 *     "card_read",
	 *     "comment_read",
	 *     "comment_read",
	 *     "product_read"
	 * })
	 * @ORM\Column(type="string", length=255)
	 */
	private $lastName;

    /**
     * @Groups({
     *     "user_read",
     *     "user_write",
     *     "product_read",
     *     "card_read",
     *     "comment_read",
     *     "product_read"
     * })
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

	/**
	 * @Groups({"user_write"})
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 */
	private $password;

    /**
     * @Groups({"user_read", "user_write"})
     * @ORM\Column(type="json")
     */
    private $roles = ["ROLE_USER"];

	/**
	 * @Groups({"user_read", "user_write"})
	 * @ORM\OneToOne(targetEntity=Card::class, mappedBy="user_id", cascade={"persist", "remove"})
	 */
	private $card;

	/**
	 * @Groups({"user_read", "user_write"})
	 * @ORM\OneToMany(targetEntity=Product::class, mappedBy="user_id", orphanRemoval=true)
	 */
	private $products;

	/**
	 * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="idUser")
	 */
	private $comments;

	/**
	 * @var \DateTime $created
	 * @Groups({"user_read"})
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime", options={"default":"CURRENT_TIMESTAMP"})
	 */
	private $created;

	/**
	 * @var \DateTime $updated
	 * @Groups({"user_read"})
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

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

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

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(Card $card): self
    {
        $this->card = $card;

        // set the owning side of the relation if necessary
        if ($card->getUserId() !== $this) {
            $card->setUserId($this);
        }

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
            $product->setUserId($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getUserId() === $this) {
                $product->setUserId(null);
            }
        }

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
            $comment->setIdUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getIdUser() === $this) {
                $comment->setIdUser(null);
            }
        }

        return $this;
    }
}
