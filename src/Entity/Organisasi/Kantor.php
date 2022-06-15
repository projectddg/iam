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
use App\Repository\Organisasi\KantorRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Kantor Class
 */
#[ORM\Entity(
    repositoryClass: KantorRepository::class
)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(
    name: 'kantor'
)]
#[ORM\Index(
    columns: [
        'id',
        'nama',
        'level',
        'sk'
    ],
    name: 'idx_kantor_nama_status'
)]
#[ORM\Index(
    columns: [
        'id',
        'legacy_kode',
        'legacy_kode_kpp',
        'legacy_kode_kanwil'
    ],
    name: 'idx_kantor_legacy'
)]
#[ORM\Index(
    columns: [
        'id',
        'jenis_kantor_id',
        'parent_id',
        'level',
        'pembina_id'
    ],
    name: 'idx_kantor_relation'
)]
#[ORM\Index(
    columns: [
        'id',
        'latitude',
        'longitude'
    ],
    name: 'idx_kantor_location'
)]
#[ORM\Index(
    columns: [
        'id',
        'nama',
        'legacy_kode',
        'tanggal_aktif',
        'tanggal_nonaktif'
    ],
    name: 'idx_kantor_active'
)]
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
        'groups' => ['kantor:write'],
        'swagger_definition_name' => 'write'
    ],
    normalizationContext: [
        'groups' => ['kantor:read'],
        'swagger_definition_name' => 'read'
    ],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'id' => 'exact',
        'nama' => 'ipartial',
        'sk' => 'ipartial',
        'legacyKode' => 'partial',
        'legacyKodeKpp' => 'partial',
        'legacyKodeKanwil' => 'partial',
        'parent.id' => 'exact',
        'parent.nama' => 'iexact',
        'parent.legacyKode' => 'exact',
        'childs.id' => 'exact',
        'childs.nama' => 'iexact',
        'childs.legacyKode' => 'exact',
        'pembina.id' => 'exact',
        'pembina.nama' => 'iexact',
        'pembina.legacyKode' => 'exact',
        'membina.id' => 'exact',
        'membina.nama' => 'iexact',
        'membina.legacyKode' => 'exact',
        'alamat' => 'ipartial',
        'telp' => 'exact',
        'fax' => 'exact',
        'zonaWaktu' => 'exact',
        'jenisKantor.id' => 'exact',
        'jenisKantor.nama' => 'iexact',
        'jenisKantor.tipe' => 'iexact',
    ]
)]
#[ApiFilter(
    DateFilter::class,
    properties: [
        'tanggalAktif',
        'tanggalNonaktif'
    ]
)]
#[ApiFilter(
    NumericFilter::class,
    properties: [
        'level',
        'jenisKantor.klasifikasi',
        'jenisKantor.legacyId',
        'jenisKantor.legacyKode'
    ]
)]
#[ApiFilter(PropertyFilter::class)]
class Kantor
{
    #[ORM\Id]
    #[ORM\Column(
        type: 'uuid',
        unique: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private UuidV4 $id;

