<?php

namespace App\Form;

use App\Entity\Sortie;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('dateHeureDebut', DateTimeType::class, [
                'html5' => true,
                'widget' => 'single_text'
            ] )
            ->add('duree')
            ->add('dateLimiteInscription', DateType::class,  [
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('nbMaxInscriptions')
            ->add('infos', TextareaType::class)
            ->add('campus', ChoiceType::class, ['choices' => [
                'Nantes' => 'Nantes',
                'Chartres-de-Bretagne' => 'Chartres-de-Bretagne',
                'Niort' => 'Niort',
            ], 'expanded' => true, 'multiple' => true, 'mapped' => false

            ])
            ->add('lieu')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
