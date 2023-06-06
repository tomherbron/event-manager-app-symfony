<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    //injection de dépendance
    public function __construct(UserPasswordHasherInterface $encoder, CampusRepository $repository){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {

        $generator = Factory::create('fr_FR');


        //Creation des Campus

        for ($i=0; $i<5; $i++) {
            $campus = new Campus();
            $campus->setNom($generator->randomElement(["Rennes","Nantes","Niort"]));
            $manager->persist($campus);
        }

        //Création des Villes

        for ($i=0;$i<15;$i++){
            $ville=new Ville();

            $ville->setNom($generator->city);
            $ville->setCodePostal($generator->randomNumber(5,true));
            $manager->persist($ville);
        }

        //Création des Utilisateurs

        for ($i=0;$i<25;$i++){

            $user=new Utilisateur();


            $user->setUsername($generator->name);
            $user->setNom($generator->firstName);
            $user->setPrenom($generator->lastName);
            $user->setTelephone($generator->phoneNumber);
            $user->setEmail($generator->email);
            $user->setActif(true);

            $user->setCampus($campus);



            //Mot de Passe
            //$user->setPassword($generator->$this->hasher->hashPassword($user, 'test123456'));

            $plainPassword = "123456";
            $encoded = $this->encoder->hashPassword($user, $plainPassword);
            $user->setPassword($encoded);

            $manager->persist($user);
        }

        //Creation des lieux

        for ($i=0; $i<25; $i++){
            $lieu = new Lieu();
            $lieu
                ->setLatitude($generator->latitude)
                ->setNom($generator->name)
                ->setLongitude($generator->longitude)
                ->setRue($generator->streetName)
                ->setVille($ville);

            $manager->persist($lieu);
        }

        //Creation des etats
        for ($i=0; $i<6; $i++){
            $etat = new Etat();
            $etat->setLibelle($generator->randomElement(["Créée","Ouverte","Clôturée","Activité en cours","Passée","Annulée"]));

            $manager->persist($etat);
        }

        //Creation des Sorties
        for ($i=0; $i<25; $i++){
            $sortie= new Sortie();
            $sortie
                ->setCampus($campus)
                ->setDateLimiteInscription($generator->dateTime)
                ->setDateHeureDebut($generator->dateTime)
                ->setDuree($generator->randomNumber(4,false))
                ->setInfos($generator->realText(200))
                ->setLieu($lieu)
                ->setNom($generator->name)
                ->setNbMaxInscriptions($generator->randomNumber(3,false))
                ->setEtat($etat)
                ->setOrganisateur($user);

            $manager->persist($sortie);
        }

        $manager->flush();

    }


}