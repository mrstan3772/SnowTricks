<?php
namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\GroupFactory;
use App\Factory\TrickFactory;
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
        GroupFactory::createOne(['group_name' => 'Groupe 2']);
        GroupFactory::createOne(['group_name' => 'Groupe 3']);
        GroupFactory::createOne(['group_name' => 'Groupe 4']);
        GroupFactory::createOne(['group_name' => 'Groupe 5']);

        TrickFactory::createOne(['trick_name' => 'Butters']);
        TrickFactory::createOne(['trick_name' => 'Indy Grabs']);
        TrickFactory::createOne(['trick_name' => 'Ollie']);
        TrickFactory::createOne(['trick_name' => 'Front Side 180s']);

        $manager->flush();
    }
}
