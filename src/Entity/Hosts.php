<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\HostsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HostsRepository::class)]
class Hosts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $mailNs = null;

    #[ORM\Column(length: 255)]
    private ?string $adresseWww = null;

    #[ORM\Column(length: 255)]
    private ?string $telStd = null;

    #[ORM\Column(length: 255)]
     // old: $hostNom
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    // old: $facturationNom
    private ?string $billingName = null;

    #[ORM\Column(length: 255)]
    // old: $facturationAdresse
    private ?string $billingAddress = null;

    #[ORM\Column(length: 255)]
    // old: $facturationAdresse2
    private ?string $billingAddress2 = null;

    #[ORM\Column(length: 255)]
     // old: $facturationCp
    private ?string $cpBilling = null;

    #[ORM\Column(length: 255)]
    // old: $facturationVille
    private ?string $cityBilling = null;

    #[ORM\Column(length: 255)]
    // old: $facturationPays
    private ?string $countryBilling = null;

    #[ORM\Column(length: 255)]
    // old: $pays
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    // old: $paysCode
    private ?string $countryCode = null;

    #[ORM\Column]
    private ?int $idHostType = null;

    #[ORM\Column]
    // old: $masterHost
    private ?int $master = null;

    #[ORM\Column]
    // old: $tva
    private ?float $vat = null;

    #[ORM\Column(length: 255)]
    private ?string $mailInfo = null;

    #[ORM\Column(length: 255)]
    // old: $domaine
    private ?string $domain = null;

    #[ORM\Column(length: 255)]
    private ?string $fax = null;

    #[ORM\Column]
    private ?int $idFourP24 = null;

    #[ORM\Column]
    private ?int $localCurrenciesId = null;

    #[ORM\Column]
    private ?int $googleAnalyticsId = null;

    #[ORM\Column]
    private ?int $googleAdWordsId = null;

    #[ORM\Column(length: 255)]
    // old: $hosGoogleIdConversion
    private ?string $googleIdConversion = null;

    #[ORM\Column(length: 255)]
     // old: $hosGoogleAdWordsRemarketingId
    private ?string $googleAdWordsRemarketingId = null;

    #[ORM\Column]
    // old: $hosBingAdsId
    private ?int $bingAdsId = null;

    #[ORM\Column(length: 255)]
    // old: $codeLangue
    private ?string $languageCode = null;

    #[ORM\Column]
    // old: $actif
    private ?bool $active = null;

    #[ORM\Column]
    // old: $hosOrdre
    private ?int $orderHost = null;

    #[ORM\Column(length: 255)]
    // old: $hostData
    private ?string $data = null;

    #[ORM\Column(length: 255)]
    // old: $hostProduct
    private ?string $productHost = null;

    #[ORM\Column(length: 255)]
    // old: $hosFacebookUrl
    private ?string $facebookUrl = null;

    #[ORM\Column]
    // old: $hosFacebookAppId
    private ?int $facebookAppId = null;

    #[ORM\Column(length: 255)]
    // old: $hosCreditName
    private ?string $creditName = null;

    #[ORM\Column(length: 255)]
    // old: $hosHrefLang
    private ?string $referenceLanguage = null;

    #[ORM\Column]
    // old: $hosSiteAvisNote
    private ?float $noticeRating = 0;

    #[ORM\Column]
    // old: $hosSiteAvisNbr
    private ?int $opinionNumber = 0;

    #[ORM\Column]
    // old: $hosSiteSecretKey
    private ?int $secretkey = null;

    #[ORM\Column]
    private ?int $hosSiteId = null;

    #[ORM\Column]
    // old: $hosAmountPremium
    private ?float $amountPremuim = 1000;

    #[ORM\Column]
    // old: $hosProductsNumber
    private ?int $productNumber = null;

    #[ORM\Column]
    private ?int $hosDelay = 0;

    #[ORM\Column]
    // old: $hosPriceDecimal
    private ?float $priceDecimal = 0;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TAHostCmsBloc::class)]
    private Collection $tAHostCmsBlocs;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TAProductMeta::class)]
    private Collection $tAProductMetas;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TAProductOption::class)]
    private Collection $tAProductOptions;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TAProductOptionValue::class)]
    private Collection $tAProductOptionValues;

    public function __construct()
    {
        $this->tAHostCmsBlocs = new ArrayCollection();
        $this->tAProductMetas = new ArrayCollection();
        $this->tAProductOptions = new ArrayCollection();
        $this->tAProductOptionValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMailNs(): ?string
    {
        return $this->mailNs;
    }

    public function setMailNs(string $mailNs): static
    {
        $this->mailNs = $mailNs;

        return $this;
    }

    public function getAdresseWww(): ?string
    {
        return $this->adresseWww;
    }

    public function setAdresseWww(string $adresseWww): static
    {
        $this->adresseWww = $adresseWww;

        return $this;
    }

    public function getTelStd(): ?string
    {
        return $this->telStd;
    }

    public function setTelStd(string $telStd): static
    {
        $this->telStd = $telStd;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getBillingName(): ?string
    {
        return $this->billingName;
    }

    public function setBillingName(string $billingName): static
    {
        $this->billingName = $billingName;

        return $this;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(string $billingAddress): static
    {
        $this->billingAddress = $billingAddress;

        return $this;
    }

    public function getBillingAddress2(): ?string
    {
        return $this->billingAddress2;
    }

    public function setBillingAddress2(string $billingAddress2): static
    {
        $this->billingAddress2 = $billingAddress2;

        return $this;
    }

    public function getCpBilling(): ?string
    {
        return $this->cpBilling;
    }

    public function setCpBilling(string $cpBilling): static
    {
        $this->cpBilling = $cpBilling;

        return $this;
    }

    public function getCityBilling(): ?string
    {
        return $this->cityBilling;
    }

    public function setCityBilling(string $cityBilling): static
    {
        $this->cityBilling = $cityBilling;

        return $this;
    }

    public function getCountryBilling(): ?string
    {
        return $this->countryBilling;
    }

    public function setCountryBilling(string $countryBilling): static
    {
        $this->countryBilling = $countryBilling;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): static
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getIdHostType(): ?int
    {
        return $this->idHostType;
    }

    public function setIdHostType(int $idHostType): static
    {
        $this->idHostType = $idHostType;

        return $this;
    }

    public function getMaster(): ?int
    {
        return $this->master;
    }

    public function setMaster(int $master): static
    {
        $this->master = $master;

        return $this;
    }

    public function getVat(): ?float
    {
        return $this->vat;
    }

    public function setVat(float $vat): static
    {
        $this->vat = $vat;

        return $this;
    }

    public function getMailInfo(): ?string
    {
        return $this->mailInfo;
    }

    public function setMailInfo(string $mailInfo): static
    {
        $this->mailInfo = $mailInfo;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(string $fax): static
    {
        $this->fax = $fax;

        return $this;
    }

    public function getIdFourP24(): ?int
    {
        return $this->idFourP24;
    }

    public function setIdFourP24(int $idFourP24): static
    {
        $this->idFourP24 = $idFourP24;

        return $this;
    }

    public function getLocalCurrenciesId(): ?int
    {
        return $this->localCurrenciesId;
    }

    public function setLocalCurrenciesId(int $localCurrenciesId): static
    {
        $this->localCurrenciesId = $localCurrenciesId;

        return $this;
    }

    public function getGoogleAnalyticsId(): ?string
    {
        return $this->googleAnalyticsId;
    }

    public function setGoogleAnalyticsId(int $googleAnalyticsId): static
    {
        $this->googleAnalyticsId = $googleAnalyticsId;

        return $this;
    }

    public function getGoogleAdWordsId(): ?int
    {
        return $this->googleAdWordsId;
    }

    public function setGoogleAdWordsId(int $googleAdWordsId): static
    {
        $this->googleAdWordsId = $googleAdWordsId;

        return $this;
    }

    public function getGoogleIdConversion(): ?string
    {
        return $this->googleIdConversion;
    }

    public function setGoogleIdConversion(string $googleIdConversion): static
    {
        $this->googleIdConversion = $googleIdConversion;

        return $this;
    }

    public function getGoogleAdWordsRemarketingId(): ?string
    {
        return $this->googleAdWordsRemarketingId;
    }

    public function setGoogleAdWordsRemarketingId(string $googleAdWordsRemarketingId): static
    {
        $this->googleAdWordsRemarketingId = $googleAdWordsRemarketingId;

        return $this;
    }

    public function getBingAdsId(): ?int
    {
        return $this->bingAdsId;
    }

    public function setBingAdsId(int $bingAdsId): static
    {
        $this->bingAdsId = $bingAdsId;

        return $this;
    }

    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    public function setLanguageCode(string $languageCode): static
    {
        $this->languageCode = $languageCode;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getOrderHost(): ?int
    {
        return $this->orderHost;
    }

    public function setOrderHost(int $orderHost): static
    {
        $this->orderHost = $orderHost;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(string $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getProductHost(): ?string
    {
        return $this->productHost;
    }

    public function setProductHost(string $productHost): static
    {
        $this->productHost = $productHost;

        return $this;
    }

    public function getFacebookUrl(): ?string
    {
        return $this->facebookUrl;
    }

    public function setFacebookUrl(string $facebookUrl): static
    {
        $this->facebookUrl = $facebookUrl;

        return $this;
    }

    public function getFacebookAppId(): ?int
    {
        return $this->facebookAppId;
    }

    public function setFacebookAppId(int $facebookAppId): static
    {
        $this->facebookAppId = $facebookAppId;

        return $this;
    }

    public function getCreditName(): ?string
    {
        return $this->creditName;
    }

    public function setCreditName(string $creditName): static
    {
        $this->creditName = $creditName;

        return $this;
    }

    public function getReferenceLanguage(): ?string
    {
        return $this->referenceLanguage;
    }

    public function setReferenceLanguage(string $referenceLanguage): static
    {
        $this->referenceLanguage = $referenceLanguage;

        return $this;
    }

    public function getNoticeRating(): ?float
    {
        return $this->noticeRating;
    }

    public function setNoticeRating(float $noticeRating): static
    {
        $this->noticeRating = $noticeRating;

        return $this;
    }

    public function getOpinionNumber(): ?int
    {
        return $this->opinionNumber;
    }

    public function setOpinionNumber(int $opinionNumber): static
    {
        $this->opinionNumber = $opinionNumber;

        return $this;
    }

    public function getSecretkey(): ?int
    {
        return $this->secretkey;
    }

    public function setSecretkey(int $secretkey): static
    {
        $this->secretkey = $secretkey;

        return $this;
    }

    public function getHosSiteId(): ?int
    {
        return $this->hosSiteId;
    }

    public function setHosSiteId(int $hosSiteId): static
    {
        $this->hosSiteId = $hosSiteId;

        return $this;
    }

    public function getAmountPremuim(): ?float
    {
        return $this->amountPremuim;
    }

    public function setAmountPremuim(float $amountPremuim): static
    {
        $this->amountPremuim = $amountPremuim;

        return $this;
    }

    public function getProductNumber(): ?int
    {
        return $this->productNumber;
    }

    public function setProductNumber(int $productNumber): static
    {
        $this->productNumber = $productNumber;

        return $this;
    }

    public function getHosDelay(): ?int
    {
        return $this->hosDelay;
    }

    public function setHosDelay(int $hosDelay): static
    {
        $this->hosDelay = $hosDelay;

        return $this;
    }

    public function getPriceDecimal(): ?float
    {
        return $this->priceDecimal;
    }

    public function setPriceDecimal(float $priceDecimal): static
    {
        $this->priceDecimal = $priceDecimal;

        return $this;
    }


    /**
     * @return Collection<int, TAProductMeta>
     */
    public function getTAProductMetas(): Collection
    {
        return $this->tAProductMetas;
    }

    public function addTAProductMeta(TAProductMeta $tAProductMeta): static
    {
        if (!$this->tAProductMetas->contains($tAProductMeta)) {
            $this->tAProductMetas->add($tAProductMeta);
            $tAProductMeta->setHost($this);
        }

        return $this;
    }

    public function removeTAProductMeta(TAProductMeta $tAProductMeta): static
    {
        if ($this->tAProductMetas->removeElement($tAProductMeta)) {
            // set the owning side to null (unless already changed)
            if ($tAProductMeta->getHost() === $this) {
                $tAProductMeta->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TAHostCmsBloc>
     */
    public function getTAHostCmsBlocs(): Collection
    {
        return $this->tAHostCmsBlocs;
    }

    public function addTAHostCmsBloc(TAHostCmsBloc $tAHostCmsBloc): static
    {
        if (!$this->tAHostCmsBlocs->contains($tAHostCmsBloc)) {
            $this->tAHostCmsBlocs->add($tAHostCmsBloc);
            $tAHostCmsBloc->setHost($this);
        }

        return $this;
    }

    public function removeTAHostCmsBloc(TAHostCmsBloc $tAHostCmsBloc): static
    {
        if ($this->tAHostCmsBlocs->removeElement($tAHostCmsBloc)) {
            // set the owning side to null (unless already changed)
            if ($tAHostCmsBloc->getHost() === $this) {
                $tAHostCmsBloc->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TAProductOption>
     */
    public function getTAProductOptions(): Collection
    {
        return $this->tAProductOptions;
    }

    public function addTAProductOption(TAProductOption $tAProductOption): static
    {
        if (!$this->tAProductOptions->contains($tAProductOption)) {
            $this->tAProductOptions->add($tAProductOption);
            $tAProductOption->setHost($this);
        }

        return $this;
    }

    public function removeTAProductOption(TAProductOption $tAProductOption): static
    {
        if ($this->tAProductOptions->removeElement($tAProductOption)) {
            // set the owning side to null (unless already changed)
            if ($tAProductOption->getHost() === $this) {
                $tAProductOption->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TAProductOptionValue>
     */
    public function getTAProductOptionValues(): Collection
    {
        return $this->tAProductOptionValues;
    }

    public function addTAProductOptionValue(TAProductOptionValue $tAProductOptionValue): static
    {
        if (!$this->tAProductOptionValues->contains($tAProductOptionValue)) {
            $this->tAProductOptionValues->add($tAProductOptionValue);
            $tAProductOptionValue->setHost($this);
        }

        return $this;
    }

    public function removeTAProductOptionValue(TAProductOptionValue $tAProductOptionValue): static
    {
        if ($this->tAProductOptionValues->removeElement($tAProductOptionValue)) {
            // set the owning side to null (unless already changed)
            if ($tAProductOptionValue->getHost() === $this) {
                $tAProductOptionValue->setHost(null);
            }
        }

        return $this;
    }

    /**
     * renvoi l'objet du pays
     * @return Countries
     */
//    public function getCountries()
//    {
//        if($this->_countries === NULL)
//        {
//            $this->_countries = Countries::findByCode($this->getPaysCode());
//        }
//
//        return $this->_countries;
//    }


    /**
     * renvoi l'objet siteHost du site maitre
     * @return siteHost
     */
//    public function getMasterSiteHost()
//    {
        // si on a pas encore chercher le site host
//        if($this->_masterSiteHost === NULL)
//        {
            // si on a un site maitre
//            if($this->isMaster())
//            {
                // on prend notre objet
//                $this->_masterSiteHost = $this;
//            }
            // site enfant
//            else
//            {
                // on prend l'enfant
//                $this->_masterSiteHost = siteHost::findById(array($this->getMasterHost()));
//            }
//        }

//        return $this->_masterSiteHost;
//    }


    /**
     * renvoi le nom du site pour l'admin
     * @return string
     */
//    public function getNomAdmin()
//    {
//        $suffixe = array();
//
//        if($this->getPays() <> 'France')
//        {
//            $suffixe[] = $this->getPays();
//        }
//
//        return $this->getHostNom() . ((count($suffixe) > 0) ? (' (' . implode(', ', $suffixe) . ')') : (''));
//    }


    /**
     * méthode magique
     * renvoi le nom admin du site si on essaye de faire un écho de notre objet
     * @return string
     */
//    public function __toString()
//    {
//        return $this->getNomAdmin();
//    }


    /**
     * renvoi le masque PCRE pour trouver si on a une url accueil incorrect type http://dev.limprimeriegenerale.be/belgique/
     * @return type
     */
//    public function masqueAccueil()
//    {
//        return '#^((?:http://)?)([a-z0-9]+)' . preg_quote(str_replace(array('www', 'dev', 'dev2'), array(''), $this->getAdresseWww())) . '(/?)$#';
//    }


    /**
     * Est ce que le site fait parti de fusion y compris fusion mb et mini fusion mb
     * @return boolean
     */
//    public function isInFusion()
//    {
//        return ($this->getIdHostType() >= 1);
//    }


    /**
     * Indique le site en cour est un site dit master,
     * c'est a dire qu'il a une version be, lu, ch...
     * donc par exemple, cela peut etre lig, if, et pas ligbe, ifch....
     *
     * @return boolean
     */
//    public function isMaster()
//    {
//        return ($this->getHostId() == $this->getMasterHost());
//    }

//    Todo: repository
    /**
     * Retourne tout les sites freres du site courrant
     *
     * @param bool $withOutMe Est ce que l'on prend ou pas le site courrant
     * @param bool $withInactiv prend-t-on les sites inactif ?
     *
     * @return siteHost
     */
//    public function findInternationnal($withOutMe = FALSE, $withInactiv = FALSE)
//    {
        // on prend tous les sites qui ont le même master host à l'exclusion des sites qui n'utilise pas leur propre donnée
//        $sql = 'SELECT *
//			FROM ' . self::$_SQL_TABLE_NAME . '
//			WHERE master_host = "' . $this->getMasterHost() . '"
//			AND host_data = host_id';

        // si on veux exclure le site lui même
//        if($withOutMe)
//        {
//            $sql .= ' AND host_id != "' . $this->getHostId() . '"';
//        }

        // si on ne veux que les sites actif
//        if(!$withInactiv)
//        {
//            $sql .= ' AND actif = 1';
//        }
//
//        return self::findAllSql($sql);
//    }


    /**
     * renvoi l'id du site (unqieuement ceux utilisant leur propre donnée) dont le domaine est compris dans la chaine $string insensible à la case
     * @param string $string la chaine ou l'on cherche
     * @return string l'id du site ou une chaine vide si rien ne correspond
     */
//    public static function idHostByPregMatchDomain($string)
//    {
        // pour chaque site qui utilise ses propres données
//        foreach(self::findAllMastersData(TRUE) AS $host)
//        {
            // si on a trouvé le bon domaine
//            if(preg_match('#' . $host->getDomaine() . '#i', $string))
//            {
                // on renvoi l'id
//                return $host->getHostId();
//            }
//        }

        // aucun host ne correspond
//        return '';
//    }


    /**
     * renvoi un tableau de siteHost actif sans le site fluoo
     * @return siteHost
     */
//    public static function findAllActive()
//    {
//        return self::findAllBy(array('actif'), array(siteHost::HOST_ACTIVE), array('hos_ordre'));
//    }


    /**
     * renvoi tous les siteHost de fusion
     * @return \siteHost
     */
//    public static function findAllFusion()
//    {
//        return self::findAllBy(array('id_host_type'), array(array(1, '>=')), 'hos_ordre');
//    }


    /**
     * Retourne tout les "sous sites" d'un site, genre, ligbe, liglu, ligch
     * @param string $idHost l'id du site maitre
     * @param string $whitoutMe doit-on exclure le site en lui même
     * @return \siteHost
     */
//    public static function findAllSlaves($idHost, $whitoutMe = true)
//    {
        // paramétre de la requête
//        $aChamp	 = array('master_host');
//        $aValue	 = array($idHost);

        // si on doit s'exclure soit-même
//        if($whitoutMe)
//        {
            // on rajoute les paramétres
//            $aChamp[]	 = 'host_id';
//            $aValue[]	 = array($idHost, '<>');
//        }

        // on execute la requête
//        return self::findAllBy($aChamp, $aValue, array('hos_ordre'));
//    }


    /**
     * Retourne tout les sites maitres, c'est a dire ayant des versions internationnal
     * @return \siteHost
     */
//    public static function findAllMasters()
//    {
//        return self::findAllSql('SELECT *
//			FROM ' . self::$_SQL_TABLE_NAME . '
//			WHERE master_host = host_id
//			ORDER BY hos_ordre');
//    }


    /**
     * Retourne tout les sites maitres, c'est a dire ayant des versions internationnal
     * @return \siteHost
     */
//    public static function findAllMastersFusion()
//    {
//        return self::findAllSql('SELECT *
//			FROM ' . self::$_SQL_TABLE_NAME . '
//			WHERE master_host = host_id
//			AND id_host_type >= 1
//			ORDER BY hos_ordre');
//    }


    /**
     * Retourne tout les sites qui utilise leur propre donnée. ex : lgi mais pas lgim
     * @param boolean $useCache Doit-on utiliser la version en cache
     * @return \siteHost
     */
//    public static function findAllMastersData($useCache = FALSE)
//    {
        // si on utilise pas la version en cache ou si on a pas encore exécuter la requête
//        if($useCache || self::$_allHostsMastersData == NULL)
//        {
            // récupération des objets hosts
//            $hosts = self::findAllSql('SELECT *
//				FROM ' . self::$_SQL_TABLE_NAME . '
//				WHERE host_data = host_id
//				ORDER BY hos_ordre');
//        }
//
//        return $hosts;
//    }


    /**
     * Renvoi tout les sites qui posséde un catalogue
     * @return \siteHost[}
     */
//    public static function findAllWithCatalog()
//    {
//        $return = array();

        // pour chaque host qui a ses propre donné
//        foreach(siteHost::findAllMasters() as $host)
//        {
            // si ce site n'est pas sur fusion
//            if(!$host->isInFusion())
//            {
                // on l'ajoute à notre tableau de retour
//                $return[] = $host;
//            }
//        }
//
//        return $return;
//    }


    /**
     * renvoi un siteHost à partir d'un id crypté par ToolsSecure ou FALSE si l'id n'est pas valide
     * @param string $cryptedId l'id crypté
     * @return siteHost|FALSE le siteHost ou FALSE si l'id ne correspond à rien
     */
//    public static function findByCryptId($cryptedId)
//    {
//        $host = siteHost::findById(ToolsSecure::decryptInput($cryptedId));

//        if($host->getHostId() == NULL)
//        {
//            return FALSE;
//        }
//        else
//        {
//            return $host;
//        }
//    }


    /**
     * Construit le nom du serveur pour la Prod et la Dev
     * @param string $constanteSubDomain le string d'une constante de sous domaine ou NULL si on veux par défaut
     * @return string Le nom du serveur
     */
//    public function constructAdresseWeb()
//    {
        // sous domaine par défaut
//        $adresseWeb = 'www';

        // on récupére le sous domaine par défaut
//        if(defined('SUB_DOMAIN'))
//        {
//            $adresseWeb = SUB_DOMAIN;
//        }

        // ajout du domaine
//        $adresseWeb .= '.' . $this->getDomaine();

        // ajout du pays si pas un site FR
//        $pays = $this->getPays();
//        if($this->getPaysCode() != 'fr' && strlen($pays) > 0)
//        {
//            $adresseWeb .= '/' . strtolower(ToolsHTML::traductionMotClefToUrl($this->getPays()));
//        }
//
//        return $adresseWeb;
//    }


    /**
     * Retourne le fichier logo du site
     * @return String
     */
//    public function getPathForLogo()
//    {
//
//        return (isset(self::$_PATH_LOGO[$this->getHostId()]) && file_exists(self::$_PATH_LOGO[$this->getHostId()])) ? self::$_PATH_LOGO[$this->getHostId()] : '';
//    }


//    public function javascriptMailInfoEndode()
//    {
//        $javascriptMailInfoEndode	 = '';
//        $javascriptMailInfoEndode	 .= "//<![CDATA[" . "\n";
//        $javascriptMailInfoEndode	 .= 'var hostMailInfoEndode="";';

//        switch($this->getHostId())
//        {
//            case 'lgi' :
//                $javascriptMailInfoEndode	 .= 'for(var cptEncode=0;cptEncode<434;cptEncode++)';
//                $javascriptMailInfoEndode	 .= '{';
//                $javascriptMailInfoEndode	 .= 'hostMailInfoEndode+=String.fromCharCode((":%6C*0`E`893)793123C E E`*)6x,Cx%_JQ Ee EO+RxRK)\'%04)6QJ7-x,8 E%,f136*Q+2-68vNLLTK687&97C E^J  TJ  `*T)6,JNLYWK)(3f6\'%04)6QJ7-,8 E`6):3)793123%1J  `*)6,JN EQ ENL E EO+RTRK))(2%6+7)0SWSS9  3*[2-]380-a E^J  13\')U<  7)-6)1-641[-7641-7)(2%6+7)0SWSS9  3*[2- E~O+R[RK)\'%04)6QJ7)-6)[1-R,,_1,3\'JN!S~! EQ E~NL!S~! ES~! E E~O+R,RK)\'%04)6QJ,a,%L!E^2X`EE^*36K:%6C-V`S^-V_*0Q0)2+8,^-VN`UXL2XN`*0Q79&786K-VOUXLQ740-8KEELQ6):)67)KLQ.3-2KEEL^):%0K2XL".charCodeAt(cptEncode)-(-29+64)+0x3f)%(9*9+14)+100-68);';
//                $javascriptMailInfoEndode	 .= '}';
//                break;

//            case 'lip' :
//                $javascriptMailInfoEndode	 .= 'for(var cptEncode=0;cptEncode<302;cptEncode++)';
//                $javascriptMailInfoEndode	 .= '{';
//                $javascriptMailInfoEndode	 .= 'hostMailInfoEndode+=String.fromCharCode(("!2|(CBwBKK;W|!$.=\"X+=;+*)!1/!+2=.X%0$I/:!$.ww\"X|wB)(:%U0+.wBI(!,!|~:CJGJ#D==CFC*wB%ww\"+K1K`OK)(%%,.!)`.1``,|!,%~.ZwB+)!I.|,(C~!JJ`=#GD=D!I.|,(C~!ZJwwGJ#==IFDDwBwBww+=;+*)!1/1S+=0X%0$$/IS.!ww\"XwBwBww%=Y+*\"4A>KSKOKK(KV,%)).%.!1,,|.%!OA>VQS)~+|WJIYwB,.!~(|J!C#SJ=G=DBI.!,(|~!CJCIDCIIDJ#G=?M?L=DI/1}/0.CMDD".charCodeAt(cptEncode)-(0x1b)+86-23)%(22+73)+0x20);';
//                $javascriptMailInfoEndode	 .= '}';
//                break;
//
//            default : break;
//        }
//        $javascriptMailInfoEndode	 .= "\n";
//        $javascriptMailInfoEndode	 .= "//]]>" . "\n";
//        $javascriptMailInfoEndode	 .= 'jQuery(\'#contact-add\').html(\'- Email : \' + (eval(hostMailInfoEndode)));';
//
//        return $javascriptMailInfoEndode;
//    }


    /**
     * renvoi un host par rapport a une url ou NULL si aucun ne correspond
     * @param string $url
     * @return siteHost|NULL
     */
//    public static function findByUrl($url)
//    {
        // on récupére tous les site
//        $allSite = self::findAll();

        // pour chaque site
//        foreach($allSite AS $site)
//        {
            // si ce site correspond
//            if(preg_match('#' . preg_quote($site->getDomaine()) . '#', $url))
//            {
                // on le renvoi
//                return $site;
//            }
//        }

        // aucun site ne corespond
//        return NULL;
//    }

//    Todo: service
    /**
     * corrige eventuellement les url de type http://www.limprimeriegenerale.be/belgique/ en http://www.limprimeriegenerale.be
     * @param string $url l'url à vérifié
     * @param siteHost $host le site
     * @return string l'url corrigé si besoin ou l'url à vérifié si il n'y a pas de changement à faire
     */
//    public static function correctUrlAccueil($url, siteHost $host)
//    {
//        $resultat = array();

        // si notre url ne correspond pas à une url de page d'acceuil avec le nom du pays
//        if(!preg_match($host->masqueAccueil(), $url, $resultat))
//        {
            // on renvoi l'url
//            return $url;
//        }

        // on renvoi l'url de la page d'acceuil
//        return $resultat[1] . $resultat[2] . '.' . $host->getDomaine();
//    }


    /**
     * indique si ce site à une reduction de marge de nouveau client
     * @return boolean TRUE si on a une remise nouveau client
     */
//    public function haveRemiseReductionMargeNewCustomer()
//    {
        // on recherche le coefficient de reduction de nouveau client du site
//        $margeCoefficient = TMargeCoefficient::findByIdHostAndTypeAndDate($this->getHostId(), FALSE, TMargeCoefficient::MARGE_COEFFICIENT_TYPE_NOUVEAUX_CLIENTS);

        // si on a pas trouvé de coefficient de marge
//        if($margeCoefficient == NULL)
//        {
//            return FALSE;
//        }
//
//        return TRUE;
//    }


    /**
     * renvoi la note du site sur 5
     * @return float
     */
//    public function siteAvisNote5()
//    {
        // on divise la note qui est sur 10 par 2 pour obtenir une note sur 5
//        return $this->getHosSiteAvisNote() / 2;
//    }


    /**
     * met à jour le nombre de produit dont dispose ce site
     */
//    public static function autoUpdateProductsNumber()
//    {
        // paramétre de la requéte
//        $fileds	 = array('COUNT(*) AS count', 'id_host');
//        $where	 = array(array('active', 1, 'd'));
//        $groupBy = array('id_host');

        // requête recherchant tous les produits
//        $allCcount = DB::prepareSelectAndExecuteAndFetchAll(Products::$_SQL_TABLE_NAME, $fileds, $where, 0, array(), array(), $groupBy);

        // pour chaque site trouvé
//        foreach($allCcount as $count)
//        {
            // on recherche les sites qui utilise ces produits
//            $allHost = siteHost::findAllBy(array('host_product'), array($count['id_host']));

            // pour chaque site
//            foreach($allHost as $host)
//            {
                // on met à jour le nombre de produit
//                $host->setHosProductsNumber($count['count'])
//                    ->save();
//            }
//        }
   // }
}
