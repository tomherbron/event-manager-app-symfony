<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Repository\CampusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;
    private CampusRepository $repository;

    public function __construct(UserPasswordHasherInterface $encoder, CampusRepository $repository){
        $this->encoder = $encoder;
        $this->repository = $repository;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new Utilisateur();
        $user->setUsername('tomtats');
        $user->setNom('Herbron');
        $user->setPrenom('Tom');
        $user->setTelephone('0233289763');
        $user->setEmail('tom@gmail.com');
        $user->setActif(true);

        $campus = $this->repository->find('1');

        $user->setCampus($campus);
        $plainPassword = '123456';
        $encoded = $this->encoder->hashPassword($user, $plainPassword);
        $user->setPassword($encoded);

        $manager->persist($user);

        $manager->flush();
    }

    public function addSorties(ObjectManager $manager)
    {
        //Test sortieA
        $sortieA = new Sortie();
        $sortieA
            ->setNom('testA')
            ->setDuree(123)
            ->setNbMaxInscriptions(123)
            ->setInfos('testA');

        $h="08/08/2023 14:48";
        $hdebut= \DateTime::createFromFormat('d/m/Y H:i',$h);
        $sortieA->setDateHeureDebut($hdebut);

        $d="15/08/2023";
        $dInscription= \DateTime::createFromFormat('d/m/Y', $d);
        $sortieA->setDateLimiteInscription($dInscription);

        $organisateur = $this->repository->find('1');
        $sortieA->setOrganisateur($organisateur);

        $campus = $this->repository->find('1');
        $sortieA->setCampus($campus);

        $etat = $this->repository->find('1');
        $sortieA->setEtat($etat);

        $lieu = $this->repository->find('2');
        $sortieA->setLieu($lieu);

        $manager->persist($sortieA);

        $manager->flush();

        //Test sortieB
        $sortieB = new Sortie();
        $sortieB
            ->setNom('testB')
            ->setDuree(123)
            ->setNbMaxInscriptions(123)
            ->setInfos('testB');

        $h="09/09/2023";
        $dInscription= \DateTime::createFromFormat('d/m/Y',$h);
        $sortieA->setDateLimiteInscription($dInscription);

        $organisateur = $this->repository->find('1');
        $sortieA->setOrganisateur($organisateur);

        $campus = $this->repository->find('1');
        $sortieA->setCampus($campus);

        $etat = $this->repository->find('1');
        $sortieA->setEtat($etat);

        $lieu = $this->repository->find('2');
        $sortieA->setLieu($lieu);

        $manager->persist($sortieA);

        $manager->flush();

    }
}