    #[ORM\Column(
        type: 'string',
        length: 255
    )]
    #[Assert\NotBlank]
    #[Groups(
        groups: [
            'pegawai:read',
            'user:read',
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?string $nama;

    #[ORM\ManyToOne(
        targetEntity: JenisKantor::class,
        inversedBy: 'kantors'
    )]
    #[ORM\JoinColumn(
        nullable: false
    )]
    #[Assert\NotNull]
    #[Assert\Valid]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?JenisKantor $jenisKantor;

    #[ORM\Column(
        type: 'integer',
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?int $level;

    #[ORM\ManyToOne(
        targetEntity: Kantor::class,
        inversedBy: 'childs'
    )]
    #[Assert\Valid]
    #[Groups(
        groups: [
            'kantor:write'
        ]
    )]
    private ?Kantor $parent;

    #[ORM\OneToMany(
        mappedBy: 'parent',
        targetEntity: Kantor::class
    )]
    private Collection $childs;

    #[ORM\Column(
        type: 'datetime_immutable'
    )]
    #[Assert\NotNull]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?DateTimeImmutable $tanggalAktif;

    #[ORM\Column(
        type: 'datetime_immutable',
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?DateTimeImmutable $tanggalNonaktif;

    #[ORM\Column(
        type: 'string',
        length: 255,
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?string $sk;

    #[ORM\Column(
        type: 'text',
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?string $alamat;

    #[ORM\Column(
        type: 'string',
        length: 255,
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?string $telp;

    #[ORM\Column(
        type: 'string',
        length: 255,
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?string $fax;

    #[ORM\Column(
        type: 'string',
        length: 4,
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?string $zonaWaktu;

    #[ORM\Column(
        type: 'float',
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?float $latitude;

    #[ORM\Column(
        type: 'float',
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?float $longitude;

    #[ORM\Column(
        type: 'string',
        length: 10,
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?string $legacyKode;

    #[ORM\Column(
        type: 'string',
        length: 3,
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?string $legacyKodeKpp;

    #[ORM\Column(
        type: 'string',
        length: 3,
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?string $legacyKodeKanwil;

    #[ORM\OneToMany(
        mappedBy: 'kantor',
        targetEntity: JabatanPegawai::class,
        orphanRemoval: true
    )]
    #[Groups(
        groups: [
            'kantor:write'
        ]
    )]
    private Collection $jabatanPegawais;

    #[ORM\ManyToMany(
        targetEntity: Role::class,
        mappedBy: 'kantors'
    )]
    #[Groups(
        groups: [
            'kantor:write'
        ]
    )]
    private Collection $roles;

    #[ORM\ManyToOne(
        targetEntity: Kantor::class,
        inversedBy: 'membina'
    )]
    #[Groups(
        groups: [
            'kantor:write'
        ]
    )]
    private ?Kantor $pembina;

    #[ORM\OneToMany(
        mappedBy: 'pembina',
        targetEntity: Kantor::class
    )]
    #[Groups(
        groups: [
            'kantor:write'
        ]
    )]
    private Collection $membina;

    #[ORM\Column(
        type: 'uuid',
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:write'
        ]
    )]
    private ?UuidV4 $provinsi;

    #[ORM\Column(
        type: 'uuid',
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?UuidV4 $kabupatenKota;

    #[ORM\Column(
        type: 'uuid',
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?UuidV4 $kecamatan;

    #[ORM\Column(
        type: 'uuid',
        nullable: true
    )]
    #[Groups(
        groups: [
            'kantor:read',
            'kantor:write'
        ]
    )]
    private ?UuidV4 $kelurahan;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->childs = new ArrayCollection();
        $this->jabatanPegawais = new ArrayCollection();
        $this->roles = new ArrayCollection();
        $this->membina = new ArrayCollection();
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

    public function getJenisKantor(): ?JenisKantor
    {
        return $this->jenisKantor;
    }

    public function setJenisKantor(?JenisKantor $jenisKantor): self
    {
        $this->jenisKantor = $jenisKantor;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChilds(): Collection|array
    {
        return $this->childs;
    }

    public function addChild(self $child): self
    {
        if (!$this->childs->contains($child)) {
            $this->childs[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->childs->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

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

    #[ORM\PrePersist]
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

    public function getAlamat(): ?string
    {
        return $this->alamat;
    }

    public function setAlamat(?string $alamat): self
    {
        $this->alamat = $alamat;

        return $this;
    }

    public function getTelp(): ?string
    {
        return $this->telp;
    }

    public function setTelp(?string $telp): self
    {
        $this->telp = $telp;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getZonaWaktu(): ?string
    {
        return $this->zonaWaktu;
    }

    public function setZonaWaktu(?string $zonaWaktu): self
    {
        $this->zonaWaktu = $zonaWaktu;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

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

    public function getLegacyKodeKpp(): ?string
    {
        return $this->legacyKodeKpp;
    }

    public function setLegacyKodeKpp(?string $legacyKodeKpp): self
    {
        $this->legacyKodeKpp = $legacyKodeKpp;

        return $this;
    }

    public function getLegacyKodeKanwil(): ?string
    {
        return $this->legacyKodeKanwil;
    }

    public function setLegacyKodeKanwil(?string $legacyKodeKanwil): self
    {
        $this->legacyKodeKanwil = $legacyKodeKanwil;

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
            $jabatanPegawai->setKantor($this);
        }

        return $this;
    }

    public function removeJabatanPegawai(JabatanPegawai $jabatanPegawai): self
    {
        if ($this->jabatanPegawais->contains($jabatanPegawai)) {
            $this->jabatanPegawais->removeElement($jabatanPegawai);
            // set the owning side to null (unless already changed)
            if ($jabatanPegawai->getKantor() === $this) {
                $jabatanPegawai->setKantor(null);
            }
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

    public function getPembina(): ?self
    {
        return $this->pembina;
    }

    public function setPembina(?self $pembina): self
    {
        $this->pembina = $pembina;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getMembina(): Collection|array
    {
        return $this->membina;
    }

    public function addMembina(self $membina): self
    {
        if (!$this->membina->contains($membina)) {
            $this->membina[] = $membina;
            $membina->setPembina($this);
        }

        return $this;
    }

    public function removeMembina(self $membina): self
    {
        if ($this->membina->removeElement($membina)) {
            // set the owning side to null (unless already changed)
            if ($membina->getPembina() === $this) {
                $membina->setPembina(null);
            }
        }

        return $this;
    }

    public function getProvinsi(): ?UuidV4
    {
        return $this->provinsi;
    }

    public function setProvinsi($provinsi): self
    {
        if (Uuid::isValid($provinsi) && UuidV4::isValid($provinsi)) {
            if (is_string($provinsi)) {
                $provinsi = UuidV4::fromString($provinsi);
            }
            $this->provinsi = $provinsi;
        }

        return $this;
    }

    public function getKabupatenKota(): ?UuidV4
    {
        return $this->kabupatenKota;
    }

    public function setKabupatenKota($kabupatenKota): self
    {
        if (Uuid::isValid($kabupatenKota) && UuidV4::isValid($kabupatenKota)) {
            if (is_string($kabupatenKota)) {
                $kabupatenKota = UuidV4::fromString($kabupatenKota);
            }
            $this->kabupatenKota = $kabupatenKota;
        }

        return $this;
    }

    public function getKecamatan(): ?UuidV4
    {
        return $this->kecamatan;
    }

    public function setKecamatan($kecamatan): self
    {
        if (Uuid::isValid($kecamatan) && UuidV4::isValid($kecamatan)) {
            if (is_string($kecamatan)) {
                $kecamatan = UuidV4::fromString($kecamatan);
            }
            $this->kecamatan = $kecamatan;
        }

        return $this;
    }

    public function getKelurahan(): ?UuidV4
    {
        return $this->kelurahan;
    }

    public function setKelurahan($kelurahan): self
    {
        if (Uuid::isValid($kelurahan) && UuidV4::isValid($kelurahan)) {
            if (is_string($kelurahan)) {
                $kelurahan = UuidV4::fromString($kelurahan);
            }
            $this->kelurahan = $kelurahan;
        }

        return $this;
    }
}
