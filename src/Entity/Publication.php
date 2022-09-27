<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ORM\Index;

#[ORM\Entity(repositoryClass: PublicationRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Index(columns : ["city","country","adress","name"], flags : ["fulltext"])]

class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message : "Veuillez renseignez le nom de la photo")]
    #[Assert\Length(min : 5, minMessage : "Le nom de la photo doit faire au moins 5 caractére")]
    private ?string $name = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\NotBlank(message : "Veuillez renseignez la ville")]
    private ?string $city = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\NotBlank(message : "Veuillez renseignez le nom de le pays")]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message : "Veuillez renseignez le nom de la rue")]
    private ?string $adress = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $tips = null;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    #[ORM\OneToMany(mappedBy: 'publication', targetEntity: Coments::class, orphanRemoval: true)]
    private Collection $coments;

    #[ORM\OneToMany(mappedBy: 'publication', targetEntity: Rating::class, orphanRemoval: true)]
    private Collection $ratings;

    #[ORM\OneToMany(mappedBy: 'publication', targetEntity: Images::class, orphanRemoval: true)]
    private Collection $images;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Assert\Image(mimeTypes : ["image/png","image/jpeg","image/jpg","image/gif"], mimeTypesMessage : "Vous devez upload un fichier jpg, jpeg, png ou gif")]
    #[Assert\File(maxSize : "1024k", maxSizeMessage : "La taille du fichier est trop grande")]
    private ?string $image = null;

    public function __construct()
    {
        $this->coments = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    # Permet de récupérer la note d'une publication
    # return int 
    public function getAvgRatings()
    {
        // Calculer la somme des notations 
        // la fonction php array_reduce permet de réduire le tableau à une seule valeur (attention il faut un tableau pas une array Collection, on va utiliser toArray() - 2ème paramètre c'est la fonction pour chaque valeur, 3ème param valeur par défaut)
        $sum = array_reduce($this->ratings->toArray(),function($total, $rating){
            return $total + $rating->getRate();
        },0);

        // faire la division pour avoir la moyenne (ternaire)
        if(count($this->ratings) > 0) return $moyenne = round($sum / count($this->ratings));

        return 0;
    }

     #Permet d'initialiser le slug automatiquement
     #[ORM\PrePersist]
     #[ORM\PreUpdate]
     #return void
     public function initializeSlug(){
         if(empty($this->slug)){
             $slugify = new Slugify();
             $this->slug = $slugify->slugify($this->name.rand(1,10000));
         }
     }

    # Permet d'avoir le la ville et le pays en une fois
    # return Response
    public function getPlace(){
        return "{$this->city}, {$this->country}";
    }
    # Permet d'avoir le la ville et le pays en une fois
    # return Response
    public function getFullPlace(){
        return "{$this->adress}, {$this->city}, {$this->country}";
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getTips(): ?string
    {
        return $this->tips;
    }

    public function setTips(?string $tips): self
    {
        $this->tips = $tips;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

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
            $coment->setPublication($this);
        }

        return $this;
    }

    public function removeComent(Coments $coment): self
    {
        if ($this->coments->removeElement($coment)) {
            // set the owning side to null (unless already changed)
            if ($coment->getPublication() === $this) {
                $coment->setPublication(null);
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
            $rating->setPublication($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getPublication() === $this) {
                $rating->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setPublication($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPublication() === $this) {
                $image->setPublication(null);
            }
        }

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
