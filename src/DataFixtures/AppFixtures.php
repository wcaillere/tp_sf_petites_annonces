<?php

namespace App\DataFixtures;

use App\Factory\AdFactory;
use App\Factory\CategoryFactory;
use App\Factory\StatusFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createMany(3);

        StatusFactory::createOne(['name' => 'en cours']);
        StatusFactory::createOne(['name' => 'terminée']);
        StatusFactory::createOne(['name' => 'annulée']);

        CategoryFactory::createOne(['name' => 'auto']);
        CategoryFactory::createOne(['name' => 'immobilier']);
        CategoryFactory::createOne(['name' => 'HiFi']);
        CategoryFactory::createOne(['name' => 'service à la personne']);
        CategoryFactory::createOne(['name' => 'animaux']);

        AdFactory::createMany(20, function() {
           return [
               'author' => UserFactory::random(),
               'category' => CategoryFactory::random(),
               'status' => StatusFactory::random(),
           ];
        });
    }
}
