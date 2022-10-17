<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $type = new Type();
        $type->setName('Appartement');
        $manager->persist($type);

        $this->addReference('appartement', $type);

        $type2 = new Type();
        $type2->setName('Maison');
        $manager->persist($type2);

        $this->addReference('maison', $type2);

        $manager->flush();
    }
}
