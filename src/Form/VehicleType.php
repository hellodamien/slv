<?php

namespace App\Form;

use App\Entity\Model;
use App\Entity\Option;
use App\Entity\Type;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('passengers', null, [
                'label' => 'Nombre maximal de passagers',
                'attr' => [
                    'min' => 1,
                    'class' => 'form-control',
                ],
            ])
            ->add('dailyRent', null, [
                'label' => 'Prix de location par jour',
                'attr' => [
                    'min' => 1,
                    'class' => 'form-control',
                ],
            ])
            ->add('odometer', null, [
                'label' => 'Kilométrage',
                'attr' => [
                    'min' => 0,
                    'class' => 'form-control',
                ],
            ])
            ->add('licensePlate', null, [
                'label' => 'Plaque d\'immatriculation',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('productionYear', null, [
                'label' => 'Année de production',
                'attr' => [
                    'min' => 1900,
                    'class' => 'form-control',
                ],
            ])
            ->add('picture', null, [
                'label' => 'Photo',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-check',
                ],
            ])
            ->add('model', EntityType::class, [
                'class' => Model::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
