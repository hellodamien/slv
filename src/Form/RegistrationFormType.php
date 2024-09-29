<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\DrivingLicense;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez un mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 256,
                    ]),
                ],
            ])
            ->add('firstName', null, [
                'label' => 'Prénom',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('lastName', null, [
                'label' => 'Nom',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('address', null, [
                'label' => 'Adresse',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('zipCode', null, [
                'label' => 'Code postal',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('city', null, [
                'label' => 'Ville',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('phoneNumber', null, [
                'label' => 'Téléphone',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('drivingLicenses', EntityType::class, [
                'label' => 'Permis de conduire',
                'class' => DrivingLicense::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
