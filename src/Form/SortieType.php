<?php

namespace App\Form;

use App\Entity\Sortie;
use App\Entity\Utilisateur;
use DateTime;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('dateHeureDebut', DateTimeType::class)
            ->add('duree')
            ->add('dateLimiteInscription', DateType::class,  [
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('nbMaxInscriptions')
            ->add('infos')
            ->add('utilisateurs')
            ->add('organisateur', EntityType::class, [
                'class' => Utilisateur::class
        ])
            ->add('campus')
            ->add('etat')
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
