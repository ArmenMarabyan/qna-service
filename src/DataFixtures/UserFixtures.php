<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Component\Security\Core\User\;

class UserFixtures extends Fixture
{
    private $encoder;

    private $em;

    public function __construct(UserPasswordHasherInterface $encoder, EntityManagerInterface $entityManager)
    {
        $this->encoder = $encoder;
        $this->em = $entityManager;
    }

    public function load(\Doctrine\Persistence\ObjectManager $manager)
    {
        $usersData = [
            0 => [
                'email' => 'user@gmail.com',
                'role' => ['ROLE_USER'],
                'password' => 123456
            ],
            1 => [
                'email' => 'user2@gmail.com',
                'role' => ['ROLE_USER'],
                'password' => 123456
            ]
        ];

        foreach ($usersData as $user) {
            $newUser = new User();
            $newUser->setEmail($user['email']);
            $newUser->setPassword($this->encoder->hashPassword($newUser, $user['password']));
            $newUser->setRoles($user['role']);
            $this->em->persist($newUser);
        }

        $this->em->flush();
    }
}