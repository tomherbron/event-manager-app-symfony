<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Utilisateur;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('telephone', TextType::class)
            ->add('email', TextType::class)
            ->add('password', PasswordType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe doit correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'password'],
                'second_options' => ['label' => 'Repeat password'],
            ])
            ->add('campus', EntityType::class,[
                'class'=>Campus::class,
                'choice_label'=>'nom',
                'query_builder'=>function(CampusRepository $campusRepository){
                $qb = $campusRepository->createQueryBuilder('u');
                $qb->addOrderBy('u.nom', 'ASC');
                return $qb;
                }

                ])
            ->add('photo', FileType::class, [
                'mapped'=>false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
