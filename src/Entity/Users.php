<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity("mail", message : "Un utilisateur ayant cette adresse email existe déjà")]
#[ApiResource]
class Users implements PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    private ?string $lastName = null;

    #[ORM\Column(length: 100)]
    private ?string $mail = null;

    #[Assert\NotBlank(message : "Le mot de passe est obligatoire")]
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $bird = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $instaLink = null;

    #[ORM\Column(length: 255)]
    private ?string $Picture = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private array $role = [];

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Publication::class, orphanRemoval: true)]
    private Collection $publications;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Coments::class, orphanRemoval: true)]
    private Collection $coments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Rating::class)]
    private Collection $ratings;

    public function __construct()
    {
        $this->publications = new ArrayCollection();
        $this->coments = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getBird(): ?\DateTimeInterface
    {
        return $this->bird;
    }

    public function setBird(\DateTimeInterface $bird): self
    {
        $this->bird = $bird;

        return $this;
    }

    public function getInstaLink(): ?string
    {
        return $this->instaLink;
    }

    public function setInstaLink(?string $instaLink): self
    {
        $this->instaLink = $instaLink;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->Picture;
    }

    public function setPicture(string $Picture): self
    {
        $this->Picture = $Picture;

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

    public function getRole(): array
    {
        return $this->role;
    }

    public function setRole(array $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
            $publication->setUser($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getUser() === $this) {
                $publication->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Coments>
     */
    public function getComents(): Collection
    {
        return $this->coments;
    }

    public function addComent(Coments $coment): self
    {
        if (!$this->coments->contains($coment)) {
            $this->coments->add($coment);
            $coment->setUser($this);
        }

        return $this;
    }

    public function removeComent(Coments $coment): self
    {
        if ($this->coments->removeElement($coment)) {
            // set the owning side to null (unless already changed)
            if ($coment->getUser() === $this) {
                $coment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setUser($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getUser() === $this) {
                $rating->setUser(null);
            }
        }

        return $this;
    }
}
