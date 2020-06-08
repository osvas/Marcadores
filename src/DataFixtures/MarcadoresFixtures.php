<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Marcador;

class MarcadoresFixtures extends Fixture implements DependentFixtureInterface {

    public function load(ObjectManager $manager) {
        $marcador = new Marcador();
        $marcador->setNombre('Google');
        $marcador->setUrl('http://google.com');
        $marcador->setCategoria($this->getReference(CategoriasFixtures::CATEGORIA_INTERNET_REFERENCIA));
        $manager->persist($marcador);
        
        $marcador = new Marcador();
        $marcador->setNombre('Facebook');
        $marcador->setUrl('http://facebook.com');
        $marcador->setCategoria($this->getReference(CategoriasFixtures::CATEGORIA_INTERNET_REFERENCIA));
        $manager->persist($marcador);
        
        $manager->flush();
    }

    public function getDependencies() {
        return [CategoriasFixtures::class];
    }

}
