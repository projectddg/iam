<?php

namespace App\Entity\Organisasi;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Core\Role;
use App\Entity\Pegawai\JabatanPegawai;
use App\Repository\Organisasi\JabatanRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=JabatanRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="jabatan", indexes={
 *     @ORM\Index(name="idx_jabatan_nama_status", columns={"id", "nama", "level", "jenis"}),
 *     @ORM\Index(name="idx_jabatan_legacy", columns={"id", "legacy_kode"}),
 *     @ORM\Index(name="idx_jabatan_relation", columns={"id", "eselon_id"}),
 *     @ORM\Index(name="idx_jabatan_active", columns={"id", "nama", "legacy_kode",
 *          "tanggal_aktif", "tanggal_nonaktif"}),

 * })
 * Disable second level cache for further analysis
 * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
#[ApiResource(
    collectionOperations: [
        'get' => [
            'security' => 'is_granted("ROLE_USER")',
            'security_message' => 'Only a valid user can access this.'
        ],
        'post' => [
            'security'=>'is_granted("ROLE_APLIKASI") or is_granted("ROLE_ADMIN") or is_granted("ROLE_UPK_PUSAT")',
            'security_message'=>'Only admin/app can add new resource to this entity type.'
        ]
    ],
    itemOperations: [
        'get' => [
            'security' => 'is_granted("ROLE_USER")',
            'security_message' => 'Only a valid user can access this.'
        ],
        'put' => [
            'security' => 'is_granted("ROLE_APLIKASI") or is_granted("ROLE_ADMIN") or is_granted("ROLE_UPK_PUSAT")',
            'security_message' => 'Only admin/app can add new resource to this entity type.'
        ],
        'patch' => [
            'security' => 'is_granted("ROLE_APLIKASI") or is_granted("ROLE_ADMIN") or is_granted("ROLE_UPK_PUSAT")',
            'security_message' => 'Only admin/app can add new resource to this entity type.'
        ],
        'delete' => [
            'security' => 'is_granted("ROLE_APLIKASI") or is_granted("ROLE_ADMIN") or is_granted("ROLE_UPK_PUSAT")',
            'security_message' => 'Only admin/app can add new resource to this entity type.'
        ],
    ],
    attributes: [
        'security' => 'is_granted("ROLE_USER")',
        'security_message' => 'Only a valid user can access this.',
        'order' => [
            'level' => 'ASC',
            'nama' => 'ASC'
        ]
    ],
    denormalizationContext: [
        'groups' => ['jabatan:write'],
        'swagger_definition_name' => 'write'
    ],
    normalizationContext: [
        'groups' => ['jabatan:read'],
        'swagger_definition_name' => 'read'
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'id' => 'exact',
    'nama' => 'ipartial',
    'jenis' => 'ipartial',
    'legacyKode' => 'partial',
    'legacyKodeJabKeu' => 'partial',
    'legacyKodeGradeKeu' => 'partial',
    'eselon.id' => 'exact',
    'eselon.nama' => 'ipartial',
    "eselon.kode" => "ipartial",
    'units.id' => 'exact',
    'units.nama' => 'ipartial',
    'units.legacyKode' => 'partial',
    'kantor.id' => 'exact',
    'kantor.nama' => 'ipartial',
    'kantor.legacyKode' => 'partial',
    'kantor.legacyKodeKpp' => 'partial',
    'kantor.legacyKodeKanwil' => 'partial',
])]
#[ApiFilter(DateFilter::class, properties: ['tanggalAktif', 'tanggalNonaktif'])]
#[ApiFilter(NumericFilter::class, properties: ['level'])]
#[ApiFilter(PropertyFilter::class)]
class Jabatan
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Groups({"jabatan:read", "jabatan:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Assert\NotBlank()
     * @Groups({"jabatan:read", "jabatan:write"})
     * @Groups({"user:read"})
     * @Groups({"pegawai:read"})
     */
    private ?string $nama;

    /**
     * @ORM\Column(type="integer")
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Assert\NotNull()
     * @Groups({"jabatan:read", "jabatan:write"})
     * @Groups({"pegawai:read"})
     */
    private ?int $level;

    /**
     * @ORM\Column(type="string", length=255)
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Assert\NotNull()
     * @Groups({"jabatan:read", "jabatan:write"})
     * @Groups({"pegawai:read"})
     */
    private ?string $jenis;

    /**
     * @ORM\Column(type="datetime_immutable")
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Assert\NotNull()
     * @Groups({"jabatan:read", "jabatan:write"})
     */
    private ?DateTimeImmutable $tanggalAktif;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Groups({"jabatan:read", "jabatan:write"})
     */
    private ?DateTimeImmutable $tanggalNonaktif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Groups({"jabatan:read", "jabatan:write"})
     */
    private ?string $sk;

    /**
     * @ORM\ManyToOne(targetEntity=Eselon::class, inversedBy="jabatans")
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Assert\Valid()
     * @Groups({"jabatan:read", "jabatan:write"})
     */
    private $eselon;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Groups({"jabatan:read", "jabatan:write"})
     */
    private ?string $legacyKode;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Groups({"jabatan:read", "jabatan:write"})
     */
    private ?string $legacyKodeJabKeu;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Groups({"jabatan:read", "jabatan:write"})
     */
    private ?string $legacyKodeGradeKeu;

    /**
     * @ORM\OneToMany(targetEntity=JabatanPegawai::class, mappedBy="jabatan", orphanRemoval=true)
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Groups({"jabatan:read", "jabatan:write"})
     */
    private $jabatanPegawais;

    /**
     * @ORM\ManyToMany(targetEntity=Unit::class, inversedBy="jabatans")
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Assert\Valid()
     * @Groups({"jabatan:read", "jabatan:write"})
     */
    private $units;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="jabatans")
     * @Groups({"jabatan:read", "jabatan:write"})
     */
    private $roles;

    /**
     * @ORM\ManyToOne(targetEntity=GroupJabatan::class, inversedBy="jabatans")
     * Disable second level cache for further analysis
     * @ ORM\Cache(usage="NONSTRICT_READ_WRITE")
     * @Groups({"jabatan:read", "jabatan:write"})
     */
    private $groupJabatan;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->jabatanPegawais = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->nama;
    }

    public function getId(): Uuid
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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getJenis(): ?string
    {
        return $this->jenis;
    }

    public function setJenis(string $jenis): self
    {
        $this->jenis = $jenis;

        return $this;
    }

    public function getTanggalAktif(): ?DateTimeImmutable
    {
        return $this->tanggalAktif;
    }

    public function setTanggalAktif(DateTimeImmutable $tanggalAktif): self
    {
        $this->tanggalAktif = $tanggalAktif;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function setTanggalAktifValue(): void
    {
        // Only create tanggal Aktif if no date provided
        if (!isset($this->tanggalAktif)) {
            $this->tanggalAktif = new DateTimeImmutable();
        }
    }

    public function getTanggalNonaktif(): ?DateTimeImmutable
    {
        return $this->tanggalNonaktif;
    }

    public function setTanggalNonaktif(?DateTimeImmutable $tanggalNonaktif): self
    {
        $this->tanggalNonaktif = $tanggalNonaktif;

        return $this;
    }

    public function getSk(): ?string
    {
        return $this->sk;
    }

    public function setSk(?string $sk): self
    {
        $this->sk = $sk;

        return $this;
    }

    public function getEselon(): ?Eselon
    {
        return $this->eselon;
    }

    public function setEselon(?Eselon $eselon): self
    {
        $this->eselon = $eselon;

        return $this;
    }

    public function getLegacyKode(): ?string
    {
        return $this->legacyKode;
    }

    public function setLegacyKode(?string $legacyKode): self
    {
        $this->legacyKode = $legacyKode;

        return $this;
    }

    public function getLegacyKodeJabKeu(): ?string
    {
        return $this->legacyKodeJabKeu;
    }

    public function setLegacyKodeJabKeu(?string $legacyKodeJabKeu): self
    {
        $this->legacyKodeJabKeu = $legacyKodeJabKeu;

        return $this;
    }

    public function getLegacyKodeGradeKeu(): ?string
    {
        return $this->legacyKodeGradeKeu;
    }

    public function setLegacyKodeGradeKeu(?string $legacyKodeGradeKeu): self
    {
        $this->legacyKodeGradeKeu = $legacyKodeGradeKeu;

        return $this;
    }

    /**
     * @return Collection|JabatanPegawai[]
     */
    public function getJabatanPegawais(): Collection|array
    {
        return $this->jabatanPegawais;
    }

    public function addJabatanPegawai(JabatanPegawai $jabatanPegawai): self
    {
        if (!$this->jabatanPegawais->contains($jabatanPegawai)) {
            $this->jabatanPegawais[] = $jabatanPegawai;
            $jabatanPegawai->setJabatan($this);
        }

        return $this;
    }

    public function removeJabatanPegawai(JabatanPegawai $jabatanPegawai): self
    {
        if ($this->jabatanPegawais->contains($jabatanPegawai)) {
            $this->jabatanPegawais->removeElement($jabatanPegawai);
            // set the owning side to null (unless already changed)
            if ($jabatanPegawai->getJabatan() === $this) {
                $jabatanPegawai->setJabatan(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Unit[]
     */
    public function getUnits(): Collection|array
    {
        return $this->units;
    }

    public function addUnit(Unit $unit): self
    {
        if (!$this->units->contains($unit)) {
            $this->units[] = $unit;
        }

        return $this;
    }

    public function removeUnit(Unit $unit): self
    {
        if ($this->units->contains($unit)) {
            $this->units->removeElement($unit);
        }

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoles(): Collection|array
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

    public function getGroupJabatan(): ?GroupJabatan
    {
        return $this->groupJabatan;
    }

    public function setGroupJabatan(?GroupJabatan $groupJabatan): self
    {
        $this->groupJabatan = $groupJabatan;

        return $this;
    }
}
