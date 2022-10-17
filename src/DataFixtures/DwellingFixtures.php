<?php

namespace App\DataFixtures;

use App\Entity\Dwelling;
use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DwellingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $category = $this->getReference('vente');
        $category2 = $this->getReference('location');

        $type = $this->getReference('appartement');
        $type2 = $this->getReference('maison');
        


        $dwelling = new Dwelling();
        $dwelling -> setName('Appartement T2');
        $dwelling -> setPicture('picture.jpeg');
        $dwelling -> setAdress('28, avenue Grenier');
        $dwelling -> setZipcode('59000');
        $dwelling -> setCity('Lille');
        $dwelling -> setSize('35');
        $dwelling -> setPrice('200000');
        
        $dwelling->setType($type);
        $dwelling->setCategory($category);

        $manager->persist($dwelling);


        $dwelling2 = new Dwelling();
        $dwelling2 -> setName('Maison 3 chambres ');
        $dwelling2 -> setPicture('picture.jpeg');
        $dwelling2 -> setAdress('91, chemin Antoinette Le Goff');
        $dwelling2 -> setZipcode('29000');
        $dwelling2 -> setCity('Brest');
        $dwelling2 -> setSize('180');
        $dwelling2 -> setPrice('300000');
        
        $dwelling2->setType($type2);
        $dwelling2->setCategory($category);

        $manager->persist($dwelling2);


        $dwelling3 = new Dwelling();
        $dwelling3 -> setName('Appartement T1 bis');
        $dwelling3 -> setPicture('picture.jpeg');
        $dwelling3 -> setAdress('17, chemin Jules Fischer');
        $dwelling3 -> setZipcode('14000');
        $dwelling3 -> setCity('Caen');
        $dwelling3 -> setSize('35');
        $dwelling3 -> setPrice('550');
        
        $dwelling3->setType($type);
        $dwelling3->setCategory($category2);

        $manager->persist($dwelling3);


        $dwelling4 = new Dwelling();
        $dwelling4 -> setName('Maison 2 chambres');
        $dwelling4 -> setPicture('picture.jpeg');
        $dwelling4 -> setAdress('28, boulevard Jaurès');
        $dwelling4 -> setZipcode('44000');
        $dwelling4 -> setCity('Nantes');
        $dwelling4 -> setSize('120');
        $dwelling4 -> setPrice('1000');
        
        $dwelling4->setType($type2);
        $dwelling4->setCategory($category2);

        $manager->persist($dwelling4);


        $dwelling5 = new Dwelling();
        $dwelling5 -> setName('Appartement T4');
        $dwelling5 -> setPicture('picture.jpeg');
        $dwelling5 -> setAdress('34, avenue Victor Hugo');
        $dwelling5 -> setZipcode('33000');
        $dwelling5 -> setCity('Bordeaux');
        $dwelling5 -> setSize('70');
        $dwelling5 -> setPrice('400000');
        
        $dwelling5->setType($type);
        $dwelling5->setCategory($category);

        $manager->persist($dwelling5);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            TypeFixtures::class,
        ];
    }
}
