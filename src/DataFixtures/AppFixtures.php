<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Utilisateur;
use App\Repository\CampusRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
}
