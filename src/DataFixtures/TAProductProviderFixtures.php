<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\TAProductProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class TAProductProviderFixtures extends  Fixture
{
    public function load(ObjectManager $manager)
    {
        $providerReference = $this->getReference('provider');

        $tAProductProviderData = [
            [
                'provider' => $providerReference,
                'idSource' => 'k/affiches-1-face',
                'labelSource'  => 'Affiches imprimées 1 face'
            ],
            [
                'provider' => $providerReference,
                'idSource' => 'k/drapeaux',
                'labelSource'  => 'Drapeaux'
            ]
        ];

        foreach ($tAProductProviderData as $data) {
            $tAProductProvider = new TAProductProvider();
            $tAProductProvider
                ->setProvider($data['provider'])
                ->setIdSource($data['idSource'])
                ->setLabelSource($data['labelSource']);

            $manager->persist($tAProductProvider);
        }

        $manager->flush();

    }
}