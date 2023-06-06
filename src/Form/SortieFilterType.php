<?php

namespace App\Form;

use App\Entity\Campus;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('keywords', TextType::class, [
                'required' => false,
                'label' => 'Recherche par mots-clés : '
            ])
            ->add('campus', EntityType::class, [
                'required' => false,
                'mapped' => false,
                'class' => Campus::class,
                'choice_label' => 'nom',
                'label' => 'Campus :',
                'query_builder' => function(CampusRepository $repository){
                    return $repository->createQueryBuilder('c');
                }
            ])
            ->add('dateDebut', DateType::class, [
                'html5'=> true,
                'widget'=> 'single_text',
                'required' => false,
                'label' => 'Date de début :'
            ])
            ->add('estOrganisateur', CheckboxType::class,[
                'required' => false,
                'label' => 'Sortie dont je suis l\'organisateur/trice'
            ])
            ->add('estInscrit', CheckboxType::class,[
                'required' => false,
                'label' => 'Sortie auxquelles je suis inscrit/e '
            ])
            ->add('pasInscrit', CheckboxType::class,[
                'required' => false,
                'label' => 'Sortie auxquelles je ne suis pas inscrit/e '
            ])
            ->add('sortiesPassees', CheckboxType::class,[
                'required' => false,
                'label' => 'Sortie passées'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }
}
