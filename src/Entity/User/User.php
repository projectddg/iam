<?php

namespace App\Entity\User;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Core\Role;
use App\Entity\Pegawai\Pegawai;
use App\Repository\User\UserRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Monolog\DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}}
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="`user`", indexes={
 *     @ORM\Index(name="idx_user_data", columns={"id", "username", "password"}),
 *     @ORM\Index(name="idx_user_status", columns={"id", "status", "locked"}),
 * })
 */
class User implements UserInterface
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     * @Groups({"read", "write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"read", "write"})
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users")
     * @Groups({"write"})
     */
    private $role;

    /**
     * Default Symfony Guard Role
     * This is a virtual attributes
     * @var array
     * @Groups({"read"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"write"})
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=128)
     */
    private $plainPassword = null;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read", "write"})
     * @Assert\NotNull()
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read", "write"})
     * @Assert\NotNull()
     */
    private $locked;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"write"})
     * @Assert\NotNull()
     */
    private $twoFactorEnabled;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"write"})
     */
    private $lastChange;

    /**
     * @ORM\OneToMany(targetEntity=UserTwoFactor::class, mappedBy="user", orphanRemoval=true)
     * @Groups({"write"})
     */
    private $userTwoFactors;

    /**
     * @ORM\OneToOne(targetEntity=Pegawai::class, mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"read", "write"})
     */
    private $pegawai;

    /**
     * @ORM\OneToMany(targetEntity=Group::class, mappedBy="owner")
     * @Groups({"read", "write"})
     */
    private $ownedGroups;

    /**
     * @ORM\OneToMany(targetEntity=GroupMember::class, mappedBy="user", orphanRemoval=true)
     * @Groups({"read", "write"})
     */
    private $groupMembers;

    public function __construct()
    {
        $this->userTwoFactors = new ArrayCollection();
        $this->role = new ArrayCollection();
        $this->ownedGroups = new ArrayCollection();
        $this->groupMembers = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->username;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->getRole();
        // guarantee every user at least has ROLE_USER
        $plainRoles[] = 'ROLE_USER';
        /** @var Role $role */
        foreach ($roles as $role) {
            $plainRoles[] = $role->getNama();
        }

        return array_unique($plainRoles);
    }

    /**
     * @see UserInterface
     */
    public function getRole(): array
    {
        $roles = $this->role->toArray();

        return array_unique($roles);
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $password): void
    {
        $this->plainPassword = $password;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getLocked(): ?bool
    {
        return $this->locked;
    }

    public function setLocked(bool $locked): self
    {
        $this->locked = $locked;

        return $this;
    }

    public function getTwoFactorEnabled(): ?bool
    {
        return $this->twoFactorEnabled;
    }

    public function setTwoFactorEnabled(bool $twoFactorEnabled): self
    {
        $this->twoFactorEnabled = $twoFactorEnabled;

        return $this;
    }

    public function getLastChange(): ?DateTimeInterface
    {
        return $this->lastChange;
    }

    public function setLastChange(DateTimeInterface $lastChange): self
    {
        $this->lastChange = $lastChange;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setLastChangeValue(): void
    {
        $this->lastChange = new DateTimeImmutable(true);
    }

    /**
     * @return Collection|UserTwoFactor[]
     */
    public function getUserTwoFactors(): Collection
    {
        return $this->userTwoFactors;
    }

    public function addUserTwoFactor(UserTwoFactor $userTwoFactor): self
    {
        if (!$this->userTwoFactors->contains($userTwoFactor)) {
            $this->userTwoFactors[] = $userTwoFactor;
            $userTwoFactor->setUser($this);
        }

        return $this;
    }

    public function removeUserTwoFactor(UserTwoFactor $userTwoFactor): self
    {
        if ($this->userTwoFactors->contains($userTwoFactor)) {
            $this->userTwoFactors->removeElement($userTwoFactor);
            // set the owning side to null (unless already changed)
            if ($userTwoFactor->getUser() === $this) {
                $userTwoFactor->setUser(null);
            }
        }

        return $this;
    }

    public function getPegawai(): ?Pegawai
    {
        return $this->pegawai;
    }

    public function setPegawai(Pegawai $pegawai): self
    {
        $this->pegawai = $pegawai;

        // set the owning side of the relation if necessary
        if ($pegawai->getUser() !== $this) {
            $pegawai->setUser($this);
        }

        return $this;
    }

    public function addRole(Role $role): self
    {
        if (!$this->role->contains($role)) {
            $this->role[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->role->contains($role)) {
            $this->role->removeElement($role);
        }

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getOwnedGroups(): Collection
    {
        return $this->ownedGroups;
    }

    public function addOwnedGroup(Group $ownedGroup): self
    {
        if (!$this->ownedGroups->contains($ownedGroup)) {
            $this->ownedGroups[] = $ownedGroup;
            $ownedGroup->setOwner($this);
        }

        return $this;
    }

    public function removeOwnedGroup(Group $ownedGroup): self
    {
        if ($this->ownedGroups->contains($ownedGroup)) {
            $this->ownedGroups->removeElement($ownedGroup);
            // set the owning side to null (unless already changed)
            if ($ownedGroup->getOwner() === $this) {
                $ownedGroup->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|GroupMember[]
     */
    public function getGroupMembers(): Collection
    {
        return $this->groupMembers;
    }

    public function addGroupMember(GroupMember $groupMember): self
    {
        if (!$this->groupMembers->contains($groupMember)) {
            $this->groupMembers[] = $groupMember;
            $groupMember->setUser($this);
        }

        return $this;
    }

    public function removeGroupMember(GroupMember $groupMember): self
    {
        if ($this->groupMembers->contains($groupMember)) {
            $this->groupMembers->removeElement($groupMember);
            // set the owning side to null (unless already changed)
            if ($groupMember->getUser() === $this) {
                $groupMember->setUser(null);
            }
        }

        return $this;
    }
}
