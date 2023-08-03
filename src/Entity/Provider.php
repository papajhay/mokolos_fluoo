<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProviderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProviderRepository::class)]
class Provider
{

    /**
     * *************************************************************************
     * Constantes.
     * *************************************************************************
     */

    /**
     * constante pour l'id du fournisseur Inconnu.
     */
    public const ID_SUPPLIER_UNKNOWN = 0;

    /**
     * constante pour l'id du fournisseur Adesa 'ex Smartlabel).
     */
    public const ID_SUPPLIER_ADESA = 126;

    /**
     * constante pour l'id du fournisseur Etac.
     */
    public const ID_SUPPLIER_ETAC = 6;

    /**
     * constante pour l'id du fournisseur Exaprint.
     */
    public const ID_SUPPLIER_EXAPRINT = 7;

    /**
     * constante pour l'id du fournisseur création graphique Fluoo.
     */
    public const ID_SUPPLIER_FLUOO_CREATION = 85;

    /**
     * constante pour l'id du fournisseur de publicité sur Google.
     */
    public const ID_SUPPLIER_GOOGLE_ADS = 132;

    /**
     * constante pour l'id du fournisseur Realisaprint.
     */
    public const ID_SUPPLIER_IMPRESSIONENLIGNE_COM = 103;

    /**
     * constante pour l'id du fournisseur La poste.
     */
    public const ID_SUPPLIER_LA_POSTE = 109;

    /**
     * constante pour l'id du fournisseur Labelprint24.
     */
    public const ID_SUPPLIER_LABELPRINT24 = 127;

    /**
     * constante pour l'id du fournisseur de la solution de paiment CB Monext (anciennement Payline).
     */
    public const ID_SUPPLIER_MONEXT = 107;

    /**
     * constante pour l'id du fournisseur online printers.
     */
    public const ID_SUPPLIER_ONLINE_PRINTERS = 42;

    /**
     * constante pour l'id du fournisseur Pixart.
     */
    public const ID_SUPPLIER_PIXART = 13;

    /**
     * constante pour l'id du fournisseur PrintForYou.
     */
    public const ID_SUPPLIER_PRINTFORYOU = 101;

    /**
     * constante pour l'id du fournisseur Realisaprint.
     */
    public const ID_SUPPLIER_REALISAPRINT = 27;

    /**
     * constante pour l'id du fournisseur Saxoprint.
     */
    public const ID_SUPPLIER_SAXO = 15;

    /**
     * constante pour l'id du fournisseur Stampaprint.
     */
    public const ID_SUPPLIER_STAMPAPRINT = 128;

    /**
     * constante pour l'id du fournisseur Univers Design.
     */
    public const ID_SUPPLIER_UD = 131;

    /**
     * constante pour l'id du fournisseur Yesprint.
     */
    public const ID_SUPPLIER_YESPRINT = 2;

    /**
     * constante pour l'id du fournisseur du chat : ZOPIM.
     */
    public const ID_SUPPLIER_ZOPIM = 124;

    /**
     * constante pour le fournisseur actif.
     */
    // const SUPPLIER_ACTIVE = 1;

    /**
     * constante pour le fournisseur inactif.
     */
    // const SUPPLIER_INACTIVE = 0;

    /**
     * constante pour le fournisseur actif.
     */
    // const SUPPLIER_INACTIVE_WITHOUT_DELIVERY = -1;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $currenciesId = null;

    #[ORM\Column]
    // $masterFournisseurId
    private ?int $masterId = null;

    #[ORM\Column]
//    $numberTva
    private ?int $numberVAT = null;

    #[ORM\Column(length: 255, nullable: true)]
//    $dirFactures
    private ?string $dirInvoices = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accessLogin = null;

    #[ORM\Column(length: 255)]
    private ?string $comment = null;

    #[ORM\Column]
//    $tauxTva
    private ?float $VATRate = null;

    #[ORM\Column]
//    $tvaRécupérable
    private ?int $recoverableVAT = null;

    #[ORM\Column]
    private ?int $billingCompany = null;

    #[ORM\Column]
    private ?int $billingStreetAdress = null;

    #[ORM\Column]
    private ?int $billingPostCode = null;

    #[ORM\Column]
    private ?int $billingCity = null;

    #[ORM\Column]
    private ?int $billingName = null;

    #[ORM\Column]
    private ?int $billingTelephone = null;

    #[ORM\Column]
    private ?int $salutation = null;

    #[ORM\Column(length: 255)]
//    $payement
    private ?string $payment = null;

    #[ORM\Column]
    private ?int $orderSelection = null;

    #[ORM\Column(length: 255)]
//    $fouSiteAdresse
    private ?string $webSiteAddress = null;

    #[ORM\Column(length: 255)]
    private ?string $siteLogin = null;

    #[ORM\Column(length: 255)]
    private ?string $sitePass = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customerId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $partyId = null;

    #[ORM\Column(length: 255)]
    private ?string $countryCode = null;

    #[ORM\Column]
//    $actif
    private ?bool $active ;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateValidCachePrice = null;

    #[ORM\Column]
    private ?int $whiteLabelDelivery = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $idHostForSelection = null;

    #[ORM\Column]
    protected ?int $errorCode = null;

    // /*	 * *************************************************************************
    //  * Autres attributs ne correspondant pas aux champs en base
    //  * ************************************************************************ */

    // To do : constant
     /**
      * liaison entre les id de Provider et leur classe spécifique
      * @var array
      */
    // const classeDeFournisseur = array(
    // 	fournisseurPrint24::ID_FOUR_FR					 => 'fournisseurPrint24Api',
    // 	fournisseurPrint24::ID_FOUR_BE					 => 'fournisseurPrint24',
    // 	fournisseurPrint24::ID_FOUR_CH					 => 'fournisseurPrint24Api',
    // 	fournisseurPrint24::ID_FOUR_LU					 => 'fournisseurPrint24',
    // 	FournisseurLgi::ID_FOUR							 => 'FournisseurLgi',
    // 	FournisseurIgraphy::ID_FOUR						 => 'FournisseurIgraphy',
    // 	FournisseurAvisVerifies::ID_FOUR				 => 'FournisseurAvisVerifies',
    // 	FournisseurOVH::ID_FOUR							 => 'FournisseurOVH',
    // 	Provider::ID_SUPPLIER_ADESA					 => 'FournisseurAdesa',
    // 	Provider::ID_SUPPLIER_ETAC					 => '\Supplier\Etac',
    // 	Provider::ID_SUPPLIER_EXAPRINT				 => 'FournisseurExaprint',
    // 	Provider::ID_SUPPLIER_GOOGLE_ADS				 => '\Supplier\GoogleAds',
    // 	Provider::ID_SUPPLIER_IMPRESSIONENLIGNE_COM	 => '\Supplier\ImpressionsenligneCom',
    // 	Provider::ID_SUPPLIER_LA_POSTE				 => 'FournisseurLaPoste',
    // 	Provider::ID_SUPPLIER_LABELPRINT24			 => 'FournisseurLabelPrint24',
    // 	Provider::ID_SUPPLIER_MONEXT					 => '\Supplier\Monext',
    // 	Provider::ID_SUPPLIER_ONLINE_PRINTERS		 => 'FournisseurOnline',
    // 	Provider::ID_SUPPLIER_PIXART					 => 'FournisseurPixart',
    // 	Provider::ID_SUPPLIER_PRINTFORYOU			 => '\Supplier\PrintForYou',
    // 	Provider::ID_SUPPLIER_REALISAPRINT			 => 'FournisseurRealisaprint',
    // 	Provider::ID_SUPPLIER_SAXO					 => '\Supplier\Saxoprint',
    // 	Provider::ID_SUPPLIER_STAMPAPRINT			 => '\Supplier\Stampaprint',
    // 	Provider::ID_SUPPLIER_YESPRINT				 => 'FournisseurYesprint',
    // 	Provider::ID_SUPPLIER_ZOPIM					 => 'FournisseurZopim',
    // );

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHourValidCachePrice = null;

    #[ORM\OneToMany(mappedBy: 'provider', targetEntity: TAOptionValueProvider::class)]
    private Collection $taOptionValueProviders;

    #[ORM\OneToMany(mappedBy: 'provider', targetEntity: TAOptionProvider::class)]
    private Collection $tAOptionProviders;

    #[ORM\OneToMany(mappedBy: 'provider', targetEntity: TAProductProvider::class)]
    private Collection $tAProductProviders;

    #[ORM\OneToMany(mappedBy: 'provider', targetEntity: TSupplierOrder::class)]
    private Collection $tSupplierOrders;

    #[ORM\OneToMany(mappedBy: 'provider', targetEntity: TAProductOptionValueProvider::class, orphanRemoval: true)]
    private Collection $tAProductValueProviders;

    public function __construct()
    {
        $this->taOptionValueProviders = new ArrayCollection();
        $this->tAOptionProviders = new ArrayCollection();
        $this->tAProductProviders = new ArrayCollection();
        $this->tSupplierOrders = new ArrayCollection();
    }

    /**
     * @return Collection<int, TAOptionProvider>
     */
    public function getTAOptionProviders(): Collection
    {
        return $this->tAOptionProviders;
    }

