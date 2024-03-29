<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AnimateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AnimateurRepository::class)]
#[UniqueEntity(
    fields: ['email'], 
    message: 'L\'email {{ value }} est deja utilisé par un animateur'
    )]


class Animateur extends Personne 
implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    
    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank]
    private $genre; // sexe

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email(message: 'L\'email {{ value }} n\'est pas valide')]
    #[Assert\NotBlank]
    private $email;

    #[ORM\Column(type: 'json')]
    #[Assert\NotBlank]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\Regex('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/')]
    private $password;


    #[ORM\OneToMany(mappedBy: 'animateurs', targetEntity: Atelier::class)]
    private $ateliers;


    public function __construct()
    {
        $this->ateliers = new ArrayCollection();
    }

    //teste
    public static function build($nom, $prenom, $email, $password)
    {
        $animateur = new Animateur();
        $animateur->setNom($nom);
        $animateur->setPrenom($prenom);
        $animateur->setEmail($email);
        $animateur->setPassword($password);
        return $animateur;
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
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
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
        // guarantee every user at least has ROLE_ANIMATEUR
        // $roles[] = 'ROLE_ANIMATEUR';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Atelier>
     */
    public function getAteliers(): Collection
    {
        return $this->ateliers;
    }

    public function addAtelier(Atelier $atelier): self
    {
        if (!$this->ateliers->contains($atelier)) {
            $this->ateliers[] = $atelier;
            $atelier->setAnimateurs($this);
        }

        return $this;
    }

    public function removeAtelier(Atelier $atelier): self
    {
        if ($this->ateliers->removeElement($atelier)) {
            // set the owning side to null (unless already changed)
            if ($atelier->getAnimateurs() === $this) {
                $atelier->setAnimateurs(null);
            }
        }

        return $this;
    }


    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;


        return $this;
    }
}
