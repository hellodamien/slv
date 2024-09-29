<?php

namespace App\Form;

use App\Entity\Reservation;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', null, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'data' => new DateTimeImmutable(),
                'attr' => [
                    'min' => (new DateTimeImmutable())->format('Y-m-d')
                ],
            ])
            ->add('endDate', null, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'data' => (new DateTimeImmutable())->modify('+1 day'),
                'attr' => [
                    'min' => (new DateTimeImmutable())->format('Y-m-d')
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Réserver',
                'attr' => [
                    'class' => 'btn btn-success'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