    public function addTAOptionProvider(TAOptionProvider $tAOptionProvider): static
    {
        if (!$this->tAOptionProviders->contains($tAOptionProvider)) {
            $this->tAOptionProviders->add($tAOptionProvider);
            $tAOptionProvider->setProvider($this);
        }

        return $this;
    }

    public function removeTAOptionProvider(TAOptionProvider $tAOptionProvider): static
    {
        if ($this->tAOptionProviders->removeElement($tAOptionProvider)) {
            // set the owning side to null (unless already changed)
            if ($tAOptionProvider->getProvider() === $this) {
                $tAOptionProvider->setProvider(null);
            }
        }

        return $this;
    }

    /*
     * *************************************************************************
     * TO DO : RELATION
     * *************************************************************************
     */
    // /**
    //  * objet de la monnaie du Provider
    //  * @var TCurrencies
    //  */
    // private $_tcurrencies;

    // /**
    //  * TLog éventuellement associé à ce Provider
    //  * @var TLog
    //  */
    // public $log;

    // /**
    //  * sous objet du type de réglement Provider
    //  * @var TReglementFournisseurType
    //  */
    // private $_reglementFournisseurType = NULL;

    // /**
    //  * renvoi l'objet tcurrencies de ce Provider
    //  * @return TCurrencies
    //  */
    // public function getTcurrencies()
    // {
    // 	// si on a pas encore récupéré l'objet tcurrencies
    // 	if(is_null($this->_tcurrencies))
    // 	{
    // 		// on le récupére
    // 		$this->_tcurrencies = TCurrencies::findById($this->getCurrenciesId());
    // 	}

    // 	return $this->_tcurrencies;
    // }

    // /**
    //  * Retourne tout les fournisseurs qui ont besoin d'une DEB
    //  * @return Provider
    //  */
    // public static function findAllForDeb()
    // {
    // 	return self::findAllSql('select * from ' . self::$_SQL_TABLE_NAME . ' where numero_tva != ""');
    // }

    /*
     * *************************************************************************
     * END TO DO : RELATION
     * *************************************************************************
     */

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCurrenciesId(): ?int
    {
        return $this->currenciesId;
    }

    public function setCurrenciesId(int $currenciesId): static
    {
        $this->currenciesId = $currenciesId;

        return $this;
    }

    public function getMasterId(): ?int
    {
        return $this->masterId;
    }

    public function setMasterId(int $masterId): static
    {
        $this->masterId = $masterId;

        return $this;
    }

    public function getNumberVAT(): ?int
    {
        return $this->numberVAT;
    }

    public function setNumberVAT(int $numberVAT): static
    {
        $this->numberVAT = $numberVAT;

        return $this;
    }

    public function getDirInvoices(): ?string
    {
        return $this->dirInvoices;
    }

    public function setDirInvoices(?string $dirInvoices): static
    {
        $this->dirInvoices = $dirInvoices;

        return $this;
    }

    public function getAccessLogin(): ?string
    {
        return $this->accessLogin;
    }

