<?php

namespace App\Controller\Admin\Pegawai;

use App\Entity\Pegawai\JabatanPegawai;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JabatanPegawaiCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JabatanPegawai::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('pegawai', 'Pegawai')
                ->autocomplete()
                ->setRequired(true),
            AssociationField::new('jabatan', 'Jabatan')
                ->autocomplete()
                ->setRequired(true),
            AssociationField::new('tipe', 'Tipe Jabatan')
                ->autocomplete()
                ->setRequired(true),
            AssociationField::new('atribut', 'Jabatan Atribut')
                ->autocomplete()
                ->setHelp('Khusus jabatan AR/ PK/ Pelaksana yang memiliki atribut 1-100')
                ->hideOnIndex(),
            AssociationField::new('kantor', 'Kantor')
                ->autocomplete()
                ->setRequired(true),
            AssociationField::new('unit', 'Unit Organisasi')
                ->autocomplete()
                ->setRequired(true),
            TextField::new('referensi', 'Referensi'),
            DateTimeField::new('tanggalMulai', 'Tanggal Mulai')
                ->renderAsNativeWidget()
                ->setRequired(true)
                ->hideOnIndex(),
            DateTimeField::new('tanggalSelesai', 'Tanggal Selesai')
                ->renderAsNativeWidget()
                ->hideOnIndex()
        ];
    }
}
