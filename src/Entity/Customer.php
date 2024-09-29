<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email.')]
class Customer implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 2047, nullable: true)]
    #[Assert\Regex(
        pattern: '/^(\d{1,4}[\s\w]{1,2047})$/',
        message: 'L\'adresse "{{ value }}" n\'est pas valide.'
    )]
    private ?string $address = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Regex(
        pattern: '/^\d{5}$/',
        message: 'Le code postal "{{ value }}" n\'est pas valide.'
    )]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(length: 1023)]
    #[Assert\Email(
        message: 'L\'email "{{ value }}" n\'est pas valide.'
    )]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    #[Assert\Regex(
        pattern: '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/',
        message: 'Le numéro de téléphone "{{ value }}" n\'est pas valide.'
    )]
    private ?string $phoneNumber = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'customer')]
    private Collection $reservations;

    /**
     * @var Collection<int, DrivingLicense>
     */
    #[ORM\ManyToMany(targetEntity: DrivingLicense::class)]
    private Collection $drivingLicenses;

    #[ORM\Column(length: 2047)]
    private ?string $password = null;

    #[ORM\Column(type: 'json', length: 255)]
    #[Assert\Json]
    private ?array $roles = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->drivingLicenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setCustomer($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getCustomer() === $this) {
                $reservation->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DrivingLicense>
     */
    public function getDrivingLicenses(): Collection
    {
        return $this->drivingLicenses;
    }

    public function addDrivingLicense(DrivingLicense $drivingLicense): static
    {
        if (!$this->drivingLicenses->contains($drivingLicense)) {
            $this->drivingLicenses->add($drivingLicense);
        }

        return $this;
    }

    public function removeDrivingLicense(DrivingLicense $drivingLicense): static
    {
        $this->drivingLicenses->removeElement($drivingLicense);

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        if ($this->roles === null) {
            return ['ROLE_USER'];
        }

        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): static
    {
        if ($this->roles === null) {
            $this->roles = [];
        }

        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(string $role): static
    {
        if ($this->roles === null) {
            return $this;
        }

        $key = array_search($role, $this->roles);
        if ($key !== false) {
            unset($this->roles[$key]);
        }

        return $this;
    }

    public function eraseCredentials(): void
    {
        // not implemented
    }
    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
