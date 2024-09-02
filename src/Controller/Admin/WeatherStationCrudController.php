<?php

namespace App\Controller\Admin;

use App\Entity\WeatherStation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WeatherStationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WeatherStation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('serial'),
            TextField::new('name'),
            IntegerField::new('elevation'),
        ];
    }
}
