<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Provider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProviderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $providersData = [
            [
                'name' => 'REALISAPRINT',
                'email' => 'contact@realisaprint.com',
                'currenciesId' => 2,
                'discountPercentage'=> 5,
                'masterId' => 75,
                'VATRate'=> 20,
                'numberVAT' => '',
                'recoverableVAT'=> 1,
                'billingPostCode'=> 0,
                'payment'=> '12',
                'orderSelection'=> 15,
                'webSiteAddress' => 'https://www.realisaprint.com/api/',
                'siteLogin' => '75',
                'sitePass' => 'f9f9030e6e2b7975770fe02f175a5627',
                'countryCode' => 'FR',
                'active' => true,
                'dateValidCachePrice' => (new \DateTime('2030-01-01 00:00:00')),
                'whiteLabelDelivery' => 0,
                'idHostForSelection' => 'mif',
                'errorCode' => 0
            ],
            [
                'name' => 'Online Printers',
                'email' => 'info@onlineprinters.com',
                'currenciesId' =>  2,
                'discountPercentage'=> 5,
                'masterId' => 42,
                'VATRate'=> 0,
                'numberVAT' => 'DE814978904',
                'recoverableVAT'=> 1,
                'billingPostCode'=> 0,
                'payment'=> '12',
                'orderSelection'=> 99,
                'webSiteAddress' => 'https://www.onlineprinters.fr/',
                'siteLogin' => 'achat2@fluoo.com',
                'sitePass' => '050607',
                'countryCode' => 'FR',
                'active' => true,
                'dateValidCachePrice' => (new \DateTime('2023-02-01 10:36:49')),
                'whiteLabelDelivery' => 0,
                'idHostForSelection' => 'lig',
            ],
        ];

        foreach ($providersData as $data) {
            $provider = new Provider();
            $provider
                ->setName($data['name'])
                ->setEmail($data['email'])
                ->setCurrenciesId($data['currenciesId'])
                ->setDiscountPercentage($data['discountPercentage'])
                ->setMasterId($data['masterId'])
                ->setVATRate($data['VATRate'])
                ->setNumberVAT($data['numberVAT'])
                ->setRecoverableVAT($data['recoverableVAT'])
                ->setBillingPostCode($data['billingPostCode'])
                ->setPayment($data['payment'])
                ->setOrderSelection($data['orderSelection'])
                ->setWebSiteAddress($data['webSiteAddress'])
                ->setSiteLogin($data['siteLogin'])
                ->setSitePass($data['sitePass'])
                ->setCountryCode($data['countryCode'])
                ->setActive($data['active'])
                ->setDateValidCachePrice($data['dateValidCachePrice'])
                ->setWhiteLabelDelivery($data['whiteLabelDelivery'])
                ->setIdHostForSelection($data['idHostForSelection']);

        $manager->persist($provider);
    }
        $manager->flush();

        $this->addReference('provider', $provider);
    }
}
