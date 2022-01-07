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
        // UserFactory::createOne(['email' => 'abraca_admin@example.com']);
        // UserFactory::createMany(10);

        // GroupFactory::createOne(['group_name' => 'Groupe 1']);
        // GroupFactory::createOne(['group_name' => 'Groupe 2']);
        // GroupFactory::createOne(['group_name' => 'Groupe 3']);
        // GroupFactory::createOne(['group_name' => 'Groupe 4']);
        // GroupFactory::createOne(['group_name' => 'Groupe 5']);

        TrickFactory::createOne(['trick_name' => 'Butters', 'trick_thumbnail' => 'butters.jpg']);
        TrickFactory::createOne(['trick_name' => 'Indy Grabs', 'trick_thumbnail' => 'indy-grabs.jpeg']);
        TrickFactory::createOne(['trick_name' => 'Ollie', 'trick_thumbnail' => 'Ollie.jpg']);
        TrickFactory::createOne(['trick_name' => 'Frontside 180s', 'trick_thumbnail' => 'butters.jpg']);
        TrickFactory::createMany(31);
        
        $manager->flush();
    }
}
