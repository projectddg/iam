<?php

namespace App\Entity\Core;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Aplikasi\Modul;
use App\Repository\Core\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     attributes={"order"={"nama": "ASC"}}
 * )
 * @ORM\Entity(repositoryClass=PermissionRepository::class)
 * @ORM\Table(name="permission", indexes={
 *     @ORM\Index(name="idx_permission_nama_status", columns={"id", "nama", "system_name"}),
 * })
 * @ApiFilter(SearchFilter::class, properties={
 *     "nama": "ipartial",
 *     "systemName": "ipartial",
 *     "deskripsi": "ipartial",
 * })
 * @ApiFilter(PropertyFilter::class)
 */
class Permission
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nama;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $systemName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $deskripsi;

    /**
     * @ORM\ManyToMany(targetEntity=Modul::class, inversedBy="permissions")
     * @Assert\NotNull()
     */
    private $modul;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="permissions", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="role_permission",
     *     joinColumns={
     *          @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     *     }
     * )
     * @Assert\NotNull()
     */
    private $roles;

    public function __construct()
    {
        $this->modul = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getNama(): ?string
    {
        return $this->nama;
    }

    public function setNama(string $nama): self
    {
        $this->nama = $nama;

        return $this;
    }

    public function getSystemName(): ?string
    {
        return $this->systemName;
    }

    public function setSystemName(string $systemName): self
    {
        $this->systemName = $systemName;

        return $this;
    }

    public function getDeskripsi(): ?string
    {
        return $this->deskripsi;
    }

    public function setDeskripsi(?string $deskripsi): self
    {
        $this->deskripsi = $deskripsi;

        return $this;
    }

    /**
     * @return Collection|Modul[]
     */
    public function getModul(): Collection
    {
        return $this->modul;
    }

    public function addModul(Modul $modul): self
    {
        if (!$this->modul->contains($modul)) {
            $this->modul[] = $modul;
        }

        return $this;
    }

    public function removeModul(Modul $modul): self
    {
        if ($this->modul->contains($modul)) {
            $this->modul->removeElement($modul);
        }

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

        return $this;
    }
}