    public function setAccessLogin(?string $accessLogin): static
    {
        $this->accessLogin = $accessLogin;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getVATRate(): ?float
    {
        return $this->VATRate;
    }

    public function setVATRate(float $VATRate): static
    {
        $this->VATRate = $VATRate;

        return $this;
    }

    public function getRecoverableVAT(): ?int
    {
        return $this->recoverableVAT;
    }

    public function setRecoverableVAT(int $recoverableVAT): static
    {
        $this->recoverableVAT = $recoverableVAT;

        return $this;
    }

    public function getBillingCompany(): ?int
    {
        return $this->billingCompany;
    }

    public function setBillingCompany(int $billingCompany): static
    {
        $this->billingCompany = $billingCompany;

        return $this;
    }

    public function getBillingStreetAdress(): ?int
    {
        return $this->billingStreetAdress;
    }

    public function setBillingStreetAdress(int $billingStreetAdress): static
    {
        $this->billingStreetAdress = $billingStreetAdress;

        return $this;
    }

    public function getBillingPostCode(): ?int
    {
        return $this->billingPostCode;
    }

    public function setBillingPostCode(int $billingPostCode): static
    {
        $this->billingPostCode = $billingPostCode;

        return $this;
    }

    public function getBillingCity(): ?int
    {
        return $this->billingCity;
    }

    public function setBillingCity(int $billingCity): static
    {
        $this->billingCity = $billingCity;

        return $this;
    }

    public function getBillingName(): ?int
    {
        return $this->billingName;
    }

    public function setBillingName(int $billingName): static
    {
        $this->billingName = $billingName;

        return $this;
    }

    public function getBillingTelephone(): ?int
    {
        return $this->billingTelephone;
    }

    public function setBillingTelephone(int $billingTelephone): static
    {
        $this->billingTelephone = $billingTelephone;

        return $this;
    }

    public function getSalutation(): ?int
    {
        return $this->salutation;
    }

    public function setSalutation(int $salutation): static
    {
        $this->salutation = $salutation;

        return $this;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(string $payment): static
    {
        $this->payment = $payment;

        return $this;
    }

    public function getOrderSelection(): ?int
    {
        return $this->orderSelection;
    }

    public function setOrderSelection(int $orderSelection): static
    {
        $this->orderSelection = $orderSelection;

        return $this;
    }

    public function getWebSiteAddress(): ?string
    {
        return $this->webSiteAdress;
    }

    public function setWebSiteAdress(string $webSiteAdress): static
    {
        $this->webSiteAdress = $webSiteAdress;

        return $this;
    }

    public function getSiteLogin(): ?string
    {
        return $this->siteLogin;
    }

    public function setSiteLogin(string $siteLogin): static
    {
        $this->siteLogin = $siteLogin;

        return $this;
    }

    public function getSitePass(): ?string
    {
        return $this->sitePass;
    }

    public function setSitePass(string $sitePass): static
    {
        $this->sitePass = $sitePass;

        return $this;
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function setCustomerId(?string $customerId): static
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getPartyId(): ?string
    {
        return $this->partyId;
    }

    public function setPartyId(?string $partyId): static
    {
        $this->partyId = $partyId;

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

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getDateValidCachePrice(): ?\DateTimeInterface
    {
        return $this->dateValidCachePrice;
    }

    public function setDateValidCachePrice(\DateTimeInterface $dateValidCachePrice): static
    {
        $this->dateValidCachePrice = $dateValidCachePrice;

        return $this;
    }

    public function getWhiteLabelDelivery(): ?int
    {
        return $this->whiteLabelDelivery;
    }

    public function setWhiteLabelDelivery(int $whiteLabelDelivery): static
    {
        $this->whiteLabelDelivery = $whiteLabelDelivery;

        return $this;
    }

    public function getIdHostForSelection(): ?string
    {
        return $this->idHostForSelection;
    }

    public function setIdHostForSelection(?string $idHostForSelection): static
    {
        $this->idHostForSelection = $idHostForSelection;

        return $this;
    }

    // /**
    //  * renvoi le log
    //  * @return TLog
    //  */
    // public function getLog()
    // {
    // 	// si on n'a pas de log
    // 	if($this->log == null)
    // 	{
    // 		// on en initialise un
    // 		$this->log = \TLog::initLog($this->getNomFour());
    // 	}

    // 	return $this->log;
    // }

    // /**
    //  * setteur du log
    //  * @param TLog $log
    //  * @return Provider notre objet
    //  */
    // public function setLog(TLog $log)
    // {
    // 	$this->log = $log;

    // 	return $this;
    // }

    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): static
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function getDateHourValidCachePrice(): ?\DateTimeInterface
    {
        return $this->dateHourValidCachePrice;
    }

    public function setDateHourValidCachePrice(\DateTimeInterface $dateHourValidCachePrice): static
    {
        $this->dateHourValidCachePrice = $dateHourValidCachePrice;

        return $this;
    }

    // /**
    //  * getteur du sous objet de type de Provider
    //  * @return TReglementFournisseurType
    //  */
    // public function getReglementFournisseurType()
    // {
    // 	// si on a pas encore récupéré l'objet
    // 	if($this->_reglementFournisseurType == NULL)
    // 	{
    // 		$this->_reglementFournisseurType = TReglementFournisseurType::findById($this->getFouPayment());
    // 	}

    // 	return $this->_reglementFournisseurType;
    // }

    /*
    * *************************************************************************
    * TO DO : REPOSITORY
    * *************************************************************************
    */
    // /**
    //  * renvoi un tableau d'objet Provider pour lesquelles on peux faire une selection
    //  * cette fonction renvoi uniquement des objet de type Provider et pas un objet fournisseurPrint24 pour p24
    //  * @return Provider
    //  */
    // public static function findAllForSelection()
    // {
    // 	return self::findAllBy(array('ordre_selection'), array(array(0, '>')), array('ordre_selection'));
    // }

    // /**
    //  * fait un findById mais renvoi l'objet spécifique du Provider comme fournisseurPrint24 pour p24 ou renvoi un objet Provider par défaut
    //  * @param int $idFour type de Provider
    //  * @return Provider|fournisseurPrint24|FournisseurLgi|FournisseurOnline et de nombreux autres
    //  */
    // public static function findByIdWithChildObject($idFour)
    // {
    // 	// si ce Provider à sa propre classe
    // 	if(isset(self::$_classeDeFournisseur[$idFour]))
    // 	{
    // 		// on renverra un objet de cette classe
    // 		$classeName = self::$_classeDeFournisseur[$idFour];
    // 	}
    // 	// pas de classe spécifique
    // 	else
    // 	{
    // 		// on renvoi un objet Provider
    // 		$classeName = __CLASS__;
    // 	}

    // 	// on renvoi notre objet
    // 	return $classeName::findById($idFour);
    // }

    // /**
    //  * renvoi tous les Provider avec un login pour l'accés Provider
    //  * @return Provider[]
    //  */
    // public static function findAllWithAccésLogin()
    // {
    // 	return self::findAllBy(array('fou_access_login'), array(array(NULL, 'IS NOT')));
    // }

    // /**
    //  * renvoi tous les Provider actif classé par ordre de séléction en premier
    //  * @return Provider
    //  */
    // public static function findAllActif()
    // {
    // 	// on commence par récupéré tous les Provider actif classé par ordre_selection
    // 	$allFournisseurs = self::findAllBy(array('fou_actif'), array(1), array('ordre_selection', 'nom_four'));

    // 	// on renvoi le tableau trier
    // 	return self::triFournisseur($allFournisseurs);
    // }

    // /**
    //  * renvoi tous les Provider actif classé par ordre de séléction en premier
    //  * @return Provider
    //  */
    // public static function findAllActifWithMail()
    // {
    // 	// on commence par récupéré tous les Provider actif classé par ordre_selection
    // 	$allFournisseurs = self::findAllBy(array('fou_actif', 'email'), array(1, array('', '<>')), array('ordre_selection', 'nom_four'));

    // 	// on renvoi le tableau trier
    // 	return self::triFournisseur($allFournisseurs);
    // }

    // /**
    //  * renvoi tous les Provider actif classé par ordre de séléction en premier
    //  * @return Provider
    //  */
    // public static function findAllActifWithChildObject()
    // {
    // 	$return = array();

    // 	// paramétre de la requête
    // 	$aTable	 = Provider::$_SQL_TABLE_NAME;
    // 	$champs	 = array('id_four');
    // 	$where	 = array(array('fou_actif', 1, 'd'));
    // 	$order	 = array('ordre_selection', 'nom_four');

    // 	// on récupére tous les id de Provider classé correctement
    // 	$allData = DB::prepareSelectAndExecuteAndFetchAll($aTable, $champs, $where, 0, $order);

    // 	// pour chaque id
    // 	foreach($allData as $key => $data)
    // 	{
    // 		// si ce Provider à sa propre classe
    // 		if(isset(self::$_classeDeFournisseur[$data['id_four']]))
    // 		{
    // 			// on renverra un objet de cette classe
    // 			$classeName = self::$_classeDeFournisseur[$data['id_four']];
    // 		}
    // 		// pas de classe spécifique
    // 		else
    // 		{
    // 			// on renvoi un objet Provider
    // 			$classeName = __CLASS__;
    // 		}

    // 		// on ajoute notre objet au tableau de retour
    // 		$return[$key] = $classeName::findById($data['id_four']);
    // 	}

    // 	// on renvoi le tableau trier
    // 	return self::triFournisseur($return);
    // }

    // /**
    //  * retourne un Provider à partir de la variable $_SERVER['REMOTE_USER'] et dans le cas d'un admin de la variable $_GET['idFournisseur']
    //  * @return Provider
    //  */
    // public static function findByRemoteUser()
    // {
    // 	// on recherche par access login
    // 	$Provider = Provider::findBy(array('fou_access_login'), array(System::getRemoteUser()));

    // 	// si on a trouvé un Provider
    // 	if($Provider != NULL)
    // 	{
    // 		// on le renvoi avec son objet
    // 		return Provider::findByIdWithChildObject($Provider->getIdFour());
    // 	}

    // 	// si on n'est pas loggé en admin
    // 	if(System::getRemoteUser() != 'admin')
    // 	{
    // 		return FALSE;
    // 	}

    // 	// on récupére en get l'id du Provider
    // 	$idFournisseur = filter_input(INPUT_GET, 'idFournisseur', FILTER_VALIDATE_INT);

    // 	// on a pas d'id de Provider en get
    // 	if($idFournisseur == NULL)
    // 	{
    // 		// on prend un Provider par défaut
    // 		$idFournisseur = 67;
    // 	}

    // 	// création du Provider
    // 	return Provider::findByIdWithChildObject($idFournisseur);
    // }

    /*
     * *************************************************************************
     * END TO DO : REPOSITORY
     * *************************************************************************
     */

    /*
     * *************************************************************************
     * TO DO : SERVICE
     * *************************************************************************
     */
    // /**
    //  * renvoi le chemin pour le ftp ou NULL si on n'a pas de login d'accés Provider
    //  * @return string|NULL
    //  */
    // public function getDirFtp($nomFournisseur = null)
    // {
    // 	if ($nomFournisseur != NULL) {
    // 		return '/data/ftpdossier/' . Tools::slugify($nomFournisseur) . '/';
    // 	}

    // 	// si on n'a pas de login d'accés Provider
    // 	if($this->getFouAccessLogin() == NULL)
    // 	{
    // 		// on renvoi NULL
    // 		return NULL;
    // 	}

    // 	// on renvoi le chemin du ftp
    // 	return '/data/ftpdossier/' . $this->getFouAccessLogin() . '/';
    // }

    // /**
    //  * indique si le Provider est actif
    //  * @return boolean true si le Provider est actif et false sinon
    //  */
    // public function isActive()
    // {
    // 	// Provider inactif
    // 	if($this->getFouActif() < Provider::SUPPLIER_ACTIVE)
    // 	{
    // 		return false;
    // 	}

    // 	return true;
    // }

    // /**
    //  * indique si le Provider affiche une date de livraison
    //  * @return boolean true si le Provider affiche une date de livraison et false sinon
    //  */
    // public function showDelivzery()
    // {
    // 	// Provider inactif
    // 	if($this->getFouActif() != Provider::SUPPLIER_INACTIVE_WITHOUT_DELIVERY)
    // 	{
    // 		return true;
    // 	}

    // 	return false;
    // }

    // /**
    //  * masque pcre pour récupérer le nom du site dans une url
    //  * @param type $url
    //  * @return string
    //  */
    // private static function _pcreSiteNameFromUrl()
    // {
    // 	return '#https?://(?:www\.|)([-a-zA-Z]+)\.#';
    // }

    // /**
    //  * cette fonction traduit la séléction pour qu'elle soit compréhensible par un humain et gére les commandes en statut urgent
    //  * @param TSupplierOrder $supplierOrder la commande
    //  * @param bool $checkSelection dois-t-on vérifier cette selection ?
    //  */
    // public function selectionRealisee($supplierOrder, $checkSelection)
    // {
    // 	// suppression paramétre inutilisé pour ce Provider
    // 	unset($checkSelection);

    // 	return nl2br($supplierOrder->getSupplierSelection()->getSelection());
    // }

    // /**
    //  * cette fonction traduit la séléction pour qu'elle soit compréhensible par un humain
    //  * Utilisé par Saxo et print24
    //  * @param array $selectionData tableau des données de la selection
    //  * @param bool $checkSelection dois-t-on vérifier cette selection ?
    //  */
    // protected function _selectionToHuman($selectionData, $checkSelection)
    // {
    // 	// suppression paramétre inutilisé pour ce Provider
    // 	unset($checkSelection);

    // 	// on ajoute le libellé du produit
    // 	$return = 'Produit : ' . $selectionData['product']->getProLibelle() . '<br>';

    // 	// pour chaque option
    // 	foreach($selectionData['selection'] as $idOption => $idOptionValue)
    // 	{
    // 		// si on est sur l'option de délai
    // 		if($idOption == 'idDelay')
    // 		{
    // 			// on passe à l'option suivante
    // 			continue;
    // 		}

    // 		// récupération de l'option
    // 		$option = \TOption::findById(array($idOption));

    // 		// si on a un soucis avec l'option
    // 		if(!$option->exist())
    // 		{
    // 			// on quitte la fonction
    // 			return '<p class="important">Option inconnu : "' . $idOption . '"</p>';
    // 		}

    // 		// on récupére la valeur d'option
    // 		$optionValue = \TOptionValue::findById($idOptionValue);

    // 		// si on a un soucis avec l'option value
    // 		if(!$optionValue->exist())
    // 		{
    // 			// on quitte la fonction
    // 			return '<p class="important">Option Value inconnu : "' . $idOptionValue . '"</p>';
    // 		}

    // 		// on ajoute notre option au retour
    // 		$return .= $option->getOptLibelle() . ' : ' . $optionValue->getOptValLibelle() . '<br>';
    // 	}

    // 	// pour chaque option de type texte
    // 	foreach($selectionData['selectionText'] as $idOption => $value)
    // 	{
    // 		// récupération de l'option
    // 		$option = \TOption::findById(array($idOption));

    // 		// si on a un soucis avec l'option
    // 		if(!$option->exist())
    // 		{
    // 			// on quitte la fonction
    // 			return '<p class="important">Option inconnu : "' . $idOption . '"</p>';
    // 		}

    // 		// on ajoute notre option au retour
    // 		$return .= $option->getOptLibelle() . ' : ' . $value . '<br>';
    // 	}

    // 	return $return;
    // }

    // /**
    //  * Transforme la selection sous forme texte "37;3-94-7514-6034-367-59-42-124-126-2122-18252-7820-8518-18251-6883-4622-3764" en un tableau avec le produit et les options
    //  * @param string $selection la selection au format texte
    //  * @param array $aIdOptionValueDelay [=array()] un éventuel tableau des id option value de delay en cas de gestion spécial (p24)
    //  * @return boolean
    //  */
    // protected function _selectionTxtToObject($selection, $aIdOptionValueDelay = array())
    // {
    // 	$return = array();

    // 	// on découpe la partie produit et selection
    // 	$selectionData = explode(';', $selection);

    // 	// si on a un soucis avec le découpage
    // 	if(count($selectionData) < 2 || count($selectionData) > 3)
    // 	{
    // 		// on quitte la fonction
    // 		return false;
    // 	}

    // 	// récupération du produit
    // 	$return['product'] = \TProduit::findById($selectionData[0]);

    // 	// si le produit n'existe pas
    // 	if(!$return['product']->exist())
    // 	{
    // 		// on quitte la fonction
    // 		return false;
    // 	}

    // 	// pour chaque élément de la selection
    // 	foreach(explode('-', $selectionData[1]) as $idOptionValue)
    // 	{
    // 		// si il s'agit d'une option value de délai p24
    // 		if(in_array($idOptionValue, $aIdOptionValueDelay))
    // 		{
    // 			// on ajoute au tableau des option
    // 			$return['selection']['idDelay'] = $idOptionValue;

    // 			// on passe à l'option suivante
    // 			continue;
    // 		}

    // 		// on récupére l'option value
    // 		$optionValue = \TOptionValue::findById(array($idOptionValue));

    // 		// si l'option value n'existe pas
    // 		if(!$optionValue->exist())
    // 		{
    // 			// on quitte la fonction
    // 			return false;
    // 		}

    // 		// on ajoute l'option value à la liste des option value séléctionné
    // 		$return['selection'][$optionValue->getIdOption()] = $idOptionValue;
    // 	}

    // 	// si on n'a pas de selection texte
    // 	if(!isset($selectionData[2]))
    // 	{
    // 		// pas de selection texte
    // 		$return['selectionText'] = array();

    // 		// on renvoi les élément de la selection
    // 		return $return;
    // 	}

    // 	// décodage de la selection texte
    // 	$return['selectionText'] = json_decode($selectionData[2], true);

    // 	// si on n'a aucune selection texte
    // 	if($return['selectionText'] == null)
    // 	{
    // 		// on le transforme en tableau vide
    // 		$return['selectionText'] = array();
    // 	}

    // 	// si on a un format personnalisé
    // 	if(isset($return['selectionText']['idOptionValueFormatOrdered']))
    // 	{
    // 		// on récupére l'option value
    // 		$optionValue = \TOptionValue::findById(array($return['selectionText']['idOptionValueFormatOrdered']));

    // 		// si l'option value n'existe pas
    // 		if(!$optionValue->exist())
    // 		{
    // 			// on quitte la fonction
    // 			return false;
    // 		}

    // 		// on ajoute l'option value à la liste des option value séléctionné
    // 		$return['selection'][$optionValue->getIdOption()] = $return['selectionText']['idOptionValueFormatOrdered'];

    // 		// on supprime cette information dont on n'a plus besoin
    // 		unset($return['selectionText']['idOptionValueFormatOrdered']);
    // 	}

    // 	// on renvoi les élément de la selection
    // 	return $return;
    // }

    // /**
    //  * copie le fichier depuis le serveur depart fab vers le ftp Provider
    //  * @param order $order la commande
    //  * @param string $commentaire Commentaire à ajouter dans l'historique
    //  * @param string|null $fileName [=null] le nom du fichier. si NULL on prendra le numéro de commande .zip
    //  * @param bool $withHistory [=true] mettre true si on veux ajouter un historique dans la commande en cas de succés
    //  * @return boolean TRUE en cas de succés et FALSE en cas d'échec
    //  */
    // public function copyProductionFileToSupplierFtp($order, $commentaire = '', $fileName = null, $withHistory = true)
    // {
    // 	// si on a pas de commentaire l'utilisateur sera robot lgi
    // 	if($commentaire == '')
    // 	{
    // 		$user = 'Robot LGI';
    // 	}
    // 	// si on a un commentaire l'utilisateur sera la personne loggé dans l'admin
    // 	else
    // 	{
    // 		$user = '';
    // 	}

    // 	// récupération du fichier departFab pour le mettre dans le ftp du Provider
    // 	if($this->downloadProductionFile($order->getOrdersId(), $this->getDirFtp(), false, $fileName))
    // 	{
    // 		// si on veux un historique
    // 		if($withHistory)
    // 		{
    // 			// ajout d'un historique
    // 			$order->addHistory('Fichier disponible pour ' . $this->getNomFour() . '<br>' . $commentaire, 0, OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI, '', '', $user);
    // 		}

    // 		// tout est bon
    // 		return TRUE;
    // 	}
    // 	// erreur lors de la récupération du fichier depart fab
    // 	else
    // 	{
    // 		// gestion des différents code d'erreur
    // 		if($this->getErrorCode() == TMessage::ERR_ZIP_CORRUPTED)
    // 		{
    // 			$commentaire .= '<br />le fichier zip est corrompu.';
    // 		}

    // 		// on balance la commande en depart fab retour
    // 		$order->updateStatus(OrdersStatus::STATUS_DEPART_FAB_RETOUR, 'Erreur dans la récupération du fichier pour ' . $this->getNomFour() . '<br>' . $commentaire, OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI, '', '', $user);

    // 		return FALSE;
    // 	}
    // }

    // /**
    //  * verifie si le fichier de la commande est disponible sur le ftp du Provider
    //  * @param int $idOrder id de la commande
    //  * @return boolean TRUE si le fichier est disponible et FALSE si il ne l'est pas
    //  */
    // public function fichierDisponible($idOrder)
    // {
    // 	return is_file($this->getDirFtp() . $idOrder . '.zip');
    // }

    // /**
    //  * le Provider a-t-il accés aux commandes disponibles .
    //  * @return boolean
    //  */
    // public function accesCommandesDisponibles()
    // {
    // 	return TRUE;
    // }
    // /*	 * *************************************************************************
    //  * Vieille méthode à viré/déplacer/revoir
    //  * ************************************************************************ */

    // /**
    //  * fonction permettant de récupérer un fichier departfab pour le copier en local.
    //  * On va récupérer le fichier zip correspondant à la commande dans le dossier depart fab de stationserveur
    //  * @param int $idOrder id de la commande.
    //  * @param string $repertoireDestination [='/tmp/'] repertoire de destination en local. par défaut /tmp
    //  * @param bool $checkOnly [=FALSE] doit-on faire uniquement une verification du fichier sans téléchargement ?
    //  * @param string|NULL $fileName [=NULL] le nom du fichier. si NULL on prendra le numéro de commande .zip
    //  * @return bool|Fichier retourne FALSE en cas d'échec, TRUE en cas de réussite si $checkOnly est àà TRUE et le fichier dans les autres cas.
    //  */
    // public function downloadProductionFile($idOrder, $repertoireDestination = '/tmp/', $checkOnly = FALSE, $fileName = NULL)
    // {
    // 	// verification de l'existence d'un TLog et création du TLog si nécessaire
    // 	if(!isset($this->log))
    // 	{
    // 		$this->setLog(TLog::initLog('Récupération d\'un fichier sur Stationserveur'));
    // 	}

    // 	// si on n'a pas de nom de fichier
    // 	if($idOrder == '')
    // 	{
    // 		$this->getLog()->Erreur('pas de nom de fichier à récupérer');
    // 		return false;
    // 	}

    // 	// si on n'a pas de nom de fichier
    // 	if($fileName == NULL)
    // 	{
    // 		// on prend un zip du numéro de commande
    // 		$fileName = $idOrder . '.zip';
    // 	}

    // 	// initialisation de notre fichier zip
    // 	$productionFile = new Fichier(FALSE, $this->getLog());
    // 	$productionFile->setCheminComplet($repertoireDestination . $fileName);

    // 	$this->getLog()->addLogContent('Récupération du fichier ' . $productionFile->getNomFichier() . ' sur Stationserveur pour le copier dans ' . $productionFile->getCheminComplet() . '.');

    // 	// connexion au ftp
    // 	$ftp = Stationserveur::ftpInProductionFileForOrder($idOrder, $this->getLog());

    // 	// si on a un probléme de login sur le ftp
    // 	if($ftp == FALSE)
    // 	{
    // 		// on quitte la fonction
    // 		return FALSE;
    // 	}

    // 	//si le fichier n'existe pas car dans ce cas on n'a pass de mail d'erreur
    // 	if($ftp->fileExists($productionFile->getNomFichier()) != 1)
    // 	{
    // 		// on ajoute une info dans le log
    // 		$this->getLog()->addLogContent('Le fichier ' . $productionFile->getNomFichier() . ' n\'est pas sur Stationserveur.');

    // 		// on quitte la fonction
    // 		return FALSE;
    // 	}

    // 	// calcul de la durée depuis la derniére modification du fichier
    // 	$dureeModification = Duree::dureeEntreDate($ftp->dateModification($productionFile->getNomFichier()), System::today());

    // 	// si la date de derniére modification est inférieur à 3 minutes
    // 	if($dureeModification->getMinutesTotal() < 3)
    // 	{
    // 		// on ne téléchargeras pas le fichier tous de suite
    // 		$this->getLog()->addLogContent('Fichier modifié depuis moins de 3 minutes. Il sera considéré comme non présent.');
    // 		return FALSE;
    // 	}

    // 	// si on doit uniquement verifier que le fichier existe
    // 	if($checkOnly)
    // 	{
    // 		// tout est bon
    // 		return TRUE;
    // 	}

    // 	// si on n'arrive pas à récupérer le fichiers sur le serveur pour l'envoyer dans notre repertoire de destination
    // 	if(!$ftp->get($productionFile->getCheminComplet(), $productionFile->getNomFichier()))
    // 	{
    // 		// on quitte la fonction
    // 		return FALSE;
    // 	}

    // 	// si il s'agit d'un fichier zip
    // 	if($productionFile->getExtention() == 'zip')
    // 	{
    // 		// initialisation d'un objet zip
    // 		$zip = new ZipArchive();

    // 		// si le fichier n'est pas un zip ATTENTION METTRE un === car il peux s'agir de TRUE qui serait égal à notre code d'erreur
    // 		if($zip->open($productionFile->getCheminComplet()) === ZipArchive::ER_NOZIP)
    // 		{
    // 			// on met le type d'erreur
    // 			$this->setErrorCode(TMessage::ERR_ZIP_CORRUPTED);

    // 			$this->getLog()->addLogContent('Le fichier ' . $productionFile->getNomFichier() . ' est un zip corrompu.');
    // 			return FALSE;
    // 		}
    // 		// le fichier est bien un zip
    // 		else
    // 		{
    // 			return $productionFile;
    // 		}
    // 	}
    // 	// c'est pas un fichier zip donc c'est bon
    // 	else
    // 	{
    // 		return $productionFile;
    // 	}
    // }

    // /**
    //  * renvoi le chemin du cookie curl de ce Provider
    //  * @param string $suffix [=''] suffixe eventuel à ajouter à notre cookie
    //  * @return string
    //  */
    // public function curlCookiePath($suffix = '')
    // {
    // 	// si on a pas de nom de Provider
    // 	if($this->getNomFour() === NULL)
    // 	{
    // 		return '/tmp/curl_cookie/Provider' . $suffix . '.txt';
    // 	}
    // 	// si on a un nom de Provider
    // 	else
    // 	{
    // 		return '/tmp/curl_cookie/' . str_replace(' ', '_', ToolsHTML::retireAccents(strtolower($this->getNomFour()))) . $suffix . '.txt';
    // 	}
    // }

    // /**
    //  * renvoi une selection ordonné sous forme de tableau à partir de donnée provenant du post du menu déroulant
    //  * @param array $data le tableau provenant du post
    //  * @return array le tableau de la selection dans le bon ordre
    //  */
    // public static function generateOrdenedSelectionByPostData($data)
    // {
    // 	$ordonneSelection	 = array();
    // 	$return				 = array();

    // 	// on va traité chaque ligne d'option
    // 	foreach($data AS $idOption => $idOptionValue)
    // 	{
    // 		// si il s'agit des délais ou des quantités
    // 		if($idOption == 'idDelay' || $idOption == 'idQuantity')
    // 		{
    // 			// on récupére l'option value
    // 			$optionValue = TOptionValue::findById(array($idOptionValue));

    // 			// on met l'id option value dans notre tableau à ordonner.
    // 			// on utilise 2 niveau pour le cas ou plusieur option aurais le même ordre
    // 			$ordonneSelection[$optionValue->getOption()->getOptOrdre()][$idOption] = $idOptionValue;

    // 			// on passe à la donnée suivante
    // 			continue;
    // 		}

    // 		// si il ne s'agit pas d'une option
    // 		if(!is_numeric($idOption))
    // 		{
    // 			// on passe à la donnée suivante
    // 			continue;
    // 		}

    // 		// on récupére l'option correspondante
    // 		$option = TOption::findById($idOption);

    // 		// si on a bien récupéré l'option et qu'elle a un ordre
    // 		if($option->getOptOrdre() !== NULL)
    // 		{
    // 			// on met l'id option value dans notre tableau à ordonner.
    // 			// on utilise 2 niveau pour le cas ou plusieur option aurais le même ordre
    // 			$ordonneSelection[$option->getOptOrdre()][$idOption] = $idOptionValue;
    // 		}
    // 	}

    // 	// on parcour notre tableau à ordonner
    // 	foreach($ordonneSelection AS $ordonneSelection1)
    // 	{
    // 		// pour chaque option de cette ordre
    // 		foreach($ordonneSelection1 AS $idOption => $idOptionValue)
    // 		{
    // 			// on ajoute à notre tableau de retour
    // 			$return[$idOption] = $idOptionValue;
    // 		}
    // 	}

    // 	return $return;
    // }

    // /**
    //  * tri les Provider avec les Provider de selection en premier. le tableau doit être trié par ordre_selection
    //  * @param Provider[] $allFournisseurs notre tableau à trier
    //  * @return Provider[] notre tableau trié
    //  */
    // private static function triFournisseur($allFournisseurs)
    // {
    // 	// pour chaque Provider
    // 	foreach($allFournisseurs AS $key => $Provider)
    // 	{
    // 		// si on a atteint les Provider avec selection
    // 		if($Provider->getOrdreSelection() > 0)
    // 		{
    // 			// on quitte la boucle
    // 			break;
    // 		}

    // 		// on supprime le Provider du tableau
    // 		unset($allFournisseurs[$key]);

    // 		// on ajoute notre Provider à la fin
    // 		$allFournisseurs[$key] = $Provider;
    // 	}

    // 	return $allFournisseurs;
    // }

    // /**
    //  * calcul les délai
    //  * @param array $data le tableau contenant le nombre de jour de fabrication
    //  * @param int $idProduit id du produit
    //  * @return array le tableau avec les dates ajotués
    //  */
    // public function calculDelaiFromFabrication($data, $idProduit)
    // {
    // 	// si on a pas de délai
    // 	if(!isset($data['idQuantitySelected']))
    // 	{
    // 		$data['idQuantitySelected'] = 0;
    // 	}

    // 	// si on a pas de quantité séléctionné
    // 	if(!isset($data['tabDelay']))
    // 	{
    // 		// on prend un tableau vide
    // 		$data['tabDelay'] = array();
    // 	}

    // 	// si on a pas de prix
    // 	if(!isset($data['tabPrice']))
    // 	{
    // 		// on prend un tableau vide
    // 		$data['tabPrice'] = array();
    // 	}

    // 	// tableau des quantité
    // 	$tabQuantity = array();

    // 	// pour chaque quantité
    // 	foreach($data['tabPrice'] as $idQuantity => $quantityData)
    // 	{
    // 		// on ajoute au tableau des quantité une valeur numérique pour la trié
    // 		$tabQuantity[$idQuantity] = intval($quantityData['quantite']);
    // 	}

    // 	// on tri le tableau des quantité
    // 	asort($tabQuantity);

    // 	// si on a pas de délai
    // 	if(!isset($data['selectionText']))
    // 	{
    // 		$data['selectionText'] = '';
    // 	}

    // 	// si on a pas d'url de gabarit
    // 	if(!isset($data['templateUrl']))
    // 	{
    // 		$data['templateUrl'] = '';
    // 	}

    // 	// récupération des valeurs de variables
    // 	$data['aVariableValuesJson'] = json_encode(\Supplier\Message::getAVariableValues());

    // 	// si il manque trop de donnée (cas de site Provider inaccessible
    // 	if(!isset($data['selection']))
    // 	{
    // 		// on mettra une chaune vide
    // 		$data['dataAjax'] = '';
    // 	}
    // 	// tout est bon
    // 	else
    // 	{
    // 		// création des dataAjax
    // 		$data['dataAjax'] = '"idProduit=' . $idProduit . '&dependance=' . $data['dependance'] . '&selection=' . $data['selection'] . '&idDelaySelected=' . $data['idDelaySelected'] . '&tabDelay=' . urlencode(json_encode($data['tabDelay'])) . '&idQuantitySelected=' . $data['idQuantitySelected'] . '&tabQuantity=' . urlencode(json_encode($tabQuantity)) . '&selectionText=' . urlencode($data['selectionText']) . '&aVariableValuesJson=' . urlencode($data['aVariableValuesJson']) . '"';
    // 	}

    // 	return $data;
    // }

    // /**
    //  * calcul une date de livraison en fonction du délai de fabrication
    //  * @param int $fabricationDelay nombre de jour de fabrication
    //  * @param bool $secureDelay [=FALSE] mettre TRUE pour avoir un délai sécurisé et FALSE pour un délai estimé
    //  * @param bool|NULL $isInFusion [=NULL] indique si on est sur fusion ou pas pour choisir la bonne méthode de calcul. mettre NULL va prendre la valeur pour le site actuel
    //  * @return DateHeure
    //  */
    // public static function shippingDateFromFabrication($fabricationDelay, $secureDelay = FALSE, $isInFusion = NULL)
    // {
    // 	// si on a un délai de -1 (spécifique au PDF UD)
    // 	if($fabricationDelay == -1)
    // 	{
    // 		// on renvoi la date du jour
    // 		return new DateHeure();
    // 	}

    // 	// si on ne sais pas si on est sur fusion ou past
    // 	if($isInFusion === NULL)
    // 	{
    // 		// on prend celui du site
    // 		$isInFusion = System::getCurrentHost()->isInFusion();
    // 	}

    // 	// si on n'est pas sur fusion
    // 	if(!$isInFusion)
    // 	{
    // 		// on ajoute 3 jour à notre délai
    // 		return DateHeure::jPlusX($fabricationDelay + System::getCurrentHost()->getHosDelay());
    // 	}

    // 	// si on a moins d'un jour de fabrication
    // 	if($fabricationDelay < 1)
    // 	{
    // 		//  on considére qu'il faut au minimum un jour de fabrication
    // 		$fabricationDelay = 1;
    // 	}

    // 	// on commence par créé un délai de 10 heure pour changer de date de livraison aprés 14h
    // 	$delayForNextDay = new Duree(10, Duree::TYPE_HEURE);

    // 	// on créé la date de depart à laquel on ajoute ce délai
    // 	$startDate = new DateHeure();
    // 	$startDate->addTime($delayForNextDay);

    // 	// si notre date de départ n'est pas un jour ouvré
    // 	if(!$startDate->estOuvre())
    // 	{
    // 		// on rajoute 2 journéee pour commencé au pochain jour ouvré et rajouter une journée
    // 		$startDate = DateHeure::jPlusX(2, $startDate);
    // 	}
    // 	// jour normal
    // 	else
    // 	{
    // 		// on rajoute une journée
    // 		$startDate = DateHeure::jPlusX(1, $startDate);
    // 	}

    // 	// si on veux un délai sécurisé
    // 	if($secureDelay)
    // 	{
    // 		// on ajoute 5 jour ouvré
    // 		$fabricationDelay += 5;
    // 	}

    // 	// on renvoi la date final
    // 	return DateHeure::jPlusX($fabricationDelay, $startDate);
    // }

    // /**
    //  * renvoi la selection Provider et le Provider pour la sauvegarde de la selection
    //  * @param string$selection				la selection
    //  * @param \TProduitHost $tProduitHost	le produit host
    //  * @param int $idDelay					id du délai
    //  * @param string $selectionTxt			[=''] la selection text encodé en json ou "" si on n'en a pas
    //  * @return array
    //  */
    // public function buildSupplierSelectionForSave($selection, \TProduitHost $tProduitHost, $idDelay, $selectionTxt = '')
    // {
    // 	$return = array();

    // 	// on supprime toutes les variables comme ca on evite les erreurs dans netbeans
    // 	unset($selection);
    // 	unset($tProduitHost);
    // 	unset($idDelay);
    // 	unset($selectionTxt);

    // 	// par défaut pas de selection Provider
    // 	$return['selection_fournisseur'] = NULL;

    // 	// on ajoute le Provider dans la variable de retour
    // 	$return['Provider'] = $this;

    // 	return $return;
    // }

    // /**
    //  * indique si le Provider dispose d'un systéme de Passage d'une commande Provider automatiquement chez ce Provider
    //  * @return bool true si le Provider dispose du systéme et false sinon
    //  */
    // public function haveSupplierOrderAutoLaunch()
    // {
    // 	return method_exists($this, 'supplierOrderAutoLaunch');
    // }

    // /**
    //  * indique si le Provider dispose d'un systéme de Gestion des mails
    //  * @return bool true si le Provider dispose du systéme et false sinon
    //  */
    // public function haveManageMail()
    // {
    // 	return method_exists($this, 'manageMail');
    // }

    // /**
    //  * finalisation de la commande chez le Provider
    //  * @param TLockProcess $lockProcess le lockprocess pour mettre à jour l'étape
    //  * @param order $order la commande
    //  * @param array $supplierPrice un tableau avec le prix TTC et le prix HT payé au Provider
    //  * @param type $supplierOrderId id de la commande chez le Provider
    //  * @param DateHeure|NULL $deliveryDate [=''] la date de livraison prévu ou '' si on ne veux pas changer
    //  * @param TSupplierOrder|null $supplierOrder [=null] la commande Provider si on l'a sinon null pour la chercher ou la créé
    //  * @param int|null $jobId [=null] id du job dans la commande Provider ou null si non applicable
    //  * @return bool renvoi true
    //  */
    // public function finalisationCommandeSite(TLockProcess $lockProcess, order $order, $supplierPrice, $supplierOrderId, $deliveryDate = '', $supplierOrder = null, $jobId = null)
    // {
    // 	// si on a une date de type DateHeure
    // 	if(is_a($deliveryDate, 'DateHeure'))
    // 	{
    // 		// on récupére la date au format FR
    // 		$deliveryDate = $deliveryDate->format(DateHeure::DATEMYSQL);
    // 	}

    // 	$lockProcess->getLog()->addLogContent('Création du reglement Provider');
    // 	$order->addRegFour($this->getIdFour(), $this->getFouPayment(), $supplierPrice['ttc'], $supplierOrderId);

    // 	$lockProcess->updateStage('Création de la commande Provider.');
    // 	TSupplierOrder::updatePreOrderOrCreateNew($order->getOrdersId(), $deliveryDate, $this->getIdFour(), $supplierPrice['ht'], $supplierOrderId, TSupplierOrderStatus::ID_STATUS_FILE_WAITING, $jobId, '', $supplierOrder);

    // 	// maj du statut de la commande
    // 	$lockProcess->updateStage('Mise à jour du statut de la commande et envoi des mails');
    // 	$commentaire = '--- Informations ---
    // 			Prix d achat : ' . $supplierPrice['ht'] . ' &euro;
    // 			Provider : ' . $this->getNomFour() . '
    // 			Livraison : ' . $deliveryDate;
    // 	$order->updateStatus(OrdersStatus::STATUS_FABRICATION, $commentaire, OrdersStatusHistory::TYPE_ENVOI_MAIL_MAIL_DU_STATUT, '', '', 'robot lgi', str_replace("'", " ", $supplierOrderId));

    // 	return true;
    // }

    // /**
    //  * découpe le nom complet en nom et prénom qui passe chez p24
    //  * @param string $nomComplet le nom complet
    //  * @return array un tableau constitué du nom et du prenom
    //  */
    // public function decoupeNomPrenomPourCommandeAuto($nomComplet)
    // {
    // 	// on commence par retirer les accents et tous les trucs qui pourrait nous embéter
    // 	$nomCompletSansAccent = ToolsHTML::retireAccents($nomComplet);

    // 	$resultat = array();
    // 	// on verifie si on a un nom du type "Mediapost 37" qui pose des problème
    // 	if(preg_match('#^(\S+)\s+\d+$#', $nomCompletSansAccent, $resultat))
    // 	{
    // 		// dans ce cas on renvoi un . dans le prénom et tout le reste dans le nom
    // 		return array(
    // 			'prenom' => '.',
    // 			'nom'	 => trim($resultat[1]));
    // 	}
    // 	// cas classique avec nom et prénom
    // 	elseif(preg_match('#^(\S+)\s+(.+)$#', $nomCompletSansAccent, $resultat))
    // 	{
    // 		// dans ce cas on renvoi un . dans le prénom et tout le reste dans le nom
    // 		return array(
    // 			'prenom' => trim($resultat[1]),
    // 			'nom'	 => trim($resultat[2]));
    // 	}
    // 	// autre cas
    // 	else
    // 	{
    // 		// on renvoi tout dans le nom
    // 		return array(
    // 			'prenom' => '.',
    // 			'nom'	 => trim($nomComplet));
    // 	}
    // }

    // /**
    //  * Renvoi la liste des noms de fichiers normalement présent pour une selection
    //  * @param SelectionFournisseur $selection
    //  * @param int $idOrder id de la commande
    //  * @return string[]|FALSE une liste de nom de fichier ou FALSE si on n'a pas d'information
    //  */
    // public function filesListFromSelection(SelectionFournisseur $selection, $idOrder)
    // {
    // 	// selection non utilisé pour ce Provider
    // 	unset($selection);

    // 	// par défaut fichier pdf
    // 	return array($idOrder . '.pdf');
    // }

    // /**
    //  * Indique si on peux vérifier cette selection
    //  * @return bool TRUE si la section peux être vérifier et FALSE sinon
    //  */
    // public function selectionNeedCheck()
    // {
    // 	// par défaut la selection n'est pas vérifiable
    // 	return FALSE;
    // }

    // /**
    //  * Renvoi les information bancaire sur une transaction
    //  * @param string $idTransaction id de la transaction
    //  * @param \DateHeure $dateTransaction date de la transaction
    //  * @return array|null|false null si ce Provider ne dispose pas d'information
    //  */
    // public function bankTransaction($idTransaction, \DateHeure $dateTransaction)
    // {
    // 	// pas besoin des paramétres pour les Provider générique
    // 	unset($idTransaction);
    // 	unset($dateTransaction);

    // 	// on renvoi null pour indiquer qu'on n'a pas d'information pour ce fournisseru
    // 	return null;
    // }

    // /**
    //  * télécharge un gabarit
    //  * @param type $downloadCode le code pour télécharger
    //  * @return false par défaut pas de gestion du telechargement d'un gabarit
    //  */
    // public function templateDownload($downloadCode)
    // {
    // 	// paramétre non utilisé
    // 	unset($downloadCode);

    // 	// on quitte la focntion
    // 	return false;
    // }

    // /**
    //  * Recherche un id Provider par rapport a son nom
    //  * @param string $supplierInformation
    //  * @return int|false on renvoi l'id ou false si on ne trouve pas
    //  */
    // public static function supplierIdBySupplierInformation($supplierInformation)
    // {
    // 	$return	 = array();
    // 	$matches = array();

    // 	// si on a une url
    // 	if(preg_match(Provider::_pcreSiteNameFromUrl(), $supplierInformation, $matches))
    // 	{
    // 		// on ajoute le nom du Provider aux information pour le trouver aprés.
    // 		$supplierInformation .= "\n" . $matches[1];
    // 	}

    // 	// recherche de tous les fournisseurs on tri par nom inversé pour chercher "print 24 belgique" avant "print 24"
    // 	$allSupplier = Provider::findAll(array('nom_four DESC'));

    // 	// on sépare chaque ligne
    // 	$allSupplierInformationLineRaw = explode("\n", $supplierInformation);

    // 	// pour chaque ligne dans l'information
    // 	foreach($allSupplierInformationLineRaw as $idSupplierInformationLineRaw => $supplierInformationLineRaw)
    // 	{
    // 		// on met en minuscule
    // 		$supplierInformationLine = trim(mb_strtolower($supplierInformationLineRaw));

    // 		// si on n'a pas d'information
    // 		if($supplierInformationLine == '')
    // 		{
    // 			// on passe au suivant
    // 			continue;
    // 		}

    // 		// on recherche parmis les Provider supplémentaire
    // 		foreach(self::$_ADDITIONAL_SUPPLIER as $supplierName => $supplierId)
    // 		{
    // 			// si on n'a pas trouvé
    // 			if(mb_strtolower($supplierName) != $supplierInformationLine)
    // 			{
    // 				// on passe à la suivante
    // 				continue;
    // 			}

    // 			// on ajoute l'id du fournissur
    // 			$return['idSupplier'] = $supplierId;

    // 			// on supprime cette ligne de l'information
    // 			unset($allSupplierInformationLineRaw[$idSupplierInformationLineRaw]);

    // 			// on rajoute les informations
    // 			$return['supplierInformation'] = implode("\n", $allSupplierInformationLineRaw);

    // 			// on renvoi le résultat
    // 			return $return;
    // 		}

    // 		// pour chaque Provider
    // 		foreach($allSupplier as $supplier)
    // 		{
    // 			// si on n'a pas trouvé
    // 			if(mb_strtolower($supplier->getNomFour()) != $supplierInformationLine)
    // 			{
    // 				// on passe à la suivante
    // 				continue;
    // 			}

    // 			// on ajoute l'id du fournissur
    // 			$return['idSupplier'] = $supplier->getIdFour();

    // 			// on supprime cette ligne de l'information
    // 			unset($allSupplierInformationLineRaw[$idSupplierInformationLineRaw]);

    // 			// on rajoute les informations
    // 			$return['supplierInformation'] = implode("\n", $allSupplierInformationLineRaw);

    // 			// on renvoi le résultat
    // 			return $return;
    // 		}
    // 	}

    // 	// on recherche parmis les Provider supplémentaire
    // 	foreach(self::$_ADDITIONAL_SUPPLIER as $supplierName => $supplierId)
    // 	{
    // 		// si on n'a pas trouvé
    // 		if(!preg_match('#^' . preg_quote($supplierName) . '#i', $supplierInformation))
    // 		{

    // 			// on passe à la suivante
    // 			continue;
    // 		}

    // 		// on ajoute l'id du fournissur
    // 		$return['idSupplier'] = $supplierId;

    // 		// on renvoi les informations en supprimant le Provider
    // 		$return['supplierInformation'] = trim(str_replace(mb_strtolower($supplierName), '', mb_strtolower($supplierInformation)));

    // 		// on renvoi le résultat
    // 		return $return;
    // 	}

    // 	// on recherche parmis les Provider
    // 	foreach($allSupplier as $supplier)
    // 	{
    // 		// si on n'a pas trouvé
    // 		if(!preg_match('#^' . preg_quote($supplier->getNomFour()) . '#i', $supplierInformation))
    // 		{

    // 			// on passe à la suivante
    // 			continue;
    // 		}

    // 		// on ajoute l'id du fournissur
    // 		$return['idSupplier'] = $supplier->getIdFour();

    // 		// on renvoi les informations en supprimant le Provider
    // 		$return['supplierInformation'] = trim(str_replace(mb_strtolower($supplier->getNomFour()), '', mb_strtolower($supplierInformation)));

    // 		// on renvoi le résultat
    // 		return $return;
    // 	}

    // 	// on n'a rien trouvé
    // 	return false;
    // }

    // /**
    //  * Récupére la commande Provider ou la créé si elle n'existe pas puis la met à jour
    //  * @param string $supplierOrderId id de la commande chez le Provider
    //  * @param int $idSupplierOrderStatus [=TSupplierOrderStatus::ID_STATUS_PRODUCTION] statut de la commande Provider
    //  * @param AchattodbEmail|null $achattodbEmail [=null] si on fournit un mail il sera mis à retraité en cas de probléme
    //  * @param int|int[] $idOrder [=null] id de la commande ou des commandes si on a un array (pour une eventuelle création)
    //  * @param date $deliveryDate [=null] date de livraison de la commande Provider ou null pour ne pas la mettre à jour
    //  * @param float $ordSupOrdPriceWithoutTax [=null] prix d'achat HT de la commande Provider (pour une eventuelle création)
    //  * @param string|null $jobId [=null] id du job ou null si non applicable
    //  * @param string $additionnalComment [=''] commentaire additionnel à ajouter dans l'historique de la commande
    //  * @param int|null $idOrderStatus [=null] id du statut pour notre commande si on souhaite le changer. mettre null pour avoir un commentaire
    //  * @param array|null $aDeliveryInformation [=null] tableau des informations colis pour le passage de commande en livraison ou null si non applicable
    //  * @return TSupplierOrder|false la commande Provider ou FALSE si rien ne correspond
    //  */
    // public function updateOrderSupplier($supplierOrderId, $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRODUCTION, $achattodbEmail = null, $idOrder = null, $deliveryDate = null, $ordSupOrdPriceWithoutTax = null, $jobId = null, $additionnalComment = '', $idOrderStatus = null, $aDeliveryInformation = null)
    // {
    // 	// on recherche la commande Provider
    // 	$supplierOrder = $this->orderSupplier($supplierOrderId, $achattodbEmail, $idOrder, $idSupplierOrderStatus, $deliveryDate, $ordSupOrdPriceWithoutTax, $jobId);

    // 	// si on n'a pas trouvé la commande Provider
    // 	if($supplierOrder == false)
    // 	{
    // 		// on quitte la fonction
    // 		return false;
    // 	}

    // 	// relie la commande Provider à toutes les commandes nécessaire
    // 	$supplierOrder->linkWithAllOrder($idOrder);

    // 	// récupération du statut de la commande
    // 	$supplierOrderStatus = TSupplierOrderStatus::findById(array($idSupplierOrderStatus));

    // 	// si on a mis à jour le statut
    // 	if($supplierOrder->updateStatusIfAfterCurrent($idSupplierOrderStatus))
    // 	{
    // 		// on ajoutera un commentaire dans l'historique pour la commande
    // 		$comment = 'La commande ' . $this->getNomFour() . ' "' . $supplierOrderId . '" est en "' . $supplierOrderStatus->getSupOrdStaName() . '".<br>';
    // 	}
    // 	// pas de maj du statut
    // 	else
    // 	{
    // 		// pas de commentaire
    // 		$comment = '';
    // 	}

    // 	// si on a un commentaire additionnel
    // 	if($additionnalComment != '')
    // 	{
    // 		// on l'ajoute
    // 		$comment .= $additionnalComment . '<br>';
    // 	}

    // 	// on vériei si il y a bien des commande lié à notre commande Provider
    // 	if(!$this->checkOrderLinkedToSupplierOrderWithJob($supplierOrder, $jobId, $achattodbEmail))
    // 	{
    // 		// on quitte la fonction
    // 		return false;
    // 	}

    // 	// pour chaque commande correspondant à notre job
    // 	foreach($supplierOrder->getAOrderSupplierOrder($jobId) as $orderSupplierOrder)
    // 	{
    // 		// si on a changé la date de livraison
    // 		if($deliveryDate != null && $deliveryDate->format(DateHeure::DATEMYSQL) != $orderSupplierOrder->getOrdSupOrdDeliveryDate())
    // 		{
    // 			// on modifie la date de livraison
    // 			$orderSupplierOrder->setOrdSupOrdDeliveryDate($deliveryDate->format(DateHeure::DATEMYSQL))
    // 					->save();

    // 			// on ajoute un commentaire
    // 			$comment .= 'Nouvelle date de livraison : ' . $deliveryDate->format(DateHeure::DATEFR);
    // 		}

    // 		// si on passe la commande en expédié
    // 		if($idOrderStatus == OrdersStatus::STATUS_EXPEDITION)
    // 		{
    // 			// on passe la commande en livraison
    // 			$orderSupplierOrder->getOrder()->setAsLivraison($this->getNomFour(), $aDeliveryInformation, $comment, $this->getNomFour());
    // 		}
    // 		elseif($idOrderStatus == OrdersStatus::STATUS_LIVRE)
    // 		{
    // 			// on passe la commande en livre
    // 			$orderSupplierOrder->getOrder()->setAsDelivered($comment, $this->getNomFour());
    // 		}
    // 		// si on doit changer la commande de statut
    // 		elseif($idOrderStatus != null)
    // 		{
    // 			// mise à jour de la commande
    // 			$orderSupplierOrder->getOrder()->updateStatus($idOrderStatus, $comment, OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI, '', '', $this->getNomFour());
    // 		}
    // 		// si on a un commentaire à ajouter
    // 		elseif(trim($comment) != '')
    // 		{
    // 			// on ajoute un historique à la commande
    // 			$orderSupplierOrder->getOrder()->addHistory($comment, 0, 0, '', '', $this->getNomFour());
    // 		}
    // 	}

    // 	// passage du mail en traité
    // 	$achattodbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED)
    // 			->save();

    // 	// tout est bon
    // 	return true;
    // }

    // /**
    //  * Vérifie qu'il y a bien des commandes lié à une commande Provider et un job
    //  * @param TSupplierOrder $supplierOrder la commande Provider
    //  * @param string $jobId [=null] id du job
    //  * @param AchattodbEmail|null $achattodbEmail [=null] si on fournit un mail il sera mis à retraité
    //  * @return boolean true si tout va bien et false si rien ne correspond
    //  */
    // public function checkOrderLinkedToSupplierOrderWithJob(TSupplierOrder $supplierOrder, $jobId = null, $achattodbEmail = null)
    // {
    // 	// rien ne correspond à notre job
    // 	if(count($supplierOrder->getAOrderSupplierOrder($jobId)) == 0)
    // 	{
    // 		// on envoi un log
    // 		$log = TLog::initLog('commande Provider avec job introuvable');
    // 		$log->Erreur($this->getNomFour());
    // 		$log->Erreur(var_export($supplierOrder, true));
    // 		$log->Erreur(var_export($jobId, true));

    // 		// si on a un mail
    // 		if(is_a($achattodbEmail, 'AchattodbEmail'))
    // 		{
    // 			// on va retraité le mail
    // 			$achattodbEmail->needReprocess();
    // 		}

    // 		// on quitte la fonction
    // 		return false;
    // 	}

    // 	// tout est bon
    // 	return true;
    // }

    // /**
    //  * Renvoi un tableau contenant un ou plusieurs numéro de commande contenu dans la chaine.
    //  * @param string $orderIdRaw la chaine par exemple : 233298/99/00/01
    //  * @return string[] tableau contenant un ou plus numéro de commande
    //  */
    // public static function multipleOrderNumberStringToArray($orderIdRaw)
    // {
    // 	$return		 = array();
    // 	$baseOrderId = null;

    // 	// on découpe suivant les / quand on a plusieurs numéro de commande. on trim les résultat
    // 	$aOrderId = array_map('trim', explode('/', $orderIdRaw));

    // 	// si on a qu'un seul numéro de commande ou aucun
    // 	if(count($aOrderId) <= 1)
    // 	{
    // 		// on renvoi le tableau tel quel
    // 		return $aOrderId;
    // 	}

    // 	// pour chaque numéro de commande
    // 	foreach($aOrderId as $orderId)
    // 	{
    // 		// si on est sur le 1er numéro de commande ou si le numéro est complet
    // 		if($baseOrderId == null || mb_strlen($orderId) >= mb_strlen($baseOrderId))
    // 		{
    // 			// on met ) jour le numéro qui servira de base au suivant
    // 			$baseOrderId = $orderId;

    // 			// on ajoute notre numéro au tableau de retour
    // 			$return[] = $orderId;

    // 			// on passe au suivant
    // 			continue;
    // 		}

    // 		// si on a changé de centaine ou dixaine, etc
    // 		if($orderId < mb_substr($baseOrderId, -1 * mb_strlen($orderId)))
    // 		{
    // 			// on calcul le nouveau numéro
    // 			$newOrderId = (mb_substr($baseOrderId, 0, mb_strlen($baseOrderId) - mb_strlen($orderId)) + 1) . $orderId;

    // 			// on met ) jour le numéro qui servira de base au suivant
    // 			$baseOrderId = $newOrderId;

    // 			// on ajoute notre numéro au tableau de retour
    // 			$return[] = $newOrderId;

    // 			// on passe au suivant
    // 			continue;
    // 		}

    // 		// on calcul le nouveau numéro
    // 		$newOrderId = mb_substr($baseOrderId, 0, mb_strlen($baseOrderId) - mb_strlen($orderId)) . $orderId;

    // 		// on met ) jour le numéro qui servira de base au suivant
    // 		$baseOrderId = $newOrderId;

    // 		// on ajoute notre numéro au tableau de retour
    // 		$return[] = $newOrderId;
    // 	}

    // 	return $return;
    // }

    /**
     * liste des fournisseurs supplémentaire.
     * @var int[]
     */
//    protected $_ADDITIONAL_SUPPLIER = ['ALIAS PIXART' => Provider::ID_SUPPLIER_PIXART,
//        'ALIAS REDUC' => fournisseurPrint24::ID_FOUR_FR,
//        'AUDRY' => 67,
//        'AVL' => 45,
//        'colissimo' => Provider::ID_SUPPLIER_LA_POSTE,
//        'cusin' => 5,
//        'crea' => Provider::ID_SUPPLIER_FLUOO_CREATION,
//        'digit' => 17,
//        'exa' => Provider::ID_SUPPLIER_EXAPRINT,
//        'exeprint' => Provider::ID_SUPPLIER_EXAPRINT,
//        'envelcolor.fr' => 71,
//        'igraphy' => 73,
//        'impressionsenligne' => 103,
//        'indexit' => 20,
//        'le-sac-publicitaire' => 75,
//        'max' => 11,
//        'magenta' => 45,
//        'mursdimages' => Provider::ID_SUPPLIER_PIXART,
//        'onelineprinters' => Provider::ID_SUPPLIER_ONLINE_PRINTERS,
//        'onlinp' => Provider::ID_SUPPLIER_ONLINE_PRINTERS,
//        'oneline' => Provider::ID_SUPPLIER_ONLINE_PRINTERS,
//        'online' => Provider::ID_SUPPLIER_ONLINE_PRINTERS,
//        'onlin' => Provider::ID_SUPPLIER_ONLINE_PRINTERS,
//        'p24' => fournisseurPrint24::ID_FOUR_FR,
//        'p24 be' => fournisseurPrint24::ID_FOUR_BE,
//        'pixartprinting' => Provider::ID_SUPPLIER_PIXART,
//        'pix' => Provider::ID_SUPPLIER_PIXART,
//        'MULTISIGNE' => Provider::ID_SUPPLIER_PIXART,
//        'Print 24 be' => fournisseurPrint24::ID_FOUR_BE,
//        'Print 24 lu' => fournisseurPrint24::ID_FOUR_LU,
//        'Print 24be' => fournisseurPrint24::ID_FOUR_BE,
//        'print24' => fournisseurPrint24::ID_FOUR_FR,
//        'print 27' => fournisseurPrint24::ID_FOUR_FR,
//        'print 30' => fournisseurPrint24::ID_FOUR_FR,
//        'print 31' => fournisseurPrint24::ID_FOUR_FR,
//        'pc' => 1,
//        'printoclok' => 29,
//        'printforyou' => Provider::ID_SUPPLIER_PRINTFORYOU,
//        'Printconcept (ex Aud' => 67,
//        'carnet -liasse.com' => Provider::ID_SUPPLIER_REALISAPRINT,
//        'realisa' => Provider::ID_SUPPLIER_REALISAPRINT,
//        'smartlabel' => Provider::ID_SUPPLIER_ADESA,
//        'saxo' => Provider::ID_SUPPLIER_SAXO,
//        'ud' => Provider::ID_SUPPLIER_UD,
//        'yp' => Provider::ID_SUPPLIER_YESPRINT];
}
