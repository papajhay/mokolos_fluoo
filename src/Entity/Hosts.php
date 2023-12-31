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

    #[ORM\Column(type: 'string')]
    // old: $masterHost
    private ?string $master = null;

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

    #[ORM\Column(length: 255)]
    private ?string $googleAnalyticsId = null;

    #[ORM\Column(nullable: true)]
    private ?int $googleAdWordsId = null;

    #[ORM\Column(length: 255, nullable: true)]
    // old: $hosGoogleIdConversion
    private ?string $googleIdConversion = null;

    public function getGoogleIdConversion(): ?string
    {
        return $this->googleIdConversion;
    }

    public function setGoogleIdConversion(?string $googleIdConversion): void
    {
        $this->googleIdConversion = $googleIdConversion;
    }

    #[ORM\Column(length: 255, nullable: true)]
    // old: $hosGoogleAdWordsRemarketingId
    private ?string $googleAdWordsRemarketingId = null;

    #[ORM\Column(nullable: true)]
    // old: $hosBingAdsId
    private ?int $bingAdsId = null;

    #[ORM\Column(length: 255)]
    // old: $codeLangue
    private ?string $languageCode = null;

    #[ORM\Column(nullable: true)]
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

    #[ORM\Column(length: 255, nullable: true)]
    // old: $hosFacebookUrl
    private ?string $facebookUrl = null;

    #[ORM\Column(length: 255, nullable: true)]
    // old: $hosFacebookAppId
    private ?string $facebookAppId = null;

    #[ORM\Column(length: 255, nullable: true)]
    // old: $hosCreditName
    private ?string $creditName = null;

    #[ORM\Column(length: 255, nullable: true)]
    // old: $hosHrefLang
    private ?string $referenceLanguage = null;

    #[ORM\Column]
    // old: $hosSiteAvisNote
    private ?float $noticeRating = 0;

    #[ORM\Column]
    // old: $hosSiteAvisNbr
    private ?int $opinionNumber = 0;

    #[ORM\Column(length: 255, nullable: true)]
    // old: $hosSiteSecretKey
    private ?string $secretkey = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hosSiteId = null;

    #[ORM\Column]
    // old: $hosAmountPremium
    private ?float $amountPremium = 1000;

    #[ORM\Column]
    // old: $hosProductsNumber
    private ?int $productNumber = null;

    #[ORM\Column]
    private ?int $hosDelay = 0;

    #[ORM\Column]
    // old: $hosPriceDecimal
    private ?float $priceDecimal = 0;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TTxt::class)]
    private Collection $tTxts;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TCmsPage::class)]
    private Collection $tCmsPages;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TProductHost::class)]
    private Collection $tProductHosts;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TAVariantOptionValue::class)]
    private Collection $tAVariantOptionValues;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TProductHostMoreViewed::class)]
    private Collection $tProductHostMoreVieweds;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TAHostCmsBloc::class)]
    private Collection $tAHostCmsBlocs;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TAProductMeta::class)]
    private Collection $tAProductMetas;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TAProductOption::class)]
    private Collection $tAProductOptions;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TAProductOptionValue::class)]
    private Collection $tAProductOptionValues;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TCategory::class, orphanRemoval: true)]
    private Collection $tCategories;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: TProductHostAcl::class)]
    private Collection $tProductHostAcls;

    public function __construct()
    {
        $this->tAHostCmsBlocs = new ArrayCollection();
        $this->tAProductMetas = new ArrayCollection();
        $this->tAProductOptions = new ArrayCollection();
        $this->tAProductOptionValues = new ArrayCollection();
        $this->tTxts = new ArrayCollection();
        $this->tCmsPages = new ArrayCollection();
        $this->tProductHosts = new ArrayCollection();
        $this->tAVariantOptionValues = new ArrayCollection();
        $this->tProductHostMoreVieweds = new ArrayCollection();
        $this->tCategories = new ArrayCollection();
        $this->tProductHostAcls = new ArrayCollection();
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

    public function getMaster(): ?string
    {
        return $this->master;
    }

    public function setMaster(string $master): static
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

    public function setGoogleAnalyticsId(string $googleAnalyticsId): static
    {
        $this->googleAnalyticsId = $googleAnalyticsId;

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

    public function getAmountPremium(): ?float
    {
        return $this->amountPremium;
    }

    public function setAmountPremium(float $amountPremium): static
    {
        $this->amountPremium = $amountPremium;

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
     * @return Collection<int, TTxt>
     */
    public function getTTxts(): Collection
    {
        return $this->tTxts;
    }

    public function addTTxt(TTxt $tTst): static
    {
        if (!$this->tTxts->contains($tTst)) {
            $this->tTxts->add($tTst);
            $tTst->setHost($this);
        }

        return $this;
    }

    public function removeTTxt(TTxt $tTst): static
    {
        if ($this->tTxts->removeElement($tTst)) {
            // set the owning side to null (unless already changed)
            if ($tTst->getHost() === $this) {
                $tTst->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TCmsPage>
     */
    public function getTCmsPages(): Collection
    {
        return $this->tCmsPages;
    }

    public function addTCmsPage(TCmsPage $tCmsPage): static
    {
        if (!$this->tCmsPages->contains($tCmsPage)) {
            $this->tCmsPages->add($tCmsPage);
            $tCmsPage->setHost($this);
        }

        return $this;
    }

    public function removeTCmsPage(TCmsPage $tCmsPage): static
    {
        if ($this->tCmsPages->removeElement($tCmsPage)) {
            // set the owning side to null (unless already changed)
            if ($tCmsPage->getHost() === $this) {
                $tCmsPage->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TProductHost>
     */
    public function getTProductHosts(): Collection
    {
        return $this->tProductHosts;
    }

    public function addTProductHost(TProductHost $tProductHost): static
    {
        if (!$this->tProductHosts->contains($tProductHost)) {
            $this->tProductHosts->add($tProductHost);
            $tProductHost->setHost($this);
        }

        return $this;
    }

    public function removeTProductHost(TProductHost $tProductHost): static
    {
        if ($this->tProductHosts->removeElement($tProductHost)) {
            // set the owning side to null (unless already changed)
            if ($tProductHost->getHost() === $this) {
                $tProductHost->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TAVariantOptionValue>
     */
    public function getTAVariantOptionValues(): Collection
    {
        return $this->tAVariantOptionValues;
    }

    public function addTAVariantOptionValue(TAVariantOptionValue $tAVariantOptionValue): static
    {
        if (!$this->tAVariantOptionValues->contains($tAVariantOptionValue)) {
            $this->tAVariantOptionValues->add($tAVariantOptionValue);
            $tAVariantOptionValue->setHost($this);
        }

        return $this;
    }

    public function removeTAVariantOptionValue(TAVariantOptionValue $tAVariantOptionValue): static
    {
        if ($this->tAVariantOptionValues->removeElement($tAVariantOptionValue)) {
            // set the owning side to null (unless already changed)
            if ($tAVariantOptionValue->getHost() === $this) {
                $tAVariantOptionValue->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TProductHostMoreViewed>
     */
    public function getTProductHostMoreVieweds(): Collection
    {
        return $this->tProductHostMoreVieweds;
    }

    public function addTProductHostMoreViewed(TProductHostMoreViewed $tProductHostMoreViewed): static
    {
        if (!$this->tProductHostMoreVieweds->contains($tProductHostMoreViewed)) {
            $this->tProductHostMoreVieweds->add($tProductHostMoreViewed);
            $tProductHostMoreViewed->setHost($this);
        }

        return $this;
    }

    public function removeTProductHostMoreViewed(TProductHostMoreViewed $tProductHostMoreViewed): static
    {
        if ($this->tProductHostMoreVieweds->removeElement($tProductHostMoreViewed)) {
            // set the owning side to null (unless already changed)
            if ($tProductHostMoreViewed->getHost() === $this) {
                $tProductHostMoreViewed->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TCategory>
     */
    public function getTCategories(): Collection
    {
        return $this->tCategories;
    }

    public function addTCategory(TCategory $tCategory): static
    {
        if (!$this->tCategories->contains($tCategory)) {
            $this->tCategories->add($tCategory);
            $tCategory->setHost($this);
        }

        return $this;
    }

    public function removeTCategory(TCategory $tCategory): static
    {
        if ($this->tCategories->removeElement($tCategory)) {
            // set the owning side to null (unless already changed)
            if ($tCategory->getHost() === $this) {
                $tCategory->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TProductHostAcl>
     */
    public function getTProductHostAcls(): Collection
    {
        return $this->tProductHostAcls;
    }

    public function addTProductHostAcl(TProductHostAcl $tProductHostAcl): static
    {
        if (!$this->tProductHostAcls->contains($tProductHostAcl)) {
            $this->tProductHostAcls->add($tProductHostAcl);
            $tProductHostAcl->setHost($this);
        }

        return $this;
    }

    public function removeTProductHostAcl(TProductHostAcl $tProductHostAcl): static
    {
        if ($this->tProductHostAcls->removeElement($tProductHostAcl)) {
            // set the owning side to null (unless already changed)
            if ($tProductHostAcl->getHost() === $this) {
                $tProductHostAcl->setHost(null);
            }
        }

        return $this;
    }

    public function getGoogleAdWordsId(): ?int
    {
        return $this->googleAdWordsId;
    }

    public function setGoogleAdWordsId(?int $googleAdWordsId): void
    {
        $this->googleAdWordsId = $googleAdWordsId;
    }

    public function getGoogleAdWordsRemarketingId(): ?string
    {
        return $this->googleAdWordsRemarketingId;
    }

    public function setGoogleAdWordsRemarketingId(?string $googleAdWordsRemarketingId): void
    {
        $this->googleAdWordsRemarketingId = $googleAdWordsRemarketingId;
    }

    public function getFacebookUrl(): ?string
    {
        return $this->facebookUrl;
    }

    public function setFacebookUrl(?string $facebookUrl): void
    {
        $this->facebookUrl = $facebookUrl;
    }

    public function getFacebookAppId(): ?string
    {
        return $this->facebookAppId;
    }

    public function setFacebookAppId(?string $facebookAppId): void
    {
        $this->facebookAppId = $facebookAppId;
    }

    public function getHosSiteId(): ?string
    {
        return $this->hosSiteId;
    }

    public function setHosSiteId(?string $hosSiteId): void
    {
        $this->hosSiteId = $hosSiteId;
    }

    public function getSecretkey(): ?string
    {
        return $this->secretkey;
    }

    public function setSecretkey(?string $secretkey): void
    {
        $this->secretkey = $secretkey;
    }

    public function getBingAdsId(): ?int
    {
        return $this->bingAdsId;
    }

    public function setBingAdsId(?int $bingAdsId): void
    {
        $this->bingAdsId = $bingAdsId;
    }

    /*
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

    /*
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

    /*
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

    /*
     * méthode magique
     * renvoi le nom admin du site si on essaye de faire un écho de notre objet
     * @return string
     */
    //    public function __toString()
    //    {
    //        return $this->getNomAdmin();
    //    }

    /*
     * renvoi le masque PCRE pour trouver si on a une url accueil incorrect type http://dev.limprimeriegenerale.be/belgique/
     * @return type
     */
    //    public function masqueAccueil()
    //    {
    //        return '#^((?:http://)?)([a-z0-9]+)' . preg_quote(str_replace(array('www', 'dev', 'dev2'), array(''), $this->getAdresseWww())) . '(/?)$#';
    //    }

    /*
     * Est ce que le site fait parti de fusion y compris fusion mb et mini fusion mb
     * @return boolean
     */
    //    public function isInFusion()
    //    {
    //        return ($this->getIdHostType() >= 1);
    //    }

    /*
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
    /*
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

    /*
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

    /*
     * renvoi un tableau de siteHost actif sans le site fluoo
     * @return siteHost
     */
    //    public static function findAllActive()
    //    {
    //        return self::findAllBy(array('actif'), array(siteHost::HOST_ACTIVE), array('hos_ordre'));
    //    }

    /*
     * renvoi tous les siteHost de fusion
     * @return \siteHost
     */
    //    public static function findAllFusion()
    //    {
    //        return self::findAllBy(array('id_host_type'), array(array(1, '>=')), 'hos_ordre');
    //    }

    /*
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

    /*
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

    /*
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

    /*
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

    /*
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

    /*
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

    /*
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

    /*
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

    /*
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
    /*
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

    /*
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

    /*
     * renvoi la note du site sur 5
     * @return float
     */
    //    public function siteAvisNote5()
    //    {
    // on divise la note qui est sur 10 par 2 pour obtenir une note sur 5
    //        return $this->getHosSiteAvisNote() / 2;
    //    }

    /*
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
