<?php


namespace App\Helper;


use ApiPlatform\Api\IriConverterInterface;
use App\Entity\Aplikasi\Aplikasi;
use App\Entity\Core\Permission;
use App\Entity\Core\Role;
use App\Entity\Pegawai\JabatanPegawai;
use DateTimeImmutable;
use JetBrains\PhpStorm\ArrayShape;

class RoleHelper
{

    /**
     * @param JabatanPegawai $jabatanPegawai
     * @return array
     */
    public static function getRolesFromJabatanPegawai(JabatanPegawai $jabatanPegawai): array
    {
        // Get role by jabatan pegawai
        // Jenis Relasi Role: 1 => user, 2 => jabatan, 3 => unit, 4 => kantor, 5 => eselon,
        // 6 => jenis kantor, 7 => group, 8 => jabatan + unit, 9 => jabatan + kantor,
        // 10 => jabatan + unit + kantor, 11 => jabatan + unit + jenis kantor"
        $roles = [];
        $jabatan = $jabatanPegawai->getJabatan();
        $unit = $jabatanPegawai->getUnit();
        $kantor = $jabatanPegawai->getKantor();
        $jenisKantorKantor = $kantor?->getJenisKantor();
        $jenisKantorUnit = $unit?->getJenisKantor();
        if ($jenisKantorKantor === $jenisKantorUnit
            && null !== $jenisKantorKantor
            && null !== $jenisKantorUnit
        ) {
            $jenisKantor = $jenisKantorKantor;
        } else {
            $jenisKantor = null;
        }

        // check from jabatan
        if (null !== $jabatan) {
            // direct role from jabatan/ jabatan unit/ jabatan kantor/ combination
            foreach ($jabatan->getRoles() as $role) {
                if (2 === $role->getJenis()) {
                    $roles[] = $role;
                } elseif (8 === $role->getJenis() && $role->getUnits()->contains($unit)) {
                    $roles[] = $role;
                } elseif (9 === $role->getJenis() && $role->getKantors()->contains($kantor)) {
                    $roles[] = $role;
                } elseif (10 === $role->getJenis()
                    && $role->getUnits()->contains($unit)
                    && $role->getKantors()->contains($kantor)
                ) {
                    $roles[] = $role;
                } elseif (11 === $role->getJenis()
                    && $role->getUnits()->contains($unit)
                    && $role->getJenisKantors()->contains($jenisKantor)
                ) {
                    $roles[] = $role;
                }
            }

            // get eselon level
            $eselon = $jabatan->getEselon();
            if (null !== $eselon) {
                foreach ($eselon->getRoles() as $role) {
                    if (5 === $role->getJenis()) {
                        $roles[] = $role;
                    }
                }
            }
        }

        // get role from unit
        if (null !== $unit) {
            foreach ($unit->getRoles() as $role) {
                if (3 === $role->getJenis()) {
                    $roles[] = $role;
                }
            }
        }

        // get role from kantor
        if (null !== $kantor) {
            foreach ($kantor->getRoles() as $role) {
                if (4 === $role->getJenis()) {
                    $roles[] = $role;
                }
            }
        }

        // get role from eselon
        if (null !== $jabatan->getEselon()) {
            foreach ($jabatan->getEselon()->getRoles() as $role) {
                if (5 === $role->getJenis()) {
                    $roles[] = $role;
                }
            }
        }

        // get role from jenis kantor
        if (null !== $jenisKantor) {
            foreach ($jenisKantor->getRoles() as $role) {
                if (6 === $role->getJenis()) {
                    $roles[] = $role;
                }
            }
        }

        return $roles;
    }

    /**
     * @param JabatanPegawai $jabatanPegawai
     * @return array
     */
    public static function getPlainRolesNameFromJabatanPegawai(JabatanPegawai $jabatanPegawai): array
    {
        $roles = self::getRolesFromJabatanPegawai($jabatanPegawai);
        $plainRoles = [];

        /** @var Role $role */
        foreach ($roles as $role) {
            if ($role->getStartDate() <= new DateTimeImmutable('now')
                && ($role->getEndDate() >= new DateTimeImmutable('now')
                    || null === $role->getEndDate())
            ) {
                $plainRoles[] = $role->getNama();
            }
        }

        return array_values(array_unique($plainRoles));
    }

    /**
     * @param Role $role
     * @param IriConverterInterface $iriConverter
     * @return array
     */
    #[ArrayShape([
        'iri' => "string",
        'id' => "null|string",
        'nama' => "null|string",
        'deskripsi' => "null|string",
        'level' => "int|null"
    ])]
    public static function createRoleDefaultResponseFromRole(Role $role, IriConverterInterface $iriConverter): array
    {
        return [
            'iri' => $iriConverter->getIriFromResource($role),
            'id' => $role->getId(),
            'nama' => $role->getNama(),
            'deskripsi' => $role->getDeskripsi(),
            'level' => $role->getLevel()
        ];
    }

    /**
     * @param array $roles
     * @param IriConverterInterface $iriConverter
     * @return array
     */
    public static function createRoleDefaultResponseFromArrayOfRoles(array $roles, IriConverterInterface $iriConverter): array
    {
        $response = [];

        /** @var Role $role */
        foreach ($roles as $role) {
            $response[] = self::createRoleDefaultResponseFromRole($role, $iriConverter);
        }

        return $response;
    }

    /**
     * @param Role $role
     * @return array
     */
    public static function getAplikasiByRole(Role $role): array
    {
        $permissions = $role?->getPermissions();
        $listAplikasi = [];
        if (null !== $permissions) {
            /** @var Permission $permission */
            foreach ($permissions as $permission) {
                $moduls = $permission?->getModul();
                if (null !== $moduls) {
                    foreach ($moduls as $modul) {
                        if ($modul->getStatus()) {
                            /** @var Aplikasi $aplikasi */
                            $aplikasi = $modul->getAplikasi();
                            if ($aplikasi->getStatus() && !in_array($aplikasi, $listAplikasi, true)) {
                                $listAplikasi[] = $aplikasi;
                            }
                        }
                    }
                }
            }
        }
        return $listAplikasi;
    }

    /**
     * @param array $roles
     * @return array
     */
    public static function getAplikasiByArrayOfRoles(array $roles): array
    {
        $listAplikasi = [];
        /** @var Role $role */
        foreach ($roles as $role) {
            $listAplikasi[] = self::getAplikasiByRole($role);
        }
        return array_merge(...$listAplikasi);
    }

    /**
     * @param Role $role
     * @return array
     */
    public static function getAllAplikasiByRole(Role $role): array
    {
        $permissions = $role?->getPermissions();
        $listAplikasi = [];
        if (null !== $permissions) {
            /** @var Permission $permission */
            foreach ($permissions as $permission) {
                $moduls = $permission?->getModul();
                if (null !== $moduls) {
                    foreach ($moduls as $modul) {
                        $aplikasi = $modul->getAplikasi();
                        if (!in_array($aplikasi, $listAplikasi, true)) {
                            $listAplikasi[] = $aplikasi;
                        }
                    }
                }
            }
        }
        return $listAplikasi;
    }

    /**
     * @param array $roles
     * @return array
     */
    public static function getAllAplikasiByArrayOfRoles(array $roles): array
    {
        $listAplikasi = [];
        /** @var Role $role */
        foreach ($roles as $role) {
            $listAplikasi[] = self::getAllAplikasiByRole($role);
        }
        return array_merge(...$listAplikasi);
    }
}
