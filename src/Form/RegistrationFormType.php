<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'attr' =>[
                    'class' => 'form-control'
                ],
                ])
            ->add('email', EmailType::class,[
                'attr' =>[
                    'class' => 'form-control'
                ],
                ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Soit d\'accord. Obéis!.',
                    ]),
                ],
                'label' => "Etre d'accord avec les petites lignes",
                'attr' =>[
                    'class' => 'form-check-input',
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class, 
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'first_options'  => [
                    'label' => 'Password',
                    'attr' => [           // pas bon !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        'oups' => 2 ,
                        'class' => 'form-control',
                        'placeholder' => 'Mot de Passe',                        
                    ],
                ],
                'second_options' => [
                    'label' => 'Password',
                    'attr' => [           // pas bon !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                        'oups' => 2 ,
                        'class' => 'form-control',
                        'placeholder' => 'Mot de Passe',                        
                    ],
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}/',
                        'match' => true,
                        'message' => 'Le mot de passe doit comporter 12 caractères, incluant majuscules, minuscules, chiffres et caratères spéciaux usuels...',
                    ])
                ],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

/*
// ajouter une regex , recommandation cnil - 12 caractères; Maj, Min, Chiffres, Spéciaux... OK
^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$
// repeated type

// petit oeil pour voir mdp 

// message d'erreur en français

*/