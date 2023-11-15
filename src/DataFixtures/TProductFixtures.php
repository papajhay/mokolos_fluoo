<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\TProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TProductFixtures extends  Fixture
{
     public function load(ObjectManager $manager)
     {
         $providerReference = $this->getReference('provider');

         $tProductData = [
             [
                 'provider' => $providerReference,
                 'libelle' => 'Chemise / pochette à rabats',
                  'specialFormat' => 0,
                  'specialQuantity' => 0,
                  'attachement' => 0
             ],
             [
                 'provider' => $providerReference,
                 'libelle' => 'Brochure',
                 'specialFormat' => 0,
                 'specialQuantity' => 0,
                 'attachement' => 0
             ]
         ];

         foreach ($tProductData as $data) {
             $tProduct = new TProduct();
             $tProduct
                 ->setProvider($data['provider'])
                 ->setLibelle($data['libelle'])
                 ->setSpecialFormat($data['specialFormat'])
                 ->setSpecialQuantity($data['specialQuantity']);

             $manager->persist($tProduct);
         }

           $manager->flush();

     }
}