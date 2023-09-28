<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TProductHostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TProductHostRepository::class)]
class TProductHost
{
    /**
     * constante utilisé pour un produit original.
     */
    // const ID_ORIGINAL = 0;

    /**
     * constante utilisé pour un produit de type variant.
     */
    // const ID_VARIANT = 1;

    /**
     * nom de la table de localisation.
     * @var string
     */
    // public static $_SQL_LOCALIZATION_TABLE_NAME = 'fusion.tl_produit_host';

    /**
     * nom des champs localisé.
     * @var array
     */
    //    public static $_SQL_LOCALIZATION_FIELDS = array(
    //        'pro_hos_libelle',
    //        'pro_hos_informations',
    //        'pro_hos_libelle_url',
    //        'pro_hos_titre',
    //        'pro_hos_sous_titre',
    //        'pro_hos_description_1',
    //        'pro_hos_description_2',
    //        'pro_hos_description_3',
    //        'pro_hos_meta_title',
    //        'pro_hos_meta_description',
    //        'pro_hos_meta_description2',
    //        'pro_hos_footer_link'
    //    );

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?int $satelliteIdParent = null;

    #[ORM\Column]
    private ?string $isActif = null;

    #[ORM\Column]
    private ?string $informations = null;

    #[ORM\Column]
    private ?string $urlPictoFile = null;

    #[ORM\Column]
    private ?int $rattachement = null;

    #[ORM\Column]
    private ?string $libelleUrl = null;

    #[ORM\Column]
    private ?int $productHostOrder = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $subtitle = null;

    #[ORM\Column(length: 255)]
    private ?string $description1 = null;

    #[ORM\Column(length: 255)]
    private ?string $description2 = null;

    #[ORM\Column(length: 255)]
    private ?string $description3 = null;

    #[ORM\Column(length: 255)]
    private ?string $metaTitle = null;

    #[ORM\Column(length: 255)]
    private ?string $metaDescription2 = null;

    #[ORM\Column(length: 255)]
    private ?string $minPrice = null;

    #[ORM\Column]
    private ?int $idProduct = null;

    #[ORM\ManyToOne(inversedBy: 'tProductHosts')]
    // private $_host		 = null;
    // private ?int $idHost = null;
    private ?Hosts $host = null;

    #[ORM\Column(length: 255)]
    private ?string $footerLink = null;

    // date de derniere modification
    #[ORM\Column(length: 255)]
    private ?string $lastUpdate = null;

    // date de derniere modification du listing des produits si il existe
    #[ORM\Column(length: 255)]
    private ?string $listLastUpdate = null;

    // affiche ou pas en bas sur page accueil (n'affiche pas par defaut)
    #[ORM\Column]
    private ?int $showOnHomeBottom = null;

    // affiche ou pas en haut sur page accueil (n'affiche pas par defaut)
    #[ORM\Column]
    private ?int $showOnHomeTop = null;

    // indique si ce produit host est un variant ou un original
    #[ORM\Column]
    private ?int $variant = null;

    // nombre de produit satellite rattaché à ce produit
    #[ORM\Column]
    private ?int $countSatellite = null;

    // objet dateHeure de la derniére modification
    #[ORM\Column]
    private ?\DateTimeImmutable $dateTimeLastUpdate = null;

    // objet dateHeure de la derniére modification du listing des pages smarts
    #[ORM\Column]
    private ?\DateTimeImmutable $dateTimeListLastUpdate = null;

    #[ORM\OneToMany(mappedBy: 'productHost', targetEntity: TTxt::class)]
    private Collection $tTxts;

    #[ORM\OneToMany(mappedBy: 'productHost', targetEntity: TProductHostMoreViewed::class)]
    private Collection $tProductHostMoreVieweds;

    #[ORM\OneToMany(mappedBy: 'tProductHost', targetEntity: TAVariantOptionValue::class, orphanRemoval: true)]
    private Collection $tAVariantOptionValues;

    /**
     * objet siteHost du site de ce produit host.
     * @var siteHost
     */
    #[ORM\OneToOne(inversedBy: 'tProductHost', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?TProduct $tProduct = null;

    /**
     * un objet TAProduitFournisseur lié à notre objet.
     * @var TAProduitFournisseur
     */
    #[ORM\OneToOne(inversedBy: 'tProductHost', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?TAProductProvider $tAProductProvider = null;

    /**
     * liste des acl lié à ce produit (on récupére les acl du produit parent dans le cas d'un satellite.
     * @var TProduitHostAcl
     */
    // private $_aProduitHostAcl = null;
    #[ORM\OneToMany(mappedBy: 'tProductHost', targetEntity: TProductHostAcl::class)]
    private Collection $tProductHostAcl;

    /**
     * Renvoi le diaporama des images de pub du produit.
     * @var TCmsDiapo
     */
    #[ORM\OneToMany(targetEntity: TCmsDiapo::class, mappedBy: 'sliderProductAdsHost')]
    private Collection $sliderProductAds;

    /**
     * Renvoi le diaporama des images de détails du produit.
     * @var TCmsDiapo
     */
    #[ORM\OneToMany(targetEntity: TCmsDiapo::class, mappedBy: 'sliderProductDetailHost')]
    private Collection $sliderProductDetail;

