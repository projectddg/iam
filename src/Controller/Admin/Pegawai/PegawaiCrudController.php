<?php

namespace App\Controller\Admin\Pegawai;

use App\Entity\Pegawai\Pegawai;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PegawaiCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pegawai::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nama', 'Nama Pegawai')
                ->setRequired(true)
                ->setMaxLength(255),
            AssociationField::new('user')
                ->setRequired(true)
                ->autocomplete()
                ->setHelp('Silakan pilih nama user yang telah didaftarkan'),
            TextField::new('tempatLahir', 'Tempat lahir')
                ->setRequired(true)
                ->setMaxLength(255)
                ->hideOnIndex(),
            DateField::new('tanggalLahir', 'Tanggal Lahir')
                ->renderAsNativeWidget()
                ->hideOnIndex(),
            BooleanField::new('pensiun', 'Pensiun')
                ->setHelp('Pegawai Pensiun?')
                ->hideOnIndex(),
            TextField::new('npwp','NPWP')
                ->hideOnIndex(),
            TextField::new('nik','NIK')
                ->setRequired(true)
                ->setMaxLength(16)
                ->hideOnIndex(),
            TextField::new('nip9','IP SIKKA')
                ->setRequired(true)
                ->setMaxLength(9)
                ->hideOnIndex(),
            TextField::new('nip18','NIP')
                ->setRequired(true)
                ->setMaxLength(18)
                ->hideOnIndex(),
            TextField::new('pangkat','Pangkat')
                ->setRequired(false)
                ->setMaxLength(255)
                ->hideOnIndex(),
            BooleanField::new('onLeave', 'On Leave (Sedang Cuti)?')
                ->setHelp('Pegawai on Leave/ sedang cuti?'),
        ];
    }
}
