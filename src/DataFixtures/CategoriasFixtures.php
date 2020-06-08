<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use \App\Entity\Categoria;

class CategoriasFixtures extends Fixture {
    public const CATEGORIA_INTERNET_REFERENCIA = 'categoria-internet';

    public function load(ObjectManager $manager) {
        $categoria = new Categoria;
        $categoria->setNombre('Internet');
        $categoria->setColor('#000000');
        $manager->persist($categoria);
        $manager->flush();
        
        $this->addReference(self::CATEGORIA_INTERNET_REFERENCIA, $categoria); 
    }

}
