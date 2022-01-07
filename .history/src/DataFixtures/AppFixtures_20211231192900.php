<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\GroupFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        UserFactory::createOne(['email' => 'abraca_admin@example.com']);
        UserFactory::createMany(10);

        GroupFactory::createOne(['group_name' => 'Groupe 1']);
        GroupFactory::createOne(['group_name' => '']);
        GroupFactory::createOne(['group_name' => '']);
        GroupFactory::createOne(['group_name' => '']);
        GroupFactory::createOne(['group_name' => '']);

        $manager->flush();
    }
}
