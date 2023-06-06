<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    //injection de dépendance
    public function __construct(UserPasswordHasherInterface $encoder, CampusRepository $repository, EtatRepository $etatRepository)
    {
        $this->encoder = $encoder;
        $this->repository = $repository;
        $this->etatRepository = $etatRepository;
    }

    public function load(ObjectManager $manager): void
    {

        //Creation d'un utilisateur pour avoir un mot de passe

//        $user = new Utilisateur();
//        $user->setUsername('julien');
//        $user->setNom('Chéreau');
//        $user->setPrenom('Julien');
//        $user->setTelephone('0233289763');
//        $user->setEmail('julien@gmail.com');
//        $user->setActif(true);
//
//        $campus = $this->repository->find('1');
//
//        $user->setCampus($campus);
//        $plainPassword = '123456';
//        $encoded = $this->encoder->hashPassword($user, $plainPassword);
//        $user->setPassword($encoded);
//
//        $manager->persist($user);

        //Creation d'etats specifiques si non créés dans la base de données

//        $etat= new Etat();
//        $etat->getId(1);
//        $etat->setLibelle('Créée');
//
//        $manager->persist($etat);
//
//        $etat= new Etat();
//        $etat->getId(2);
//        $etat->setLibelle('Ouverte');
//
//        $manager->persist($etat);
//
//        $etat= new Etat();
//        $etat->getId(3);
//        $etat->setLibelle('Clôturée');
//
//        $manager->persist($etat);
//
//        $etat= new Etat();
//        $etat->getId(4);
//        $etat->setLibelle('Activité en cours');
//
//        $manager->persist($etat);
//
//        $etat= new Etat();
//        $etat->getId(5);
//        $etat->setLibelle('Passée');
//
//        $manager->persist($etat);
//
//        $etat= new Etat();
//        $etat->getId(6);
//        $etat->setLibelle('Annulée');
//
//        $manager->persist($etat);

        //Generer des donnees pour la base de donnees

        $generator = Factory::create('fr_FR');


        //Creation des Campus

        for ($i = 0; $i < 5; $i++) {
            $campus = new Campus();
            $campus->setNom($generator->randomElement(["Rennes", "Nantes", "Niort"]));
            $manager->persist($campus);
        }

        //Création des Villes

        for ($i = 0; $i < 15; $i++) {
            $ville = new Ville();

            $ville->setNom($generator->city);
            $ville->setCodePostal($generator->randomNumber(5, true));
            $manager->persist($ville);
        }

        //Création des Utilisateurs

        for ($i = 0; $i < 25; $i++) {

            $user = new Utilisateur();


            $user->setUsername($generator->name);
            $user->setNom($generator->firstName);
            $user->setPrenom($generator->lastName);
            $user->setTelephone($generator->phoneNumber);
            $user->setEmail($generator->email);
            $user->setActif(true);

            $user->setCampus($campus);

            $plainPassword = "123456";
            $encoded = $this->encoder->hashPassword($user, $plainPassword);
            $user->setPassword($encoded);

            $manager->persist($user);
        }

        //Creation des lieux

        for ($i = 0; $i < 25; $i++) {
            $lieu = new Lieu();
            $lieu
                ->setLatitude($generator->latitude)
                ->setNom($generator->name)
                ->setLongitude($generator->longitude)
                ->setRue($generator->streetName)
                ->setVille($ville);

            $manager->persist($lieu);
        }


        //Creation des Sorties
        for ($i = 0; $i < 25; $i++) {

            //initialisation des etats
            $etats = $this->etatRepository->findAll();
            $tirage = rand(0, 5);
            $etat = $etats[$tirage];

            $sortie = new Sortie();

            $sortie
                ->setCampus($campus)
                ->setDateLimiteInscription($generator->dateTimeBetween("- 5 months", "+3 months"))
                ->setDateHeureDebut($generator->dateTimeBetween("-5 months", "+5 months"))
                ->setDuree($generator->randomNumber(2, false))
                ->setInfos($generator->realText(200))
                ->setLieu($lieu)
                ->setNom($generator->word)
                ->setNbMaxInscriptions($generator->numberBetween(1, 50))
                ->setEtat($etat)
                ->setOrganisateur($user);

            $manager->persist($sortie);
        }

        $manager->flush();

    }

//    public function tirerEtat(){
//
//        $etats = $this->etatRepository->findAll();
//        $tirage = rand(0, 5);
//        $etat = $etats[$tirage];
//
//        return $etat;
//
//    }
//  }


}
