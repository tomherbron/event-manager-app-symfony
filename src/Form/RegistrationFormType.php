<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Utilisateur;
use App\Repository\CampusRepository;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
//            ->add('agreeTerms', CheckboxType::class, [
//                'mapped' => false,
//                'constraints' => [
//                    new IsTrue([
//                        'message' => 'You should agree to our terms.',
//                    ]),
//                ],
//            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])

            ->add('nom', TextType::class,[
                'label'=>'Nom :'
            ])
            ->add('prenom', TextType::class, [
                'label'=>'Prénom :'
                ])
            ->add('telephone', TextType::class,[
                'label'=> 'Téléphone :'
            ])
            ->add('email', EmailType::class, [
                'label'=> 'E-mail :'
            ])
            ->add('campus', EntityType::class,[
                'label'=>'Campus :',
                'class'=>Campus::class,
                'choice_label'=>'nom',
                'query_builder'=>function(CampusRepository $campusRepository) {
                    $qb = $campusRepository->createQueryBuilder('u');
                    $qb->addOrderBy('u.nom', 'ASC');
                    return $qb;
                }
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