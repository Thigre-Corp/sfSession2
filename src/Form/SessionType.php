<?php

namespace App\Form;

use App\Entity\Session;
use App\Entity\Formation;
use App\Entity\Stagiaire;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\LessThan;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('nom')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('nombrePlacesTotales')
            ->add('nombrePlacesReservees')
           /* ->add('stagiaires', EntityType::class, [
                'class' => Stagiaire::class,
                'choice_label' => 'id',
                'multiple' => true,
                'attr' =>[
                   'readonly'=> 'true',
                ]
            ]) */
            ->add('stagiairesEnregistres', IntegerType::class, [
                    'mapped' => false,
                    'data' => $options["stagiairesEnregistres"]
                    ])

            ->add('formation', EntityType::class, [
                'class' => Formation::class,
                'choice_label' => 'nom',
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => ['class' => 'btn btn-success']
            ])
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                [$this, 'onPreSubmit']
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
            'stagiairesEnregistres' => 0 ,
        ]);

        $resolver->setAllowedTypes('stagiairesEnregistres', 'int');
    }

    public function onPreSubmit(PreSubmitEvent $event): void
    {
        $dataArray = $event->getData();
        $stagiairesEnregistres = $event->getForm()->getConfig()->getOptions()["stagiairesEnregistres"];
        //dd($stagiairesEnregistres);
        if($dataArray["nombrePlacesTotales"] < ( $dataArray["nombrePlacesReservees"] + $stagiairesEnregistres ))
        {
            $event->getForm()->addError( new FormError('Le nombre de réservation et de stagiaires sont supérieurs au nombre de places totales'));
        }

       // dd($event->getForm()->addError( new FormError('Le nombre de réservation et de stagiaires sont supérieurs au nombre de places totales')));

    }
}


/**********************
 * 
 *  /////////////////// revoir et exporter le pricipe de stagiairesEnregistres dans les autres élements.
 * 
 */