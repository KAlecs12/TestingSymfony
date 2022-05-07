<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'alexadmin@gmail.com',
            'roles' => ['ROLE_ADMIN']
        ]);
        UserFactory::createOne([
            'email' => 'alex@gmail.com',
            'roles' => ['ROLE_USER']
        ]);
        UserFactory::createMany(10);


        $manager->flush();
    }
}
