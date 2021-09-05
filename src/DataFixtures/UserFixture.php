<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $random = bin2hex(random_bytes(10));
        $user = new User();
        $user->setEmail($random.'@test.com');
        $manager->persist(hash('sadsdsad'));
        $manager->flush();
    }
}
