<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={
 *       "normalization_context"={"groups"={"GetUser"}},
 *       "denormalization_context"={"groups"={"SetUser"}},
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("GetUser")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"GetUser", "SetUser"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups("GetUser")
     */
    private $roles = ["ROLE_USER"];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"GetUser", "SetUser"})
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="owner")
     * @Groups("GetUser")
     */
    private $Pictures;

    public function __construct()
    {
        $this->Pictures = new ArrayCollection();
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

    /**
     * @return Collection|Image[]
     */
    public function getPictures(): Collection
    {
        return $this->Pictures;
    }

    public function addPicture(Image $picture): self
    {
        if (!$this->Pictures->contains($picture)) {
            $this->Pictures[] = $picture;
            $picture->setOwner($this);
        }

        return $this;
    }

    public function removePicture(Image $picture): self
    {
        if ($this->Pictures->contains($picture)) {
            $this->Pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getOwner() === $this) {
                $picture->setOwner(null);
            }
        }

        return $this;
    }
}
