<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Provider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
class ProviderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $provider = new Provider();
        $provider->setName('REALISAPRINT')
            ->setEmail('contact@realisaprint.com')
            ->setCurrenciesId(2)
            ->setMasterId(75)
            ->setVATRate(20)
            ->setRecoverableVAT(1)
            ->setBillingPostCode(0)
            ->setPayment('12')
            ->setOrderSelection(15)
            ->setWebSiteAddress('https://www.realisaprint.com/api/')
            ->setSiteLogin('75')
            ->setSitePass('f9f9030e6e2b7975770fe02f175a5627')
            ->setCountryCode('FR')
            ->setActive(true)
            ->setDateValidCachePrice(new \DateTime('2030-01-01 00:00:00'))
            ->setWhiteLabelDelivery(0)
            ->setIdHostForSelection('mif')
            ->setErrorCode(0);

        $manager->persist($provider);

        $manager->flush();
    }
}


