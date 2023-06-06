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
    private CampusRepository $repository;

    //injection de dépendance
    public function __construct(UserPasswordHasherInterface $encoder, CampusRepository $repository){
        $this->encoder = $encoder;
        $this->repository = $repository;
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
            $ville
                ->setNom($generator->ville)
                ->setCodePostal($generator->randomNumer(5,true));
            $manager->persist($ville);
        }

        //Création des Utilisateurs

        for ($i=0;$i<25;$i++){

            $user=new Utilisateur();

            $user->setUsername($generator->username);
            $user->setNom($generator->nom);
            $user->setPrenom($generator->prenom);
            $user->setTelephone($generator->telephone);
            $user->setEmail($generator->email);
            $user->setActif(true);

            $user->setCampus($campus);

            //Mot de Passe
            $user->setPassword($generator->$this->hasher->hashPassword($user, 'test123456'));

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
                ->setNom($generator->nom)
                ->setLongitude($generator->longitude)
                ->setRue($generator->rue)
                ->setVille($generator->ville);

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
                ->setLieu($generator->lieu)
                ->setNom($generator->nom)
                ->setNbMaxInscriptions($generator->randomNumber(3,false))
                ->setEtat($etat);
            $manager->persist(($sortie));
        }

        $manager->flush();

    }
}
//    private UserPasswordHasherInterface $encoder;
//    private CampusRepository $repository;
//
//    public function __construct(UserPasswordHasherInterface $encoder, CampusRepository $repository){
//        $this->encoder = $encoder;
//        $this->repository = $repository;
//    }
//
//    public function load(ObjectManager $manager): void
//    {
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
//
//        $manager->flush();
//    }
//
//    public function addSorties(ObjectManager $manager)
//    {
//        //Test sortieA
//        $sortieA = new Sortie();
//        $sortieA
//            ->setNom('testA')
//            ->setDuree(123)
//            ->setNbMaxInscriptions(123)
//            ->setInfos('testA');
//
//        $h="08/08/2023 14:48";
//        $hdebut= \DateTime::createFromFormat('d/m/Y H:i',$h);
//        $sortieA->setDateHeureDebut($hdebut);
//
//        $d="15/08/2023";
//        $dInscription= \DateTime::createFromFormat('d/m/Y', $d);
//        $sortieA->setDateLimiteInscription($dInscription);
//
//        $organisateur = $this->repository->find('1');
//        $sortieA->setOrganisateur($organisateur);
//
//        $campus = $this->repository->find('1');
//        $sortieA->setCampus($campus);
//
//        $etat = $this->repository->find('1');
//        $sortieA->setEtat($etat);
//
//        $lieu = $this->repository->find('2');
//        $sortieA->setLieu($lieu);
//
//        $manager->persist($sortieA);
//
//        $manager->flush();
//
//        //Test sortieB
//        $sortieB = new Sortie();
//        $sortieB
//            ->setNom('testB')
//            ->setDuree(123)
//            ->setNbMaxInscriptions(123)
//            ->setInfos('testB');
//
//        $h="09/09/2023";
//        $dInscription= \DateTime::createFromFormat('d/m/Y',$h);
//        $sortieA->setDateLimiteInscription($dInscription);
//
//        $organisateur = $this->repository->find('1');
//        $sortieA->setOrganisateur($organisateur);
//
//        $campus = $this->repository->find('1');
//        $sortieA->setCampus($campus);
//
//        $etat = $this->repository->find('1');
//        $sortieA->setEtat($etat);
//
//        $lieu = $this->repository->find('2');
//        $sortieA->setLieu($lieu);
//
//        $manager->persist($sortieA);
//
//        $manager->flush();
//
//    }
//}
