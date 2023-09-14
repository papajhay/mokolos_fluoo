<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Hosts;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use JetBrains\PhpStorm\NoReturn;

class HostsFixtures extends Fixture
{
    #[NoReturn] public function load(ObjectManager $manager): void
    {
        $jsonData = file_get_contents('migrations' . DIRECTORY_SEPARATOR . 'hosts.json');

        $hosts = json_decode($jsonData, true);

        foreach ($hosts as $hostData) {
            $host = new Hosts();
            $host->setMailNs($hostData['mail_ns']);
            $host->setDomain($hostData['domaine']);
            $host->setAdresseWww($hostData['adresse_www']);
            $host->setMailInfo($hostData['mail_info']);
            $host->setTelStd($hostData['tel_std']);
            $host->setFax($hostData['fax']);
            $host->setName($hostData['host_nom']);
            $host->setBillingName($hostData['facturation_nom']);
            $host->setBillingAddress($hostData['facturation_adresse']);
            $host->setBillingAddress2($hostData['facturation_adresse2']);
            $host->setCpBilling($hostData['facturation_cp']);
            $host->setCityBilling($hostData['facturation_ville']);
            $host->setCountryBilling($hostData['facturation_pays']);
            $host->setCountry($hostData['pays']);
            $host->setCountryCode($hostData['pays_code']);
            $host->setIdFourP24($hostData['id_four_p24']);
            $host->setIdHostType($hostData['id_host_type']);
            $host->setMaster($hostData['master_host']);
            $host->setVat($hostData['tva']);
            $host->setLanguageCode($hostData['code_langue']);
            $host->setLocalCurrenciesId($hostData['local_currencies_id']);
            $host->setGoogleAnalyticsId($hostData['google_analytics_id']);
            $host->setGoogleIdConversion($hostData['hos_google_id_conversion']);
            $host->setGoogleAdWordsId($hostData['hos_google_ad_words_id']);
            $host->setGoogleAdWordsRemarketingId($hostData['hos_google_ad_words_remarketing_id']);
            $host->setBingAdsId($hostData['hos_bing_ads_id']);
            $host->setOrderHost($hostData['hos_ordre']);
            $host->setData($hostData['host_data']);
            $host->setProductHost($hostData['host_product']);
            $host->setFacebookUrl($hostData['hos_facebook_url']);
            $host->setFacebookAppId($hostData['hos_facebook_app_id']);
            $host->setCreditName($hostData['hos_credit_name']);
            $host->setReferenceLanguage($hostData['hos_href_lang']);
            $host->setNoticeRating($hostData['hos_site_avis_note']);
            $host->setOpinionNumber($hostData['hos_site_avis_nbr']);
            $host->setSecretkey($hostData['hos_site_secret_key']);
            $host->setHosSiteId($hostData['hos_site_id']);
            $host->setAmountPremium($hostData['hos_amount_premium']);
            $host->setProductNumber($hostData['hos_products_number']);
            $host->setHosDelay($hostData['hos_delay']);
            $host->setPriceDecimal($hostData['hos_price_decimal']);

            $manager->persist($host);
        }

        $manager->flush();
    }
}
