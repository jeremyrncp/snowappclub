<?php

namespace App\Controller\Admin;

use App\Entity\WeatherData;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WeatherDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WeatherData::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('createdAt'),
            NumberField::new('tInt'),
            NumberField::new('tOut'),
            NumberField::new('rhInt'),
            NumberField::new('rhOut'),
            TextField::new('station')->hideOnForm(),
        ];
    }
}
