<?php

namespace App\Form;

use App\Entity\Localisation;
use App\Entity\SnowData;
use App\Repository\LocalisationRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SnowDataType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('localisation', EntityType::class, [
                'class' => Localisation::class,
                'choice_label' => 'name',
                'query_builder' => function (LocalisationRepository $localisationRepository) {
                    return $localisationRepository->createQueryBuilder("l")
                    ->andWhere("l.user = :user")
                    ->setParameter("user", $this->security->getUser());
                }
            ])
            ->add('snowFresh', IntegerType::class, [
                'label' => 'Neige fraîche (cm)',
                'required' => false
            ])
            ->add('snowTotal', IntegerType::class, [
                'label' => 'Neige totale (cm)',
                'required' => false
            ])
            ->add('snow', CheckboxType::class, [
                'label' => 'Flocons observés',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SnowData::class,
        ]);
    }
}
