<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity('email', message:'Cet adresse email existe déjà !')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(
        min: 3, 
        minMessage:'Votre nom doit faire au minimum {{ limit }} caractères !')]
    private $nom;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(
        min: 3, 
        minMessage:'Votre nom doit faire au minimum {{ limit }} caractères !')]
    private $prenom;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Email(
        message: 'Votre adresse email n\'est pas valide !',
    )]
    private $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(
        min: 7, 
        minMessage:'Votre mot de passe doit faire au minimum {{ limit }} caractères !')]
    private $password;

    #[ORM\OneToMany(mappedBy: 'users', targetEntity: Commentaires::class, orphanRemoval: true)]
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials()
    {
        
    }

    public function getSalt()
    {
        
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

   

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(Commentaires $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setUsers($this);
        }

        return $this;
    }

    public function removeUser(Commentaires $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUsers() === $this) {
                $user->setUsers(null);
            }
        }

        return $this;
    }
}
