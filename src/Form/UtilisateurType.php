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
            ->add('username', TextType::class, [
                'label' => 'Pseudo :'
            ])
            ->add('nom', TextType::class,[
                'label'=> 'Nom :'
            ])
            ->add('prenom', TextType::class,[
                'label'=> 'Prénom :'
            ])
            ->add('telephone', TextType::class,[
                'label'=> 'Téléphone :'
            ])
            ->add('email', TextType::class,[
                'label'=> 'E-mail :'
            ])
            ->add('password', PasswordType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe doit correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe :'],
                'second_options' => ['label' => 'Confirmation mot de passe :']
                ,
            ])
            ->add('campus', EntityType::class,[
                'label'=>'Campus :',
                'class'=>Campus::class,
                'choice_label'=>'nom',
                'query_builder'=>function(CampusRepository $campusRepository){
                $qb = $campusRepository->createQueryBuilder('u');
                $qb->addOrderBy('u.nom', 'ASC');
                return $qb;
                }

                ])
            ->add('photo', FileType::class, [
                'label'=>'Photo :',
                'mapped'=>false,
                'required'=>false,
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
