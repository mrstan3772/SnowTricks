<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\Bundle\;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        UserFactory::createOne(['email' => 'abraca_admin@example.com']);
        UserFactory::createMany(10);

        $manager->flush();
    }
}