    /**
     * produit meta parent si notre produit en posséde un.
     * @var TProduitHost
     */
    // private $_produitMetaParent;
    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'tProductHosts')]
    private ?self $produitMetaParent = null;

    #[ORM\OneToMany(mappedBy: 'produitMetaParent', targetEntity: self::class)]
    private Collection $tProductHosts;

    // TODO Relation
    /**
     * Le prix de vente HT mini.
     * @var strinf
     */
    // private $_minPrice = null;

    public function __construct()
    {
        $this->tTxts = new ArrayCollection();
        $this->tAVariantOptionValues = new ArrayCollection();
        $this->tProductHostMoreVieweds = new ArrayCollection();
        $this->tProductHostAcl = new ArrayCollection();
        $this->sliderProductAds = new ArrayCollection();
        $this->sliderProductDetail = new ArrayCollection();
        $this->tProductHosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getSatelliteIdParent(): ?int
    {
        return $this->satelliteIdParent;
    }

    public function setSatelliteIdParent(int $satelliteIdParent): static
    {
        $this->satelliteIdParent = $satelliteIdParent;

        return $this;
    }

    public function getIsActif(): ?string
    {
        return $this->isActif;
    }

    public function setIsActif(string $isActif): static
    {
        $this->isActif = $isActif;

        return $this;
    }

    public function getInformations(): ?string
    {
        return $this->informations;
    }

    public function setInformations(string $informations): static
    {
        $this->informations = $informations;

        return $this;
    }

    public function getUrlPictoFile(): ?string
    {
        return $this->urlPictoFile;
    }

    public function setUrlPictoFile(string $urlPictoFile): static
    {
        $this->urlPictoFile = $urlPictoFile;

        return $this;
    }

    public function getRattachement(): ?int
    {
        return $this->rattachement;
    }

    public function setRattachement(int $rattachement): static
    {
        $this->rattachement = $rattachement;

        return $this;
    }

    public function getLibelleUrl(): ?string
    {
        return $this->libelleUrl;
    }

    public function setLibelleUrl(string $libelleUrl): static
    {
        $this->libelleUrl = $libelleUrl;

        return $this;
    }

    public function getProductHostOrder(): ?int
    {
        return $this->productHostOrder;
    }

    public function setProductHostOrder(int $productHostOrder): static
    {
        $this->productHostOrder = $productHostOrder;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): static
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    public function getDescription1(): ?string
    {
        return $this->description1;
    }

    public function setDescription1(string $description1): static
    {
        $this->description1 = $description1;

        return $this;
    }

    public function getDescription2(): ?string
    {
        return $this->description2;
    }

    public function setDescription2(string $description2): static
    {
        $this->description2 = $description2;

        return $this;
    }

    public function getDescription3(): ?string
    {
        return $this->description3;
    }

    public function setDescription3(string $description3): static
    {
        $this->description3 = $description3;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(string $metaTitle): static
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription2(): ?string
    {
        return $this->metaDescription2;
    }

    public function setMetaDescription2(string $metaDescription2): static
    {
        $this->metaDescription2 = $metaDescription2;

        return $this;
    }

    public function getMinPrice(): ?string
    {
        return $this->minPrice;
    }

    public function setMinPrice(string $minPrice): static
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    public function getIdProduct(): ?int
    {
        return $this->idProduct;
    }

    public function setIdProduct(int $idProduct): static
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    public function getHost(): ?Hosts
    {
        return $this->host;
    }

    public function setHost(?Hosts $host): static
    {
        $this->host = $host;

        return $this;
    }

    public function getFooterLink(): ?string
    {
        return $this->footerLink;
    }

    public function setFooterLink(string $footerLink): static
    {
        $this->footerLink = $footerLink;

        return $this;
    }

    public function getLastUpdate(): ?string
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(string $lastUpdate): static
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getListLastUpdate(): ?string
    {
        return $this->listLastUpdate;
    }

    public function setListLastUpdate(string $listLastUpdate): static
    {
        $this->listLastUpdate = $listLastUpdate;

        return $this;
    }

    public function getShowOnHomeBottom(): ?int
    {
        return $this->showOnHomeBottom;
    }

    public function setShowOnHomeBottom(int $showOnHomeBottom): static
    {
        $this->showOnHomeBottom = $showOnHomeBottom;

        return $this;
    }

    public function getShowOnHomeTop(): ?int
    {
        return $this->showOnHomeTop;
    }

    public function setShowOnHomeTop(int $showOnHomeTop): static
    {
        $this->showOnHomeTop = $showOnHomeTop;

        return $this;
    }

    public function getVariant(): ?int
    {
        return $this->variant;
    }

    public function setVariant(int $variant): static
    {
        $this->variant = $variant;

        return $this;
    }

    public function getCountSatellite(): ?int
    {
        return $this->countSatellite;
    }

    public function setCountSatellite(int $countSatellite): static
    {
        $this->countSatellite = $countSatellite;

        return $this;
    }

    public function getDateTimeLastUpdate(): ?\DateTimeImmutable
    {
        return $this->dateTimeLastUpdate;
    }

    public function setDateTimeLastUpdate(\DateTimeImmutable $dateTimeLastUpdate): static
    {
        $this->dateTimeLastUpdate = $dateTimeLastUpdate;

        return $this;
    }

    public function getDateTimeListLastUpdate(): ?\DateTimeImmutable
    {
        return $this->dateTimeListLastUpdate;
    }

    public function setDateTimeListLastUpdate(\DateTimeImmutable $dateTimeListLastUpdate): static
    {
        $this->dateTimeListLastUpdate = $dateTimeListLastUpdate;

        return $this;
    }

    /**
     * @return Collection<int, TTxt>
     */
    public function getTTxts(): Collection
    {
        return $this->tTxts;
    }

    public function addTTxt(TTxt $tTxt): static
    {
        if (!$this->tTxts->contains($tTxt)) {
            $this->tTxts->add($tTxt);
            $tTxt->setProductHost($this);
        }

        return $this;
    }

    public function removeTTxt(TTxt $tTxt): static
    {
        if ($this->tTxts->removeElement($tTxt)) {
            // set the owning side to null (unless already changed)
            if ($tTxt->getProductHost() === $this) {
                $tTxt->setProductHost(null);
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
            $tProductHostMoreViewed->setProductHost($this);
        }

        return $this;
    }

    public function removeTProductHostMoreViewed(TProductHostMoreViewed $tProductHostMoreViewed): static
    {
        if ($this->tProductHostMoreVieweds->removeElement($tProductHostMoreViewed)) {
            // set the owning side to null (unless already changed)
            if ($tProductHostMoreViewed->getProductHost() === $this) {
                $tProductHostMoreViewed->setProductHost(null);
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
            $tAVariantOptionValue->setTProductHost($this);
        }

        return $this;
    }

    public function removeTAVariantOptionValue(TAVariantOptionValue $tAVariantOptionValue): static
    {
        if ($this->tAVariantOptionValues->removeElement($tAVariantOptionValue)) {
            // set the owning side to null (unless already changed)
            if ($tAVariantOptionValue->getTProductHost() === $this) {
                $tAVariantOptionValue->setTProductHost(null);
            }
        }

        return $this;
    }

    public function getTProduct(): ?TProduct
    {
        return $this->tProduct;
    }

    public function setTProduct(TProduct $tProduct): static
    {
        $this->tProduct = $tProduct;

        return $this;
    }

    public function getTAProductProvider(): ?TAProductProvider
    {
        return $this->tAProductProvider;
    }

    public function setTAProductProvider(TAProductProvider $tAProductProvider): static
    {
        $this->tAProductProvider = $tAProductProvider;

        return $this;
    }

    /**
     * @return Collection<int, TProductHostAcl>
     */
    public function getTProductHostAcl(): Collection
    {
        return $this->tProductHostAcl;
    }

    public function addTProductHostAcl(TProductHostAcl $tProductHostAcl): static
    {
        if (!$this->tProductHostAcl->contains($tProductHostAcl)) {
            $this->tProductHostAcl->add($tProductHostAcl);
            $tProductHostAcl->setTProductHost($this);
        }

        return $this;
    }

    public function removeTProductHostAcl(TProductHostAcl $tProductHostAcl): static
    {
        if ($this->tProductHostAcl->removeElement($tProductHostAcl)) {
            // set the owning side to null (unless already changed)
            if ($tProductHostAcl->getTProductHost() === $this) {
                $tProductHostAcl->setTProductHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TCmsDiapo>
     */
    public function getSliderProductAds(): Collection
    {
        return $this->sliderProductAds;
    }

    public function addSliderProductAds(TCmsDiapo $diapo): self
    {
        if (!$this->sliderProductAds->contains($diapo)) {
            $this->sliderProductAds[] = $diapo;
            $diapo->setSliderProductAdsHost($this);
        }

        return $this;
    }

    public function removeSliderProductAds(TCmsDiapo $diapo): self
    {
        if ($this->sliderProductAds->contains($diapo)) {
            $this->sliderProductAds->removeElement($diapo);
            if ($diapo->getSliderProductAdsHost() === $this) {
                $diapo->setSliderProductAdsHost(null);
            }
        }

        return $this;
    }
    /*public function removeSliderProductAds(TCmsDiapo $diapo): self {
        if ($this->sliderProductAds->contains($diapo)) {
            $this->sliderProductAds->removeElement($diapo);
            if ($diapo->getSliderProductAdsHost() === $this) {
                $diapo->setSliderProductAdsHost(null);
            }
        }
        return $this;
    }*/

    /*public function addSliderProductAd(TCmsDiapo $sliderProductAd): static
    {
        if (!$this->sliderProductAds->contains($sliderProductAd)) {
            $this->sliderProductAds->add($sliderProductAd);
        }

        return $this;
    }*/

    public function removeSliderProductAd(TCmsDiapo $sliderProductAd): static
    {
        $this->sliderProductAds->removeElement($sliderProductAd);

        return $this;
    }

    /**
     * @return Collection<int, TCmsDiapo>
     */
    public function getSliderProductDetail(): Collection
    {
        return $this->sliderProductDetail;
    }

    public function addSliderProductDetail(TCmsDiapo $diapo): self
    {
        if (!$this->sliderProductDetail->contains($diapo)) {
            $this->sliderProductDetail[] = $diapo;
            $diapo->setSliderProductDetailHost($this);
        }

        return $this;
    }

    public function removeSliderProductDetail(TCmsDiapo $diapo): self
    {
        if ($this->sliderProductDetail->contains($diapo)) {
            $this->sliderProductDetail->removeElement($diapo);
            if ($diapo->getSliderProductDetailHost() === $this) {
                $diapo->setSliderProductDetailHost(null);
            }
        }

        return $this;
    }

    /*public function setSliderProductDetail(Collection $sliderProductDetail): void
    {
        $this->sliderProductDetail = $sliderProductDetail;
    }*/

    /*public function addSliderProductDetail(TCmsDiapo $sliderProductDetail): static
    {
        if (!$this->sliderProductDetail->contains($sliderProductDetail)) {
            $this->sliderProductDetail->add($sliderProductDetail);
        }

        return $this;
    }*/

    /* public function removeSliderProductDetail(TCmsDiapo $sliderProductDetail): static
     {
         $this->sliderProductDetail->removeElement($sliderProductDetail);

         return $this;
     }*/

    public function getProduitMetaParent(): ?self
    {
        return $this->produitMetaParent;
    }

    public function setProduitMetaParent(?self $produitMetaParent): static
    {
        $this->produitMetaParent = $produitMetaParent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTProductHosts(): Collection
    {
        return $this->tProductHosts;
    }

    public function addTProductHost(self $tProductHost): static
    {
        if (!$this->tProductHosts->contains($tProductHost)) {
            $this->tProductHosts->add($tProductHost);
            $tProductHost->setProduitMetaParent($this);
        }

        return $this;
    }

    public function removeTProductHost(self $tProductHost): static
    {
        if ($this->tProductHosts->removeElement($tProductHost)) {
            // set the owning side to null (unless already changed)
            if ($tProductHost->getProduitMetaParent() === $this) {
                $tProductHost->setProduitMetaParent(null);
            }
        }

        return $this;
    }

    // Todo: Repository
    /*
     * renvoi le dernier idProduitHost utilisé
     * @return int
     */
    //    public static function lastIdProduitHost()
    //    {
    //        $result = DB::prepareSelectAndExecuteAndFetchAll(self::$_SQL_TABLE_NAME, array('MAX(id_produit_host)'), array());
    //
    //        return $result[0]['MAX(id_produit_host)'];
    //    }

    // =================== Methodes de recherche (find) ===================

    /*
     * Renvoi tous les produits avec des filtres
     * @param bool $actifOnly				[=true] Veut-on uniquement les produit actif ?
     * @param int $produitSatellite			[=DbTable::SANS] utilise les attribut DbTable::SANS, DbTable::AVEC, DbTable::UNIQUEMENT. veux-on les produits satellite ?
     * @param string|null $idHost			[=null] null pour chercher pour tous les sites ou id du site
     * @param string|null $idHostForACL		[=null] null pour ne pas chercher d'ACL spécifique ou id du site pour vérifier le
     * @param boolean $metaEnding			[=false] true permet de mettre tous les meta produit à la fin
     * @param int|null $idProduit			[=null] id du produit parent dans le cas des produit satellite ou null pour tous renvoyer
     * @param bool $showOnHomeTop			[=null] null pour prendre tous les produits, 1 pour ne prendre que ceux qui sont situes en haut de la page d'accueil
     * @param int $idCategorie				[=0] 0 pour ne pas prendre en compte la categorie du produit, Id de la categorie a mettre en premier
     * @param int $metaProduit				[=DbTable::AVEC] utilise les attribut DbTable::SANS, DbTable::AVEC, DbTable::UNIQUEMENT. veux-on les meta produits ?
     * @return TProduitHost[]
     */
    //    public static function findAllWithFiltre($actifOnly = true, $produitSatellite = DbTable::SANS, $idHost = null, $idHostForACL = null, $metaEnding = false, $idProduit = null, $showOnHomeTop = null, $idCategorie = 1, $metaProduit = DbTable::AVEC)
    //    {
    //        $champs		 = array();
    //        $valeurs	 = array();
    //        $joinList	 = array();
    //
    //        // on ne veut que les produits actifs
    //        if($actifOnly)
    //        {
    //            // on ne prend que les produit host (pour les acls ca sera gérer plus tard
    //            $champs[]	 = 'pro_hos_is_actif';
    //            $valeurs[]	 = 1;
    //        }
    //
    //        // suivant ce que l'on veux au niveau des produits satellite
    //        switch($produitSatellite)
    //        {
    //            // on ne veux pas les produits satellites
    //            case DbTable::SANS:
    //                $champs[]	 = 'pro_hos_satellite_id_parent';
    //                $valeurs[]	 = 0;
    //                break;
    //
    //            // on veux uniquement les produits satellites
    //            case DbTable::UNIQUEMENT:
    //                $champs[]	 = 'pro_hos_satellite_id_parent';
    //                $valeurs[]	 = array(0, '<>');
    //                break;
    //        }
    //
    //        // suivant ce que l'on veux au niveau des meta produits
    //        switch($metaProduit)
    //        {
    //            // on ne veux pas les meta produits
    //            case DbTable::SANS:
    //                $joinList[]	 = array('join' => 'LEFT JOIN', 'table' => TAProduitMeta::$_SQL_TABLE_NAME, 'alias' => 'pm', 'joinCondition' => 't.id_produit_host = pm.pro_meta_id_parent AND pm.id_host = t.id_host');
    //                $champs[]	 = 'pro_meta_id_parent';
    //                $valeurs[]	 = null;
    //                break;
    //
    // on veux uniquement les meta produits
    //            case DbTable::UNIQUEMENT:
    //                $joinList[] = array('join' => 'JOIN', 'table' => TAProduitMeta::$_SQL_TABLE_NAME, 'alias' => 'pm', 'joinCondition' => 't.id_produit_host = pm.pro_meta_id_parent AND pm.id_host = t.id_host');
    //                break;
    //        }
    //
    // si on a veux limitter à un certain site
    //        if($idHost !== null)
    //        {
    //            $champs[]	 = 'id_host';
    //            $valeurs[]	 = $idHost;
    //        }
    //
    // si on veux uniquement les produit satellite lié à un certain produit
    //        if($idProduit !== null)
    //        {
    //            $champs[]	 = 'pro_hos_satellite_id_parent';
    //            $valeurs[]	 = $idProduit;
    //        }
    //
    // filtre poour les produits qui sont [=1] ou ne sont pas [=0] en haut de la page d'accueil
    //        if($showOnHomeTop !== null)
    //        {
    //            $champs[]	 = 'pro_hos_show_on_home_top';
    //            $valeurs[]	 = $showOnHomeTop;
    //        }
    //
    // si on a un ordre de tri par categorie
    //        if($idCategorie > 1)
    //        {
    //            $champs[]	 = 'phc.id_categorie';
    //            $valeurs[]	 = $idCategorie;
    //            $joinList[]	 = array('table' => TAProduitHostCategorie::$_SQL_TABLE_NAME, 'alias' => 'phc', 'joinCondition' => 'phc.id_produit_host = t.id_produit_host');
    //            //$order      = array('phc.id_categorie=' . $idCategorie . ' DESC', 'phc.id_categorie ASC', 'pro_hos_ordre', 'pro_hos_libelle');
    //        }
    //        $order = array('pro_hos_ordre', 'pro_hos_libelle');
    //
    //        // on recupere un find all by avec les parametres
    //        $aTProduitHost = self::findAllBy($champs, $valeurs, $order, 0, $joinList);
    //
    //        // activation ou desactivation de produits en fonction des ACL
    //        if($idHostForACL !== null)
    //        {
    //            // chargement des ACL des produits du site
    //            $aTProduitHostAcl = TProduitHostAcl::findAllBy(array('id_host'), array($idHostForACL));
    //
    //            // pour chaque produit
    //            foreach($aTProduitHost as $idProduitHost => $tProduitHost)
    //            {
    // pour chaque ACL de produit
    //                foreach($aTProduitHostAcl as $tProduitHostAcl)
    //                {
    // des qu'on trouve une ACL pour le produit ou pour le produit parent dans le cas des produits satellites
    //                    if($tProduitHostAcl->getIdProduitHost() === $tProduitHost->getIdProduitHost() || $tProduitHostAcl->getIdProduitHost() === $tProduitHost->getProHosSatelliteIdParent())
    //                    {
    // on ne veut que les produits actifs et que ce produit est désactivé
    //                        if($actifOnly && $tProduitHostAcl->getProHosAclActif() == 0)
    //                        {
    // on le supprime de notre tableau
    //                            unset($aTProduitHost[$idProduitHost]);
    //                        }
    //                        else
    //                        {
    // on met à jour le statut actif
    //                            $tProduitHost->setProHosIsActif($tProduitHostAcl->getProHosAclActif());
    //                        }
    //
    //                        break;
    //                    }
    //                }
    //            }
    //        }
    //
    // si on doit placer les meta produit a la fin
    //        if($metaEnding)
    //        {
    // on calcul l'id qu'on va donner à notre meta produit
    //            $idMeta = count($aTProduitHost) + 1;
    //
    // on parcour le tableau
    //            foreach($aTProduitHost as $key => $tProduitHost)
    //            {
    //                // si nous avons un produit meta
    //                if($tProduitHost->isMeta())
    //                {
    //                    // on le déplace à la fin du tableau
    //                    $aTProduitHost[$idMeta] = $tProduitHost;
    //                    unset($aTProduitHost[$key]);
    //
    //                    // on incrémente notre id pour le prochain meta produit
    //                    $idMeta++;
    //                }
    //            }
    //        }
    //
    //        // on renvoi le resultat
    //        return $aTProduitHost;
    //    }

    /*
     * renvoi tout les produit host enfant de ce meta produit
     * @param boolean $checkAcl doit-on vérifier les acl ? mettre False dans l'admin
     * @param boolean $actifOnly doit-on renvoyer uniquement les actifs ?
     * @param int $idProductInFirstPlace id du produit a mettre en premier positop,
     * @return \TProduitHost[]
     */
    //    public function findAllMetaChild($checkAcl = true, $actifOnly = true, $idProductInFirstPlace = null)
    //    {
    //        // si notre produit n'est pas un meta produit
    //        if(!$this->isMeta())
    //        {
    //            // pas besoin de chercher on envoi un tableau vide
    //            return array();
    //        }
    //
    //        // paramétre pour trouver les produits meta
    //        $fields	 = array('pm.pro_meta_id_parent', 't.id_host');
    //        $values	 = array($this->getIdProduitHost(), $this->getIdHost());
    //
    // si on ne veux que les produits actifs
    //        if($actifOnly)
    //        {
    //            $fields[]	 = 'pro_hos_is_actif';
    //            $values[]	 = 1;
    //        }
    //
    // on recupere tous les produits meta enfant actif
    //        $allProduit = self::findAllBy($fields, $values, array('pro_hos_ordre', 'pro_hos_libelle'), 0, array(array('table' => TAProduitMeta::$_SQL_TABLE_NAME, 'alias' => 'pm', 'joinCondition' => 'pm.pro_meta_id_child = t.id_produit_host')));
    //
    // si on doit vérifier les acl
    //        if($checkAcl)
    //        {
    // pour chaque produit
    //            foreach($allProduit AS $key => $produit)
    //            {
    // on recupere les acl pour la desactivation des produits sur les site type ligbe
    //                $produitHostAcl = TProduitHostAcl::findById(array(
    //                    $produit->getIdProduitHost(),
    //                    System::getCurrentHost()->getHostId()));
    //
    // produit acl inactif
    //                if($produitHostAcl->getIdProduitHost() && !($produitHostAcl->getProHosAclActif()))
    //                {
    // on le supprime du tableau
    //                    unset($allProduit[$key]);
    //                }
    //            }
    //        }
    //
    // si on doit placer un produit en premiere place
    //        if($idProductInFirstPlace)
    //        {
    //            // pour chaque produit
    //            foreach($allProduit AS $key => $produit)
    //            {
    //                // si on a le produit à mettre en premier
    //                if($produit->getIdProduitHost() == $idProductInFirstPlace)
    //                {
    //                    // on le supprime du tableau
    //                    unset($allProduit[$key]);
    //
    //                    // on le rajoute avec une clef à -1 pour etre en premier
    //                    $allProduit[-1] = $produit;
    //
    //                    // on quitte la boucle car inutile d'aller plus loin
    //                    break;
    //                }
    //            }
    //
    //            // on retrie le tableau
    //            ksort($allProduit);
    //        }
    //
    //        return $allProduit;
    //    }

    /*
     * renvoi tout les produit host que l'on pourrait ajouter à noter meta produit
     * @return \TProduitHost
     */
    //    public function findAllMetaDisponible()
    //    {
    // si notre produit est un meta produit
    //        if($this->isMeta())
    //        {
    //            $tabIdHost = array();
    //
    // on récupére tous les prduit host enfant
    //            $allChild = $this->findAllMetaChild(false, false);
    //
    // pour chaque produit enfant
    //            foreach($allChild AS $child)
    //            {
    // on ajoute le produit host dans la table
    //                $tabIdHost[$child->getIdProduitHost()] = $child->getIdProduitHost();
    //            }
    //
    // création des paramétres pour le findAll
    //            $aParam = DbTable::makeFieldAndValueArrayForFindAll('id_produit_host', $tabIdHost, 'NOT IN');
    //
    // ajout des autres paramétres
    //            $aParam['aChamp'][]	 = 'pro_hos_satellite_id_parent';
    //            $aParam['aChamp'][]	 = 'id_host';
    //            $aParam['aValue'][]	 = 0;
    //            $aParam['aValue'][]	 = $this->getIdHost();
    //
    //            // recherche des produits
    //            return self::findAllBy($aParam['aChamp'], $aParam['aValue'], 'pro_hos_libelle');
    //        }
    //        // sinon c'est pas un meta produit donc on renvoi un tableau vide
    //        else
    //        {
    //            return self::findAllBy(array('pro_hos_satellite_id_parent', 'id_host'), array(0, $this->getIdHost()), 'pro_hos_libelle');
    //        }
    //    }

    /*
     * renvoi tous les produits pour la selection d'un fournisseur avec l'id du produit en clef
     * @param fournisseur $fournisseur le fournisseur
     * @return TProduitHost[]
     */
    //    public static function findAllForSelectionFournisseur($fournisseur)
    //    {
    //        $return = array();
    //
    //        // tableau qui va contenir tous les id de fournisseur p24 pour ne pas avoir de doublon
    //        $tabProFouIdSource = array();
    //
    //        // jointure de la table
    //        $jointure = array(
    //            0 => array(
    //                'table'			 => TAProduitFournisseur::$_SQL_TABLE_NAME,
    //                'alias'			 => 'pf',
    //                'joinCondition'	 => 't.id_produit = pf.id_produit'));
    //
    //        // récupération de tous les produits actif non satellite qui ont le bon fournisseur, uniquement ceux de lig pour ne pas avoir de doublon
    //        $allProductHost = self::findAllBy(array('id_fournisseur', 'pro_hos_satellite_id_parent', 't.id_host'), array($fournisseur->getMasterFournisseurId(), 0, $fournisseur->getFouIdHostForSelection()), 'pro_hos_ordre, pro_hos_libelle', 0, $jointure);
    //
    //        // comme on peux difficilement faire des condition avec OR dans findallby on va prendre chaque produit
    //        foreach($allProductHost AS $idProduct => $productHost)
    //        {
    //            // si le produit n'est pas affiché en page d'acceuil et si il n'appartient pas à un produit meta
    //            if(!$productHost->getProHosShowOnHomeTop() && !$productHost->isMetaChild())
    //            {
    //                // on le supprime de notre tableau
    //                unset($allProductHost[$idProduct]);
    //            }
    //            // on verifie si on a déjà un produit qui correspond au même produit chez p24
    //            elseif(in_array($productHost->getProduitFournisseur()->getProFouLibelleSource(), $tabProFouIdSource))
    //            {
    //                // on le supprime de notre tableau
    //                unset($allProductHost[$idProduct]);
    //            }
    //            // on garde le produit
    //            else
    //            {
    //                // on ajoute l'id du fournisseur dans notre tableau
    //                $tabProFouIdSource[] = $productHost->getProduitFournisseur()->getProFouLibelleSource();
    //            }
    //        }
    //
    //        // pour chaque produit
    //        foreach($allProductHost AS $productHost)
    //        {
    //            // on met le produit dans notre tableau de retour avec son id en clef
    //            $return[$productHost->getIdProduitHost()] = $productHost;
    //        }
    //
    //        return $return;
    //    }

    /*
     * Retrouve un objet de site via un champ et sa valeur
     * @param int $idHost id du site
     * @param string $key nom du champs
     * @param string $value valeur du champs
     * @param boolean $returnNewIfNotFind veux-t-on renvoyer un nouvel objet si aucun n'existe
     * @param boolean $noSatellite veux t'on exclure les produit satellites ?
     * @return TProduitHost
     */
    //    public static function findForHostBy($idHost, $key, $value, $returnNewIfNotFind = false, $noSatellite = false)
    //    {
    //        $calledClassName = get_class();
    //        $object			 = new $calledClassName();
    //
    //        $sql = 'SELECT ' . $object->getSelectEtoile();
    //        $sql .= ' FROM  ' . self::$_SQL_TABLE_NAME . ' t
    //				   JOIN ' . self::$_SQL_LOCALIZATION_TABLE_NAME . ' tl ON ';
    //        $sql .= $object->getJoinCondition();
    //        $sql .= ' WHERE ' . $key . ' = "' . $value . '" and t.id_host = "' . $idHost . '"';
    //
    //        // si on exclut les produit satellite
    //        if($noSatellite)
    //        {
    //            $sql .= ' AND t.pro_hos_satellite_id_parent = 0';
    //        }
    //
    //        $sql .= ' AND tl.id_code_pays = "' . DBTable::getLangDefaut() . '"';
    //
    //        $produit = DB::req($sql);
    //
    //        if($produit->num_rows == 0)
    //        {
    //
    //            return (($returnNewIfNotFind) ? new TProduitHost : null);
    //        }
    //        else
    //        {
    //            $t = new TProduitHost;
    //            $t->loadByArray($produit->fetch_assoc());
    //
    //            return $t;
    //        }
    //    }

    /*
     * Recupere tous les produits de la page d'accueil tries suivant l'identifiant de la categorie selectionnee
     * @param int $idCategorie		Identifiant de la categorie pour le tri
     * @return TProduitHost[]
     */
    //    public static function findAllOnHome($idCategorie)
    //    {
    //        return TProduitHost::findAllWithFiltre(true, DbTable::SANS, System::getCurrentHost()->getHostProduct(), System::getCurrentHost()->getHostId(), false, null, 1, $idCategorie);
    //    }

    /*
     * Retrouve un objet de site via son id
     * @param int $idHost
     * @param int $idProduit
     * @param boolean $returnNewIfNotFind
     * @return TProduitHost
     */
    //    public static function findForHostById($idHost, $idProduit, $returnNewIfNotFind = false)
    //    {
    //        return self::findForHostBy($idHost, 't.id_produit', $idProduit, $returnNewIfNotFind, true);
    //    }

    /*
     * renvoi un TProduitHost par rapport à un libellé et un id host ou null si il n'existe pas
     * @param string $idHost l'id du site
     * @param string $libelleUrl lelibellé
     * @return TProduitHost|null le produit host ou null
     */
    //    public static function findForHostByLibelleUrl($idHost, $libelleUrl)
    //    {
    //        return self::findBy(array('pro_hos_libelle_url', 'id_host'), array($libelleUrl, $idHost));
    //    }

    /*
     * Recupere la premiere association entre un produit host Fusion et une page Smart UD
     * @return TADesignerSmartProduitHost
     */
    //    public function findFirstTADesignerSmartProduitHost()
    //    {
    //        // recuperation de la page Smart UD liee a la page produit host de Fusion
    //        $taDesignerSmartProduitHost = TADesignerSmartProduitHost::findByIdProduitHost($this->getIdProduitHost(), $this->getIdHost());
    //
    //        // si on a une liaison avec le produit host on essaie de trouver une laison avec l'id du produit
    //        if($taDesignerSmartProduitHost === null)
    //        {
    //            // recuperation de la page Smart UD liee a l'id du produit
    //            $taDesignerSmartProduitHost = TADesignerSmartProduitHost::findByIdProduit($this->getIdProduit(), $this->getIdHost());
    //        }
    //
    //        return $taDesignerSmartProduitHost;
    //    }

    /*
     * retourn les produits host actif qui ne sont pas des produit satelite
     * @param strinh $cmhId  (id de site host)
     * @param strinh $idCodePays
     * @return list of TAProduitHost
     */
    //    public static function getProduitFromProduitHost($cmhId, $idCodePays = 'fr_FR')
    //    {
    //        $rep			 = array();
    //        $calledClassName = get_class();
    //        $object			 = new $calledClassName();
    //
    //        $sql = 'SELECT ' . $object->getSelectEtoile();
    //        $sql .= ' FROM  ' . self::$_SQL_TABLE_NAME . ' t
    //				  JOIN ' . self::$_SQL_LOCALIZATION_TABLE_NAME . ' tl ON ';
    //        $sql .= $object->getJoinCondition();
    //        $sql .= ' WHERE  tl.id_code_pays = "' . $idCodePays . '"
    //					AND t.id_host		= "' . $cmhId . '"
    //					AND t.pro_hos_satellite_id_parent = 0
    //					AND t.pro_hos_is_actif = 1
    //					ORDER BY tl.pro_hos_libelle';
    //
    //        $req = DB::req($sql);
    //
    //        while($r = $req->fetch_object())
    //        {
    //            $id			 = $r->id_produit_host . '-' . $r->id_host;
    //            $rep[$id]	 = $r;
    //        }
    //
    //        return $rep;
    //    }

    /*
     * Retourne une tableau de deux element:
     * 		 1ere element 'SELECT'       : String nom de tous les colones (equivalent à SELECT *)
     * 		 2eme element 'JoinCondition': String Condition de jointure
     * @return array
     */
    //    public static function getSelectEtoileAndJoinCondition()
    //    {
    //        $calledClassName = get_class();
    //        $object			 = new $calledClassName();
    //
    //        return array('SELECT' => $object->getSelectEtoile() . ' ', 'JoinCondition' => $object->getJoinCondition() . ' ');
    //    }

    // TODO Service
    /*
     * compte le nombre de produit satellite lié à ce produit
     * @return int
     */
    //    public function getCountSatellite()
    //    {
    //        // si on n'a pas encore compté le nombre de produit satellite
    //        if($this->_countSatellite === null)
    //        {
    //            // on compte le nombre de produit satellite
    //            $this->_countSatellite = self::count(array('pro_hos_satellite_id_parent', 'id_host'), array($this->getIdProduitHost(), $this->getIdHost()));
    //        }
    //
    //        return $this->_countSatellite;
    //    }
    /*
     * getteur du diaporama des images de pub du produit
     * @return \TCmsDiapo
     */
    //    public function getSliderProductAds()
    //    {
    //        // si on n'a pas encore cherché le diaporama
    //        if($this->_sliderProductAds === null)
    //        {
    //            // on le récupére
    //            $this->_sliderProductAds = TCmsDiapo::findById(array(TCmsDiapo::ID_SLIDER_PRODUCT_ADS));
    //        }
    //
    //        return $this->_sliderProductAds;
    //    }
    /*
     * getteur du diaporama des images de détails du produit
     * @return \TCmsDiapo
     */
    //    public function getSliderProductDetail()
    //    {
    //        // si on n'a pas encore cherché le diaporama
    //        if($this->_sliderProductDetail === null)
    //        {
    //            // on le récupére
    //            $this->_sliderProductDetail = TCmsDiapo::findById(array(TCmsDiapo::ID_SLIDER_PRODUCT_DETAILS));
    //        }
    //
    //        return $this->_sliderProductDetail;
    //    }
    /*	 * *********************************************************
         * Méthode pour la compatibilité tproduithost/produitmixed
         * ********************************************************** */
    /*
     * est ce que ce produit est un produit satellite
     * @return boolean
     */
    //    public function isSatellite()
    //    {
    //        // si il s'agit d'un produit satellite
    //        if($this->getProHosSatelliteIdParent() <> 0)
    //        {
    //            return true;
    //        }
    //
    //        return false;
    //    }

    /*
     * indique si le produit est un variant ou un produit original
     * @return boolean true pour un variant et false pour un original
     */
    //    public function isVariant()
    //    {
    //        // si il s'agit d'un produit variant
    //        if($this->getProHosVariant() == TProduitHost::ID_VARIANT)
    //        {
    //            return true;
    //        }
    //
    //        return false;
    //    }

    /*
     * retourne l'url absolu du picto du produit
     * @return string
     */
    //    public function getUrlPictoProduit()
    //    {
    //        return System::getPathForImg($this->getSliderProductDetail()->getCmsDiaRepUrl() . $this->idParentOrIdProduitHost() . DIRECTORY_SEPARATOR . $this->getProHosUrlPictoFile());
    //    }

    /*
     * retourne l'url relative du picto du produit
     * @return string
     */
    //    public function getUrlPictoProduitForAdmin()
    //    {
    //        return System::constructHttpServerFromHost($this->getIdHost()) . 'assets/img/_specs/' . $this->getIdHost() . DIRECTORY_SEPARATOR . $this->getSliderProductDetail()->getCmsDiaRepUrl() . $this->idParentOrIdProduitHost() . DIRECTORY_SEPARATOR . $this->getProHosUrlPictoFile();
    //    }

    /*
     * renvoi la description en fonction du pays
     * @return string
     */
    //    public function getDescription1()
    //    {
    //        // si on est sur un site principal
    //        if(System::getCurrentHost()->isMaster())
    //        {
    //            // on renvoi la description 1
    //            return Template::replaceVariableFrom($this->getProHosDescription1());
    //        }
    //
    //        // récupération du texte host du site
    //        $txtHost = TTxt::findByHostAndProductHost(System::getCurrentHost(), $this);
    //
    //        // si on a un texte
    //        if($txtHost !== null && $txtHost->getTxtValue() != '')
    //        {
    //            // on renverra le texte du ttext
    //            return Template::replaceVariableFrom($txtHost->getTxtValue());
    //        }
    //
    //        // si on est sur un site lu / ca / gb
    //        if(System::getCurrentHost()->getPaysCode() == 'lu' || System::getCurrentHost()->getPaysCode() == 'fr')
    //        {
    //            return '';
    //        }
    //        // site classique
    //        else
    //        {
    //            // on renvoi la description 1
    //            return Template::replaceVariableFrom($this->getProHosDescription1());
    //        }
    //    }

    //    public function getDescription2()
    //    {
    //        return Template::replaceVariableFrom($this->getProHosDescription2());
    //    }

    //    public function getDescription3()
    //    {
    //        return Template::replaceVariableFrom($this->getProHosDescription3());
    //    }
    /*	 * ***********************************************************
         * Autres Métodes
         * ********************************************************** */

    /*
     * Construit une ref interne
     * @return string
     */
    //    public function getRef()
    //    {
    //        return 'PH-' . $this->getIdProduitHost() . '-' . $this->getProHosRattachement() . '-' . $this->getProHosSatelliteIdParent() . '-' . $this->getIdProduit();
    //    }

    /*
     * Retourne le siteHost
     * @return siteHost
     */
    //    public function getHost()
    //    {
    //        if($this->_host == null)
    //        {
    //            $this->_host = siteHost::findById($this->idHost);
    //        }
    //
    //        return $this->_host;
    //    }

    /*
     * Retourne le produit correspondante
     * @return TProduit
     */
    //    public function getProduit()
    //    {
    //        if($this->_produit == null)
    //        {
    //            $this->_produit = TProduit::findById($this->idProduit);
    //        }
    //
    //        return $this->_produit;
    //    }

    /*
     *  renvoi un objet TAProduitFournisseur correspondant à un fournisseur de ce produit
     * @return TAProduitFournisseur
     */
    //    public function getProduitFournisseur()
    //    {
    //        // si on a pas encore récupéré le produitFournisseur
    //        if(!isset($this->_produitFournisseur))
    //        {
    //            // on recherche le produit fournisseur source
    //            $this->_produitFournisseur = TAProduitFournisseur::findByIdProduit($this->getIdProduit());
    //        }
    //
    //        return $this->_produitFournisseur;
    //    }
    /*
     * Renvoi l'url pour afficher les produits satellites
     */
    //    public function getSmartUrl()
    //    {
    //        return 'impression/accueil/smart/produit=' . $this->getLibelleUrl();
    //    }

    /*
     * renvoi un objet TProduitHost du parent si le produit est un meta child ou null dans le cas contraire
     * @return TProduitHost|null
     */
    //    public function getProduitMetaParent()
    //    {
    //        // si on a pas encore récupéré le produitMetaParent
    //        if(!isset($this->_produitMetaParent))
    //        {
    //            // on récupére l'id d'un des meta parent
    //            $idMetaparent = TAProduitMeta::metaParentIdByChildId($this->getIdProduitHost(), $this->getIdHost());
    //
    //            // le produit appartient à un meta produit
    //            if($idMetaparent <> null)
    //            {
    //                $this->_produitMetaParent = TProduitHost::findById(array($idMetaparent, $this->getIdHost()));
    //            }
    //            // le produit n'appartient pas à un meta produit
    //            else
    //            {
    //                $this->_produitMetaParent = null;
    //            }
    //        }
    //
    //        return $this->_produitMetaParent;
    //    }

    /*
     * Retourne le nombre de fois que le produit a été vue
     * @return int
     */
    //    public function getNbView()
    //    {
    //        $return = array();
    //
    //        // pour chaque site localisé
    //        foreach(siteHost::findAllSlaves($this->getIdHost(), false) AS $host)
    //        {
    //            // on récupére les info de ce site
    //            $return[$host->getHostId()] = TProduitHostMoreViewed::getCountView($this->getIdProduitHost(), $host->getHostId());
    //        }
    //
    //        return $return;
    //    }

    /*
     * renvoi le sprite du fournisseur ou meta produit
     * @return string
     */
    //    public function getSpriteFourMeta()
    //    {
    //        // meta produit
    //        if($this->isMeta())
    //        {
    //            return '<span class="sprite-icone sprite-fusion" title="Meta produit">&nbsp;</span>';
    //        }
    //        // le produit à un fournisseur
    //        elseif($this->getProduitFournisseur() !== null)
    //        {
    //            return '<span class="sprite-icone sprite-fournisseur-' . $this->getProduitFournisseur()->getIdFournisseur() . '" title="' . $this->getProduitFournisseur()->getFournisseur()->getNomFour() . '">&nbsp;</span>';
    //        }
    //        // le produit n'a pas de fournisseur
    //        else
    //        {
    //            return '<span class="sprite-icone sprite-question" title="Aucun fournisseur">&nbsp;</span>';
    //        }
    //    }

    /*
     * renvoi le nom du produit chez le fournisseur si il existe
     * @return string
     */
    //    public function getLibelleSource()
    //    {
    //        // le produit n'est pas un meta produit et il a un fournisseur
    //        if(!$this->isMeta() && $this->getProduitFournisseur() !== null)
    //        {
    //            return $this->getProduitFournisseur()->getProFouLibelleSource();
    //        }
    //        // le produit n'a pas de fournisseur ou il s'agit d'un meta produit
    //        else
    //        {
    //            return '';
    //        }
    //    }

    /*
     * renvoi l'id du produit chez le fournisseur si il existe
     * @return string
     */
    //    public function getIdSource()
    //    {
    //        // le produit n'est pas un meta produit et il a un fournisseur
    //        if(!$this->isMeta() && $this->getProduitFournisseur() !== null)
    //        {
    //            return $this->getProduitFournisseur()->getProFouIdSource();
    //        }
    //        // le produit n'a pas de fournisseur ou il s'agit d'un meta produit
    //        else
    //        {
    //            return '';
    //        }
    //    }

    /*
     * Retourne la selection par defaut d'un produit
     * @param string $idHost id du site
     * @return string
     */
    //    public function getDefautSelection($idHost)
    //    {
    //        // si notre produit est un variant
    //        if($this->isVariant())
    //        {
    //            // on récupére l'id de produit host
    //            $idProduitHostForVariant = $this->getIdProduitHost();
    //        }
    //        // produit original
    //        else
    //        {
    //            $idProduitHostForVariant = null;
    //        }
    //
    //        return $this->getProduit()->getDefautSelection($idHost, $idProduitHostForVariant);
    //    }

    /*
     * Ajoute les valeur par défaut des options de type texte à un tableau
     * @param string $idHost id du site
     * @param array $return =array() Le tableau auquel on veux ajouter nos valeurs
     * @return array
     */
    //    public function textOptionDefaultValue($idHost, $return = array())
    //    {
    //        return $this->getProduit()->textOptionDefaultValue($idHost, $return);
    //    }

    /*
     * Retourne l'id fournisseur du produit en cour
     *
     * @return int
     */
    //    public function getIdProduitSrc()
    //    {
    //        // on recherche le produit fournisseur source
    //        return $this->getProduitFournisseur()->getProFouIdSource();
    //    }

    /*
     * Retourne un tableau avec toutes les options et les valeurs dispo pour celle ci en fonction de l'objet en cour
     * @param type $idHost
     * @param type $dependance =null
     * @param bool $getOnLyActif =true veux-on uniquement les produit option value active ?
     * @param bool $standardDelivryCountry =true mettre false pour forcer le pays de livraison
     * @param bool $withFournisseurData =false mettre true pour avoir les information de fournisseur
     * @return String[]
     */
    //    public function getOptionsAndValues($idHost, $dependance = null, $getOnLyActif = true, $standardDelivryCountry = true, $withFournisseurData = false)
    //    {
    //        // si notre produit est un variant
    //        if($this->isVariant())
    //        {
    //            // on récupére l'id de produit host
    //            $idProduitHostForVariant = $this->getIdProduitHost();
    //        }
    //        // produit original
    //        else
    //        {
    //            $idProduitHostForVariant = null;
    //        }
    //
    //        // on appel la fonction dans le produit
    //        return $this->getProduit()->getOptionsAndValues($idHost, $dependance, $getOnLyActif, $standardDelivryCountry, $withFournisseurData, $idProduitHostForVariant);
    //    }

    /*
     * Retourne un tableau avec toutes les options de type text
     * @param string $idHost id du site
     * @param bool $withFournisseurData =false mettre true pour avoir les information de fournisseur
     * @param array $opt le tableau des options auxquelles ont va ajouter nos options text
     * @param bool $getOnLyActif =true veux-on uniquement les produit option value active ?
     * @return String[]
     *
     * @category Alias la requete Magique 2
     */
    //    public function getOptionsText($idHost, $withFournisseurData = false, $opt = array(), $getOnLyActif = true)
    //    {
    //        return $this->getProduit()->getOptionsText($idHost, $withFournisseurData, $opt, $getOnLyActif);
    //    }

    /*
     * getteur qui indique si le produit autorise les format personnalisé. (utilisé par l'API p24)
     * @return int
     */
    //    public function getProSpecialFormat()
    //    {
    //        return $this->getProduit()->getProSpecialFormat();
    //    }

    /*
     * getteur qui indique si le produit autorise les quantité personnalisé. (utilisé par l'API smartlabel)
     * @return int
     */
    //    public function getProSpecialQuantity()
    //    {
    //        return $this->getProduit()->getProSpecialQuantity();
    //    }

    /*
     * Retourne l'id Group du fournisseur du produit en cour
     * @return int
     */
    //    public function getIdProduitSrcGroup()
    //    {
    //        return $this->getProduit()->getIdProduitSrcGroup();
    //    }

    /*
     * Renvoi le prix de vente HT mini du produit
     * @return Prix
     */
    //    public function getMinPrice()
    //    {
    //        if($this->_minPrice === null && $this->getProHosMinPrice() !== null)
    //        {
    //            // creation d'un objet prix
    //            $this->_minPrice = new Prix($this->getProHosMinPrice(), 2, System::getTauxTva());
    //        }
    //
    //        // on renvoi l'objet prix
    //        return $this->_minPrice;
    //    }

    /*
     * renvoi le tableau des acl pour se produit
     * les donnée peuvent être incorecte pour les produits satellites
     * @return TProduitHostAcl[]
     */
    //    public function getAProduitHostAcl()
    //    {
    //        // si on a pas encore chercher les acl
    //        if($this->_aProduitHostAcl == null)
    //        {
    //            // on reinitialise en array
    //            $this->_aProduitHostAcl = array();
    //
    //            // si on a un produit satellite
    //            if($this->isSatellite())
    //            {
    //                // on récupére l'id de produit host du parent
    //                $idProduitHost = $this->getSatelliteIdParent();
    //            }
    //            // produit standard
    //            else
    //            {
    //                // on récupére l'id du produit
    //                $idProduitHost = $this->getIdProduitHost();
    //            }
    //
    //            // on récupére les acl
    //            $allAcl = TProduitHostAcl::findAllByIdProduitHost($idProduitHost);
    //
    //            // pour chaque acl
    //            foreach($allAcl AS $acl)
    //            {
    //                // on le met dans la variable avec le host en clef
    //                $this->_aProduitHostAcl[$acl->getIdHost()] = $acl;
    //            }
    //        }
    //
    //        // on renvoi notre tableau
    //        return $this->_aProduitHostAcl;
    //    }

    /*
     * renvoi true si l'acl de ce site est actif
     * @param type $idHost
     * @return boolean
     */
    //    public function getAProduitHostAclActifByIdHost($idHost)
    //    {
    //        // on récupére les acl
    //        $allAcl = $this->getAProduitHostAcl();
    //
    //        // si on a pas d'acl
    //        if(!isset($allAcl[$idHost]))
    //        {
    //            // on considére actif
    //            return true;
    //        }
    //
    //        // on renvoi la valeur de l'acl
    //        return $allAcl[$idHost]->getProHosAclActif();
    //    }

    /*
     * renvoi un objet DateHeure de la date de derniére modification
     * @return DateHeure
     */
    //    function getDateHeureLastUpdate()
    //    {
    //        // si on a pas encore cherché
    //        if($this->_dateHeureLastUpdate == null)
    //        {
    //            $this->_dateHeureLastUpdate = new DateHeure($this->getProHosLastUpdate());
    //        }
    //
    //        return $this->_dateHeureLastUpdate;
    //    }

    /*
     * renvoi un objet DateHeure de la date de derniére modification du listing des pages smart
     * @return DateHeure|null
     */
    //    function getDateHeureListLastUpdate()
    //    {
    //        // si on a pas encore cherché
    //        if($this->_dateHeureListLastUpdate == null)
    //        {
    //            // si on a pas de date
    //            if($this->getProHosListLastUpdate() == null)
    //            {
    //                // on renverra null
    //                $this->_dateHeureListLastUpdate = null;
    //            }
    //            // on a une date
    //            else
    //            {
    //                // on renvoi le dateheure
    //                $this->_dateHeureListLastUpdate = new DateHeure($this->getProHosListLastUpdate());
    //            }
    //        }
    //
    //        return $this->_dateHeureListLastUpdate;
    //    }

    /*
     * avant chaque insert on mettra à jour l'id produit host
     */
    //    protected function _preInsert()
    //    {
    //        $this->setIdProduitHost(self::lastIdProduitHost() + 1);
    //
    //        parent::_preInsert();
    //
    //        return true;
    //    }

    /*
     * avant de sauvegarder on met à jour la date de derniére modification
     */
    //    protected function _preSave()
    //    {
    //        parent::_preSave();
    //
    //        // création d'un nouvel objet date
    //        $date = new DateHeure();
    //
    //        // on met à jour la date de derniére modification
    //        $this->setProHosLastUpdate($date->format(DateHeure::DATETIMEMYSQL));
    //    }

    /*
     * aprés avoir sauvegardé on met à jour la date de derniére modification du listin des pages satellite du parent si on est sur une page satellite
     */
    //    protected function _postSave()
    //    {
    //        parent::_postSave();
    //
    //        // si on est pas sur une page satellite
    //        if(!$this->isSatellite())
    //        {
    //            // rien à faire
    //            return true;
    //        }
    //
    //        // on récupére le produit parent
    //        $produitParent = TProduitHost::findById(array($this->getProHosSatelliteIdParent(), $this->getIdHost()));
    //
    //        // si on a bien un produit parent
    //        if($produitParent->getIdProduitHost() != null)
    //        {
    //            // on met à jour la date de deniére modification du listing des satellite
    //            $produitParent->setProHosListLastUpdate($this->getProHosLastUpdate())
    //                ->save();
    //        }
    //    }

    /*
     * Avant de supprimer notre ligne en base
     */
    //    protected function _preDelete()
    //    {
    //        parent::_preDelete();
    //
    //        // on supprime toutes les éventuelles variations lié à ce produit
    //        TAVariantOptionValue::deleteByProduitHost($this);
    //
    //        // si on n'est pas sur un produit satellite
    //        if(!$this->isSatellite())
    //        {
    //            // création d'un repertoire du diaporama de pub
    //            $directorySliderProductAds = new Repertoire();
    //            $directorySliderProductAds->setCheminComplet($this->sliderProductAdsDirectoryFullPath(false));
    //
    //            // si le produit posséde un diaporama de pub
    //            if($directorySliderProductAds->exist())
    //            {
    //                // on supprime les images en webp
    //                Webp::deleteWebpDirectoryByOriginalFullPath($directorySliderProductAds->getCheminComplet());
    //
    //                // on supprime le repertoire
    //                $directorySliderProductAds->rm(true);
    //            }
    //
    //            // création d'un repertoire du diaporama de détail de produit
    //            $directorySliderProductDetail = new Repertoire();
    //            $directorySliderProductDetail->setCheminComplet($this->sliderProductDetailDirectoryFullPath(false));
    //
    //            // si le produit posséde un diaporama de détail de produit
    //            if($directorySliderProductDetail->exist())
    //            {
    //                // on supprime les images en webp
    //                Webp::deleteWebpDirectoryByOriginalFullPath($directorySliderProductDetail->getCheminComplet());
    //
    //                // on supprime le repertoire
    //                $directorySliderProductDetail->rm(true);
    //            }
    //        }
    //    }
    /*
     * Indique si le produit en cour est un produit ratteché ou pas
     * @return boolean
     */
    //    public function isRattache()
    //    {
    //        return ($this->getProHosRattachement() != 0 && $this->getProHosRattachement() != '' && $this->getProHosRattachement() != null);
    //    }

    /*
     * Indique si le produit en cour est un meta produit
     * @return boolean
     */
    //    public function isMeta()
    //    {
    //        return TAProduitMeta::isMeta($this->getIdProduitHost(), $this->getIdHost());
    //    }

    /*
     * Indique si le produit en cour est un lié à un meta produit
     * @return boolean
     */
    //    public function isMetaChild()
    //    {
    //        return TAProduitMeta::isMetaChild($this->getIdProduitHost(), $this->getIdHost());
    //    }

    // =================== Autres methodes publiques ===================

    /*
     * Recupere les images UD a afficher dans les exemples de le page des produits
     * @return array
     */
    //    public function allPicturesForAdviceExamples()
    //    {
    //        // initialisation des variables
    //        $aPictures				 = array();
    //        $aTDesignerGabaritSmart	 = array();
    //        $aPictureAltFirstWord	 = array(
    //            'lig'	 => 'Commander',
    //            'if'	 => 'Impression',
    //            'is'	 => 'Commander',
    //            'mif'	 => 'Commander'
    //        );
    //        $aPictureAltKeyword		 = array(
    //            'lig'	 => array($this->getProHosLibelleUrl(), 'papier publicitaire et imprimerie', 'modèle graphique pour devis d\'imprimeur'),
    //            'if'	 => array('papier à prix discount et format', 'devis d\'imprimeur publicitaire professionnel', $this->getProHosLibelleUrl()),
    //            'is'	 => array($this->getProHosLibelleUrl(), 'papier publicitaire et imprimerie', 'modèle graphique pour devis d\'imprimeur'),
    //            'mif'	 => array($this->getProHosLibelleUrl(), 'papier publicitaire et imprimerie', 'modèle graphique pour devis d\'imprimeur')
    //        );
    //        $pictureAlt				 = '';
    //        $pictureLabel			 = '';
    //
    //        // recuperation de la premiere page Smart UD liee a la page produit host de Fusion
    //        $taDesignerSmartProduitHost = $this->findFirstTADesignerSmartProduitHost();
    //
    //        if($taDesignerSmartProduitHost !== null)
    //        {
    //            // on recupere les 12 images UD qui correspondent a la page du produit host de fusion
    //            $aTDesignerGabaritSmart = TDesignerGabarit::findAllByIdDesignerSmartWithAllTypeRecherche($taDesignerSmartProduitHost->getIdDesignerSmart(), array(0, 12), $this->getIdProduitHost());
    //
    //            // creation du libelle de l'ensemble des images UD illustrees
    //            $pictureLabel = 'Exemple d\'imprimé publicitaire ' . $this->getProHosLibelle() . ' sur notre site d\'imprimeur en ligne.';
    //        }
    //
    //        $numPicture				 = 0;
    //        $pictureAltFirstWord	 = $aPictureAltFirstWord[$this->getIdHost()];
    //        $pictureAltKeywordList	 = $aPictureAltKeyword[$this->getIdHost()];
    //        foreach($aTDesignerGabaritSmart as $tDesignerGabaritSmart)
    //        {
    //            // Commander  + nom de la page Smart + Nom de la categorie metier si elle existe + nom de la categorie maquette specialisee si elle exite + mots suivant la position de l'image + libelle format maquette
    //            $pictureAlt	 = $pictureAltFirstWord;
    //            $pictureAlt	 .= ' ' . $this->getProHosLibelle();
    //            $pictureAlt	 .= ' ' . $tDesignerGabaritSmart->altMiniatureForAdviceExemples();
    //            $pictureAlt	 .= ' ' . $pictureAltKeywordList[$numPicture % 3];
    //            $pictureAlt	 .= ' ' . $tDesignerGabaritSmart->getDesignerFormat()->getDesForLibelle();
    //
    //            $pictureHref	 = $tDesignerGabaritSmart->resultatRechercheUrl();
    //            $pictureSrc		 = $tDesignerGabaritSmart->previewRelativeUrlForAdviceExamples(2);
    //            $pictureTitle	 = $pictureAlt;
    //
    //            // calcul de la largeur de la miniature (on arrondi a l'entier inferieur)
    //            $pictureWidth = floor($tDesignerGabaritSmart->getDesignerFormat()->getDesignerFormatMiniature()->getDesForMinLargeur() / 2);
    //
    //            $aPictures[] = array('alt' => $pictureAlt, 'href' => $pictureHref, 'label' => $pictureLabel, 'src' => $pictureSrc, 'title' => $pictureTitle, 'width' => $pictureWidth);
    //
    //            $numPicture++;
    //        }
    //
    //        return $aPictures;
    //    }

    /*
     * Met a jour le prix mini a partir de la table tCombinaison et tCombinaisonPrix (utilise dans le cron updateStatus)
     */
    //    public function updateMinPrice()
    //    {
    //        // initialisation du prix d'achat et de vente HT pour le cas ou on ne le recupéee pas
    //        $proHosMinPrice	 = null;
    //        $prixMini		 = null;
    //
    //        // si notre produit n'est pas un meta produit
    //        if(!$this->isMeta())
    //        {
    //            // recuperation de l'objet fournisseur de ce produit
    //            $fournisseur = $this->getProduitFournisseur()->getFournisseur();
    //
    //            // si on a pour ce fournisseur une méthode qui permet de récupéré le prix mini
    //            if(method_exists($fournisseur, 'minPriceByProFouIdSource'))
    //            {
    //                // on l'utilise
    //                $prixMini = $fournisseur->minPriceByProFouIdSource($this->getProduitFournisseur()->getProFouIdSource());
    //            }
    //            // le fournisseur ne posséde pas de méthode pour récup le prix mini on va chercher dans les combinaisons
    //            else
    //            {
    //                // récupération du prix mini
    //                $tabPrixMini = TCombinaison::getFirstPriceFor($this, $this->getIdHost(), array(), false);
    //                $prixMini	 = $tabPrixMini['com_pri_prix'];
    //            }
    //
    //            // recuperation du site host
    //            $host = siteHost::findById($this->getIdHost());
    //
    //            // creation d'un objet prix
    //            $prixAchatMini = new Prix($prixMini, 2, System::getTauxTva($host));
    //
    //            // calcul du prix de vente HT mini (a partir du prix d'achat HT mini si on en a un)
    //            if($prixAchatMini->getMontant() > 0)
    //            {
    //                // on applique la marge pour obtenir un prix de vente HT
    //                $prixVenteMini	 = $prixAchatMini->margePrix($host->getIdFourP24(), 0, $host, $this->getIdProduit(), null, $host->getHosPriceDecimal());
    //                $proHosMinPrice	 = $prixVenteMini->getMontant(Prix::PRIXHT);
    //            }
    //        }
    //        // meta produit
    //        else
    //        {
    //            // pour chaque sous produit qui appartient a notre meta produit
    //            foreach($this->findAllMetaChild(false, true) AS $produit)
    //            {
    //                // si ce produit a un prix et qu'il est inferieur au prixMini actuel
    //                if($produit->getMinPrice() !== null && ($prixMini === null || $prixMini > $produit->getProHosMinPrice()))
    //                {
    //                    // on met a jour le prix de vente HT mini
    //                    $prixMini = $produit->getProHosMinPrice();
    //                }
    //            }
    //
    //            // ici le prix mini est un prix de vente HT
    //            $proHosMinPrice = $prixMini;
    //        }
    //
    //        // mise a jour du prix de vente HT mini
    //        $this->setProHosMinPrice($proHosMinPrice);
    //        $this->save();
    //    }

    /*
     * Renvoi la fiche Technique de ce produit par rapport à la selection ou renvoi null si elle n'xiste pas
     * @param string $selection la selection
     * @param string $idHost id du site pour les urls
     * @return string url de la fiche Technique ou null si on n'a pas de réponse
     */
    //    public function chercheFicheTech($selection, $idHost)
    //    {
    //        return $this->getProduit()->chercheFicheTech($selection, $idHost);
    //    }

    /*
     * Renvoi le zip contenant les gabarit de ce produit par rapport à la selection ou renvoi null si elle n'xiste pas
     * @param string $selection la selection
     * @param string $idHost id du site pour les urls
     * @return string url de la fiche Technique ou null si on n'a pas de réponse
     */
    //    public function chercheGabarits($selection, $idHost)
    //    {
    //        return $this->getProduit()->chercheGabarits($selection, $idHost);
    //    }

    /*
     * Renvoi la maquette de ce produit par rapport à la selection ou renvoi null si elle n'xiste pas
     * @param string $selection la selection
     * @param string $idHost id du site pour les urls
     * @return string url de la maquette ou null si on n'a pas de réponse
     */
    //    public function chercheMaquette($selection, $idHost)
    //    {
    //        return $this->getProduit()->chercheMaquette($selection, $idHost);
    //    }

    /*
     * Retourne le nom pour l'image du sprite
     */
    //    public function spriteName()
    //    {
    //        $spriteName = '';
    //
    //        $aSpriteName = explode('.', $this->getProHosUrlPictoFile());
    //        if(isset($aSpriteName[0]))
    //        {
    //            $spriteName = $aSpriteName[0];
    //        }
    //
    //        return $spriteName;
    //    }

    /*
     * renvoi true si ce produit posséde des produits satellites
     * @return boolean
     */
    //    public function haveSatellite()
    //    {
    //        // si on a une date de modification de listing des produits satellites
    //        if($this->getProHosListLastUpdate() != null)
    //        {
    //            return true;
    //        }
    //        // pas de listing de produit satellite
    //        else
    //        {
    //            return false;
    //        }
    //    }

    /*
     * Renvoi le chemin complet d'acces au repertoire du diaporama des pub du produit
     * @param bool $allowWebp		[=true] indique si on accepte le webp. Mettre false pour la gestion des images depuis l'admin
     * @return string
     */
    //    public function sliderProductAdsDirectoryFullPath($allowWebp = true)
    //    {
    //        return $this->getSliderProductAds()->imageFullPath($this->getIdHost(), $allowWebp) . $this->idParentOrIdProduitHost() . DIRECTORY_SEPARATOR;
    //    }

    /*
     * Renvoi le chemin complet d'acces au repertoire du diaporama des details du produit
     * @param bool $allowWebp		[=true] indique si on accepte le webp. Mettre false pour la gestion des images depuis l'admin
     * @return string
     */
    //    public function sliderProductDetailDirectoryFullPath($allowWebp = true)
    //    {
    //        return $this->getSliderProductDetail()->imageFullPath($this->getIdHost(), $allowWebp) . $this->idParentOrIdProduitHost() . DIRECTORY_SEPARATOR;
    //    }

    /*
     * Renvoi un id de produit host parent pour les produits satellites et l'id de produit host pour les non satellites
     * @return int
     */
    //    public function idParentOrIdProduitHost()
    //    {
    //        // si on a un id de parent
    //        if($this->getProHosSatelliteIdParent() != 0)
    //        {
    //            // on le renvoi
    //            return $this->getProHosSatelliteIdParent();
    //        }
    //
    //        // on renvoi l'id de produit host
    //        return $this->getIdProduitHost();
    //    }
}
