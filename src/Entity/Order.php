<?php
declare(strict_types=1);
namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
//$_SQL_TABLE_NAME = 'orders';
class Order
{
    /**
     * Date utilisé pour une commande qui ne sera pas envoyé au site gérant les avis
     */
    //const AVIS_NEVER_SENT_DATE = "2000-01-01 00:00:00";
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $customersId = null;

    #[ORM\Column(length: 255)]
    private ?string $customersName = null;

    #[ORM\Column(length: 255)]
    private ?string $customersCompany = null;

    #[ORM\Column(length: 255)]
    private ?string $customersStreetAddress = null;

    #[ORM\Column(length: 255)]
    private ?string $customersSuburd = null;

    #[ORM\Column(length: 255)]
    private ?string $customersCity = null;

    #[ORM\Column(length: 255)]
    private ?string $customersPostCode = null;

    #[ORM\Column(length: 255)]
    private ?string $customersCountry = null;

    #[ORM\Column(length: 255)]
    private ?string $customersTelephone = null;

    #[ORM\Column(length: 255)]
    private ?string $customersTelportable = null;

    #[ORM\Column(length: 255)]
    private ?string $customersEmailAddress = null;

    #[ORM\Column(length: 255,nullable: true)]
    private ?string $customersEmailAddress2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customersEmailAddress3 = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryName = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryCompany = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryTelephone = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryStreetAddress = null;

    #[ORM\Column(length: 255)]
    private ?string $deliverySuburd = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryCity = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryPostCode = null;

    #[ORM\Column(length: 255)]
    private ?string $deliveryCodePorte = null;

    #[ORM\Column(length: 255)]
    // id du pays de livraison dans notre base
    private ?string $deliveryCountryCode = null;

    #[ORM\Column(length: 255)]
    private ?string $billingName = null;

    #[ORM\Column(length: 255)]
    private ?string $billingCompany = null;

    #[ORM\Column(length: 255)]
    private ?string $billingStreetAddress = null;

    #[ORM\Column(length: 255)]
    private ?string $billingSuburd = null;

    #[ORM\Column(length: 255)]
    private ?string $billingCity = null;

    #[ORM\Column(length: 255)]
    private ?string $billingPostCode = null;

    #[ORM\Column]
    //id du pays de facturation dans notre base
    private ?int $billingCountryCode = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentMethod = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastModified = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $datePurchased = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $ordersStatus = null;

    #[ORM\Column]
    //id de la monnaie
    private ?int $idCurrencies = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 14, scale: 6)]
    private ?string $currencyValue = null;

    #[ORM\Column]
    private ?int $typeFile = null;

    #[ORM\Column(length: 255)]
    private ?string $idCase = null;

    #[ORM\Column]
    private ?int $idDossierDepartFab = null;

    #[ORM\Column]
    private ?int $idDesignerUser = null;

    #[ORM\Column(length: 255)]
    private ?string $intitule = null;

    #[ORM\Column]
    private ?int $statusRegl = null;

    #[ORM\Column]
    private ?int $statusFact = null;

    #[ORM\Column]
    private ?int $statusHt = null;

    #[ORM\Column(length: 255)]
    private ?string $graphiste = null;

    #[ORM\Column(nullable: true)]
    //id de la catégorie racine du produit lgi si disponible ou null sinon
    private ?int $idCategoryRootLgi = null;

    #[ORM\Column(nullable: true)]
    //id du produit lgi si disponible ou null sinon
    private ?int $idProductLgi = null;

    #[ORM\Column(nullable: true)]
    //id de lu produit host fusion si disponible ou null sinon
    private ?int $idProductHost = null;

    #[ORM\Column(nullable: true)]
    //selection fusion ou null si non applicable
    private ?int $orderSelection = null;

    #[ORM\Column]
    //variable spécial utilsé lors des ventes à pertes
    private ?float $loss = null;

    #[ORM\Column]
    //id de la commande d'origine si il s'agit d'une option sur commande, 0 sinon
    private ?int $orderOptionOrderId = null;

    #[ORM\Column]
    // numéro de la commande pour le client (1 pour la premiere commande)
    private ?int $countForCustomer = null;

    #[ORM\Column(nullable: true)]
    //date à laquelle la commande a était envoyé au site qui gére les avis
    private ?\DateTimeImmutable $ordAvisSendingDate = null;

    #[ORM\Column]
    //id du coeffeicient de reduction des marges utilisé dans cette commande
    private ?int $ordIdCoefficientMargeUsed = null;

    #[ORM\Column]
    //objet DateHeure de la date de la commande
    private ?\DateTimeImmutable $datetimePurchased = null;

    #[ORM\Column]
    //objet DateHeure de la date de modification de la commande
    private ?\DateTimeImmutable $datetimeLastModified = null;

    #[ORM\Column]
    //le nombre POSITIF de crédit utilisé par une commande
    private ?float $creditUsed = null;

    #[ORM\Column(type: Types::TEXT)]
    //texte de colis utilisé pour les mails de livraisons
    private ?string $textShipping = null;

    #[ORM\Column]
    //tableau des sous objet DateHeure des dates de livrraison lié à notre objet
    private ?\DateTimeImmutable $aDeliveryDate = null;

    #[ORM\OneToMany(mappedBy: 'tOrder', targetEntity: TAOrderSupplierOrder::class)]
    private Collection $taOrderSupplierOrders;

    public function __construct()
    {
        $this->taOrderSupplierOrders = new ArrayCollection();
    }


    /**
     * objet selectionFournisseur lié à cette commande
     * @var SelectionFournisseur
     */
    //private $_selectionFournisseur;

    /**
     * tableau d'objet de propositions de fournisseur
     * @var TOrdersPropositionFournisseur
     */
    //private $_propositionsFournisseurs = null;

    /**
     * prix de la commande (aprés utilisation des éventuels crédits)
     * @var Prix
     */
    //private $_prix = null;

    /**
     * prix originale de la commande (avantutilisation des éventuels crédits)
     * @var Prix
     */
    //private $_prixOrigine = null;

    /**
     * prix d'achat de la commande
     * @var Prix
     */
    //private $_prixAchat = null;

    /**
     * tous les objets order products de notre commande
     * @var OrdersProducts[]
     */
    //private $_products = null;

    /**
     * tableau des statuts des propositions fournisseur
     * @var string[]
     */
    //private $_statutPropositionsForFournisseur = array();

    /**
     * bat correspondant au dernier bat de la commande ou FALSE si la commande n'a pas de bat
     * @var bat|null|FALSE
     */
    //private $_lastBat = null;

    /**
     * tableau des réglement clients ou null si on a pas encore chercher
     * @var ReglementsClients[]|null
     */
    //private $_reglementsClient = null;

    /**
     * tableau des réglement fournisseur ou null si on a pas encore chercher
     * @var ReglementsFournisseurs[]|null
     */
    //private $_reglementsFournisseur = null;

    /**
     * tableau des livraison ou null si on a pas encore chercher
     * @var TOrdersLivraison[]|null
     */
    //private $_livraisons = null;

    /**
     * objet de type payment lié à cette commande
     * @var TPayment
     */
    //private $_payment = null;

    /**
     * objet TMargeCoefficient utilisé dans la commande
     * @var TMargeCoefficient
     */
    //private $_margeCoefficientUsed = null;

    /**
     * objet OrdersStatus utilisé dans la commande
     * @var OrdersStatus
     */
    //private $_statut = null;

    /**
     * tableau des commandes d'option lié à notre commande
     * @var order[]
     */
    //private $_aOptionOrders = null;

    /**
     * Sous-objet TEmergencyWork associe
     * @var TEmergencyWork
     */
    //private $_emergencyWork = FALSE;

    /**
     * tableau des options complémentaire lié à notre commande
     * @var TAOrdersOptions[]
     */
    //private $_aOptions = null;

    /**
     * tableau des factures chorus lié à notre commande
     * @var TChorusInvoice[]
     */
    //private $_aChorusInvoice = null;

    /**
     * tableau des liaisons entre commandes et commandes fournisseurs
     * @var TAOrderSupplierOrder
     */
    //private $_aOrderSupplierOrder = null;

    /**
     * sous objet du pays de livraison
     * @var Countries
     */
    //private $_deliveryCountry = null;

    /**
     * sous objet du pays de facturation
     * @var Countries
     */
    //private $_billingCountry = null;

    /**
     * @return Collection<int, TAOrderSupplierOrder>
     */
    public function getTaOrderSupplierOrders(): Collection
    {
        return $this->taOrderSupplierOrders;
    }

    public function addTaOrderSupplierOrder(TAOrderSupplierOrder $taOrderSupplierOrder): self
    {
        if (!$this->taOrderSupplierOrders->contains($taOrderSupplierOrder)) {
            $this->taOrderSupplierOrders->add($taOrderSupplierOrder);
            $taOrderSupplierOrder->setTOrder($this);
        }

        return $this;
    }

    public function removeTaOrderSupplierOrder(TAOrderSupplierOrder $taOrderSupplierOrder): self
    {
        if ($this->taOrderSupplierOrders->removeElement($taOrderSupplierOrder)) {
            // set the owning side to null (unless already changed)
            if ($taOrderSupplierOrder->getTOrder() === $this) {
                $taOrderSupplierOrder->setTOrder(null);
            }
        }

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomersId(): ?int
    {
        return $this->customersId;
    }

    public function setCustomersId(int $customersId): self
    {
        $this->customersId = $customersId;

        return $this;
    }

    public function getCustomersName(): ?string
    {
        return $this->customersName;
    }

    public function setCustomersName(string $customersName): self
    {
        $this->customersName = $customersName;

        return $this;
    }

    public function getCustomersCompany(): ?string
    {
        return $this->customersCompany;
    }

    public function setCustomersCompany(string $customersCompany): self
    {
        $this->customersCompany = $customersCompany;

        return $this;
    }

    public function getCustomersStreetAddress(): ?string
    {
        return $this->customersStreetAddress;
    }

    public function setCustomersStreetAddress(string $customersStreetAddress): self
    {
        $this->customersStreetAddress = $customersStreetAddress;

        return $this;
    }

    public function getCustomersSuburd(): ?string
    {
        return $this->customersSuburd;
    }

    public function setCustomersSuburd(string $customersSuburd): self
    {
        $this->customersSuburd = $customersSuburd;

        return $this;
    }

    public function getCustomersCity(): ?string
    {
        return $this->customersCity;
    }

    public function setCustomersCity(string $customersCity): self
    {
        $this->customersCity = $customersCity;

        return $this;
    }

    public function getCustomersPostCode(): ?string
    {
        return $this->customersPostCode;
    }

    public function setCustomersPostCode(string $customersPostCode): self
    {
        $this->customersPostCode = $customersPostCode;

        return $this;
    }

    public function getCustomersCountry(): ?string
    {
        return $this->customersCountry;
    }

    public function setCustomersCountry(string $customersCountry): self
    {
        $this->customersCountry = $customersCountry;

        return $this;
    }

    public function getCustomersTelephone(): ?string
    {
        return $this->customersTelephone;
    }

    public function setCustomersTelephone(string $customersTelephone): self
    {
        $this->customersTelephone = $customersTelephone;

        return $this;
    }

    public function getCustomersTelportable(): ?string
    {
        return $this->customersTelportable;
    }

    public function setCustomersTelportable(string $customersTelportable): self
    {
        $this->customersTelportable = $customersTelportable;

        return $this;
    }

    public function getCustomersEmailAddress(): ?string
    {
        return $this->customersEmailAddress;
    }

    public function setCustomersEmailAddress(string $customersEmailAddress): self
    {
        $this->customersEmailAddress = $customersEmailAddress;

        return $this;
    }

    public function getCustomersEmailAddress2(): ?string
    {
        return $this->customersEmailAddress2;
    }

    public function setCustomersEmailAddress2(string $customersEmailAddress2): self
    {
        $this->customersEmailAddress2 = $customersEmailAddress2;

        return $this;
    }

    public function getCustomersEmailAddress3(): ?string
    {
        return $this->customersEmailAddress3;
    }

    public function setCustomersEmailAddress3(?string $customersEmailAddress3): self
    {
        $this->customersEmailAddress3 = $customersEmailAddress3;

        return $this;
    }

    public function getDeliveryName(): ?string
    {
        return $this->deliveryName;
    }

    public function setDeliveryName(string $deliveryName): self
    {
        $this->deliveryName = $deliveryName;

        return $this;
    }

    public function getDeliveryCompany(): ?string
    {
        return $this->deliveryCompany;
    }

    public function setDeliveryCompany(string $deliveryCompany): self
    {
        $this->deliveryCompany = $deliveryCompany;

        return $this;
    }

    public function getDeliveryTelephone(): ?string
    {
        return $this->deliveryTelephone;
    }

    public function setDeliveryTelephone(string $deliveryTelephone): self
    {
        $this->deliveryTelephone = $deliveryTelephone;

        return $this;
    }

    public function getDeliveryStreetAddress(): ?string
    {
        return $this->deliveryStreetAddress;
    }

    public function setDeliveryStreetAddress(string $deliveryStreetAddress): self
    {
        $this->deliveryStreetAddress = $deliveryStreetAddress;

        return $this;
    }

    public function getDeliverySuburd(): ?string
    {
        return $this->deliverySuburd;
    }

    public function setDeliverySuburd(string $deliverySuburd): self
    {
        $this->deliverySuburd = $deliverySuburd;

        return $this;
    }

    public function getDeliveryCity(): ?string
    {
        return $this->deliveryCity;
    }

    public function setDeliveryCity(string $deliveryCity): self
    {
        $this->deliveryCity = $deliveryCity;

        return $this;
    }

    public function getDeliveryPostCode(): ?string
    {
        return $this->deliveryPostCode;
    }

    public function setDeliveryPostCode(string $deliveryPostCode): self
    {
        $this->deliveryPostCode = $deliveryPostCode;

        return $this;
    }

    public function getDeliveryCodePorte(): ?string
    {
        return $this->deliveryCodePorte;
    }

    public function setDeliveryCodePorte(string $deliveryCodePorte): self
    {
        $this->deliveryCodePorte = $deliveryCodePorte;

        return $this;
    }

    public function getDeliveryCountryCode(): ?string
    {
        return $this->deliveryCountryCode;
    }

    public function setDeliveryCountryCode(string $deliveryCountryCode): self
    {
        $this->deliveryCountryCode = $deliveryCountryCode;

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

    public function getBillingCompany(): ?string
    {
        return $this->billingCompany;
    }

    public function setBillingCompany(string $billingCompany): self
    {
        $this->billingCompany = $billingCompany;

        return $this;
    }

    public function getBillingStreetAddress(): ?string
    {
        return $this->billingStreetAddress;
    }

    public function setBillingStreetAddress(string $billingStreetAddress): self
    {
        $this->billingStreetAddress = $billingStreetAddress;

        return $this;
    }

    public function getBillingSuburd(): ?string
    {
        return $this->billingSuburd;
    }

    public function setBillingSuburd(string $billingSuburd): self
    {
        $this->billingSuburd = $billingSuburd;

        return $this;
    }

    public function getBillingCity(): ?string
    {
        return $this->billingCity;
    }

    public function setBillingCity(string $billingCity): self
    {
        $this->billingCity = $billingCity;

        return $this;
    }

    public function getBillingPostCode(): ?string
    {
        return $this->billingPostCode;
    }

    public function setBillingPostCode(string $billingPostCode): self
    {
        $this->billingPostCode = $billingPostCode;

        return $this;
    }

    public function getBillingCountryCode(): ?string
    {
        return $this->billingCountryCode;
    }

    public function setBillingCountryCode(int $billingCountryCode): self
    {
        $this->billingCountryCode = $billingCountryCode;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): self
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getLastModified(): ?\DateTimeImmutable
    {
        return $this->lastModified;
    }

    public function setLastModified(\DateTimeImmutable $lastModified): self
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    public function getDatePurchased(): ?\DateTimeImmutable
    {
        return $this->datePurchased;
    }

    public function setDatePurchased(\DateTimeImmutable $datePurchased): self
    {
        $this->datePurchased = $datePurchased;

        return $this;
    }

    public function getOrdersStatus(): ?int
    {
        return $this->ordersStatus;
    }

    public function setOrdersStatus(int $ordersStatus): self
    {
        $this->ordersStatus = $ordersStatus;

        return $this;
    }

    public function getIdCurrencies(): ?int
    {
        return $this->idCurrencies;
    }

    public function setIdCurrencies(int $idCurrencies): self
    {
        $this->idCurrencies = $idCurrencies;

        return $this;
    }

    public function getCurrencyValue(): ?string
    {
        return $this->currencyValue;
    }

    public function setCurrencyValue(string $currencyValue): self
    {
        $this->currencyValue = $currencyValue;

        return $this;
    }

    public function getTypeFile(): ?int
    {
        return $this->typeFile;
    }

    public function setTypeFile(int $typeFichier): self
    {
        $this->typeFile = $typeFichier;

        return $this;
    }

    public function getIdCase(): ?string
    {
        return $this->idCase;
    }

    public function setIdCase(string $idDossier): self
    {
        $this->idCase = $idDossier;

        return $this;
    }

    public function getIdDossierDepartFab(): ?int
    {
        return $this->idDossierDepartFab;
    }

    public function setIdDossierDepartFab(int $idDossierDepartFab): self
    {
        $this->idDossierDepartFab = $idDossierDepartFab;

        return $this;
    }

    public function getIdDesignerUser(): ?int
    {
        return $this->idDesignerUser;
    }

    public function setIdDesignerUser(int $idDesignerUser): self
    {
        $this->idDesignerUser = $idDesignerUser;

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getStatusRegl(): ?int
    {
        return $this->statusRegl;
    }

    public function setStatusRegl(int $statusRegl): self
    {
        $this->statusRegl = $statusRegl;

        return $this;
    }

    public function getStatusFact(): ?int
    {
        return $this->statusFact;
    }

    public function setStatusFact(int $statusFact): self
    {
        $this->statusFact = $statusFact;

        return $this;
    }

    public function getStatusHt(): ?int
    {
        return $this->statusHt;
    }

    public function setStatusHt(int $statusHt): self
    {
        $this->statusHt = $statusHt;

        return $this;
    }

    public function getGraphiste(): ?string
    {
        return $this->graphiste;
    }

    public function setGraphiste(string $graphiste): self
    {
        $this->graphiste = $graphiste;

        return $this;
    }

    public function getIdCategoryRootLgi(): ?int
    {
        return $this->idCategoryRootLgi;
    }

    public function setIdCategoryRootLgi(?int $idCategorieRootLgi): self
    {
        $this->idCategoryRootLgi = $idCategorieRootLgi;

        return $this;
    }

    public function getIdProductLgi(): ?int
    {
        return $this->idProductLgi;
    }

    public function setIdProductLgi(?int $idProductLgi): self
    {
        $this->idProductLgi = $idProductLgi;

        return $this;
    }

    public function getIdProductHost(): ?int
    {
        return $this->idProductHost;
    }

    public function setIdProductHost(?int $idProductHost): self
    {
        $this->idProductHost = $idProductHost;

        return $this;
    }

    public function getOrderSelection(): ?int
    {
        return $this->orderSelection;
    }

    public function setOrderSelection(?int $orderSelection): self
    {
        $this->orderSelection = $orderSelection;

        return $this;
    }

    public function getLoss(): ?float
    {
        return $this->loss;
    }

    public function setLoss(float $loss): self
    {
        $this->loss= $loss;

        return $this;
    }

    public function getOrderOptionOrderId(): ?int
    {
        return $this->orderOptionOrderId;
    }

    public function setOrderOptionOrderId(int $orderOptionOrderId): self
    {
        $this->orderOptionOrderId = $orderOptionOrderId;

        return $this;
    }

    public function getCountForCustomer(): ?int
    {
        return $this->countForCustomer;
    }

    public function setCountForCustomer(int $countForCustomer): self
    {
        $this->countForCustomer = $countForCustomer;

        return $this;
    }

    public function getOrdAvisSendingDate(): ?\DateTimeImmutable
    {
        return $this->ordAvisSendingDate;
    }

    public function setOrdAvisSendingDate(?\DateTimeImmutable $ordAvisSendingDate): self
    {
        $this->ordAvisSendingDate = $ordAvisSendingDate;

        return $this;
    }

    public function getOrdIdCoefficientMargeUsed(): ?int
    {
        return $this->ordIdCoefficientMargeUsed;
    }

    public function setOrdIdCoefficientMargeUsed(int $ordIdCoefficientMargeUsed): self
    {
        $this->ordIdCoefficientMargeUsed = $ordIdCoefficientMargeUsed;

        return $this;
    }

    public function getDatetimePurchased(): ?\DateTimeImmutable
    {
        return $this->datetimePurchased;
    }

    public function setDatetimePurchased(\DateTimeImmutable $datetimePurchased): self
    {
        $this->datetimePurchased = $datetimePurchased;

        return $this;
    }

    public function getDatetimeLastModified(): ?\DateTimeImmutable
    {
        return $this->datetimeLastModified;
    }

    public function setDatetimeLastModified(\DateTimeImmutable $datetimeLastModified): self
    {
        $this->datetimeLastModified = $datetimeLastModified;

        return $this;
    }

    public function getCreditUsed(): ?float
    {
        return $this->creditUsed;
    }

    public function setCreditUsed(float $creditUsed): self
    {
        $this->creditUsed = $creditUsed;

        return $this;
    }

    public function getTextShipping(): ?string
    {
        return $this->textShipping;
    }

    public function setTextShipping(string $textShipping): self
    {
        $this->textShipping = $textShipping;

        return $this;
    }

    public function getADeliveryDate(): ?\DateTimeImmutable
    {
        return $this->aDeliveryDate;
    }

    public function setADeliveryDate(\DateTimeImmutable $aDeliveryDate): self
    {
        $this->aDeliveryDate = $aDeliveryDate;

        return $this;
    }
    //TODO METHOD
    /**
     * renvoi un objet dateHeure de la date d'achat
     * @return dateHeure
     */
//    public function getDateHeurePurchased()
//    {
//        if($this->_dateHeurePurchased == null)
//        {
//            $this->_dateHeurePurchased = new DateHeure($this->getDatePurchased());
//        }
//
//        return $this->_dateHeurePurchased;
//    }


    /**
     * renvoi un objet dateHeure de la date de derniere modification
     * @return dateHeure
     */
//    public function getDateHeureLastModified()
//    {
//        if($this->_dateHeureLastModified == null)
//        {
//            $this->_dateHeureLastModified = new DateHeure($this->getLastModified());
//        }
//
//        return $this->_dateHeureLastModified;
//    }


    /**
     * Recupere le Devis associe a la commande
     * @return null|Devis objet Devis ou null s'il n'existe pas
     */
//    public function getDevis()
//    {
//        return Devis::findByOrderId($this->getOrdersId());
//    }


    /**
     * Retourne les produits de la commande
     * @return OrdersProducts
     */
//    public function getProducts()
//    {
//        if($this->_products == null)
//        {
//            $this->_products = OrdersProducts::findAllForOrder($this->getOrdersId());
//        }
//
//        return $this->_products;
//    }


    /**
     * Retourne le premier produit de la commande
     * @return OrdersProducts
     */
//    public function getOrdersProducts()
//    {
//        $ordersProducts		 = null;
//        $curOrdersProducts	 = current($this->getProducts());
//        if($curOrdersProducts !== FALSE)
//        {
//            $ordersProducts = $curOrdersProducts;
//        }
//        return $ordersProducts;
//    }
    /**
     * Retourne le statut de la commande
     * @return OrdersStatus
     */
//    public function getStatut()
//    {
//        // si on n'a pas encore mis le statut en cache
//        if($this->_statut == null)
//        {
//            $this->_statut = OrdersStatus::findById($this->getOrdersStatus());
//        }
//
//        return $this->_statut;
//    }


    /**
     * Retourne le graphiste de la commande
     * @return TUser
     */
//    public function getTGraphiste()
//    {
//        return TUser::findUserByLogin($this->getGraphiste());
//    }

    /**
     * renvoi l'objet customer du client
     * @return customer
     */
//    public function getCustomer()
//    {
//        // si on a pas encore récupéré le client
//        if($this->client === null)
//        {
//            // on le récupére
//            $this->client = customer::findById($this->getCustomersId());
//        }
//
//        return $this->client;
//    }


    /**
     * renvoi le nombre POSITIF de crédit utilisé par une commande
     * @return float
     */
//    public function getCreditUsed()
//    {
//        // si on a pas encore récupéré les totaux
//        if($this->_creditUsed == null)
//        {
//            // on le récupére
//            $this->_creditUsed = TCreditMouvement::creditsUsedByOrder($this->getOrdersId());
//        }
//
//        return $this->_creditUsed;
//    }
    /**
     * Retourne l'objet Prix du montant total la commande (crédit déduits)
     * @return Prix
     */
//    public function getPrix()
//    {
//        // si on a pas encore récupéré le prix
//        if($this->_prix === null)
//        {
//            $montant = 0;
//
//            // Calcul du montant HT et de la TVA
//            foreach($this->getProducts() as $value)
//            {
//                $montant += $value->getFinalPrice();
//            }
//
//            // Prix total de la commande
//            $this->_prix = new Prix($montant, $this->getIdCurrencies(), $this->getTVATaux(), Prix::PRIXHT);
//        }
//
//        return $this->_prix;
//    }


    /**
     * Retourne l'objet Prix du montant total originale de la commande (crédit non déduits)
     * @return Prix
     */
//    public function getPrixOrigine()
//    {
//        // si on a pas encore récupéré le prix
//        if($this->_prixOrigine === null)
//        {
//            $montant = 0;
//
//            // Calcul du montant HT et de la TVA
//            foreach($this->getProducts() as $value)
//            {
//                $montant += $value->getProductsPrice();
//            }
//
//            // Prix total de la commande
//            $this->_prixOrigine = new Prix($montant, $this->getIdCurrencies(), $this->getTVATaux(), Prix::PRIXHT);
//        }
//
//        return $this->_prixOrigine;
//    }


    /**
     * Retourne l'objet Prix du prix d'achat
     * la tva de ce prix est fixé à 0 car on peux avoir 2 fournisseur avec des taux de TVA différent
     * @return Prix
     */
//    public function getPrixAchat()
//    {
//        // si on a pas encore récupéré le prix
//        if($this->_prixAchat === null)
//        {
//            $montant = 0;
//
//            // Calcul du montant HT et de la TVA
//            foreach($this->getAOrderSupplierOrder() as $orderSupplierOrder)
//            {
//                // on ajoute le montant HT
//                $montant += $orderSupplierOrder->getPrice()->getMontant(Prix::PRIXHT);
//            }
//
//            // Prix total de la commande
//            $this->_prixAchat = new Prix($montant, TCurrencies::ID_EURO, 0, Prix::PRIXHT);
//        }
//
//        return $this->_prixAchat;
//    }


    /**
     * Obtenir le prix du montant total des fraas de port de la commande
     * @return Prix L'objet prix du montant total des frais de port
     */
//    public function getPrixFdp()
//    {
//        return $this->getOrdersProducts()->getPrixFdp();
//    }

    /**
     * Retourne l'objet Prix du montant des facile crédit bonus utilisé pour cette commande
     * @return Prix
     */
//    public function getPrixCreditBonusUsed()
//    {
//        return new Prix($this->getCreditUsed(), TCurrencies::ID_FACILE_CREDIT, 0);
//    }


    /**
     * Retourne l'objet Prix du montant des facile crédit qui devrait être obtenu par rapport au partenariat
     * @return Prix
     */
//    public function getPrixCreditPartenariat()
//    {
//        return $this->getCustomer()->prixCreditRistournePartenariat($this->getPrix());
//    }
    /**
     * Obtenir le libelle de l'etat d'avancement d'un BAT
     * @return string libelle etat avancement du BAT
     */
//    public function getBatStatusLibelle()
//    {
//        return self::getBatStatusLibelleFrom($this->getBatStatus());
//    }


    //TODO Repository
    /**
     * renvoi toutes les commandes non supprimé considéré comme des ventes à pertes
     * @return order[]
     */
//    public static function findAllVentesAPerte()
//    {
//        $return = array();
//
//        // requête récupérant les ventes à pertes
//        $sql = 'SELECT o.orders_id,
//			sum(products_price) - sum(comment) / (1 + (tva_recuperable * taux_tva) / 100)  as perte
//			FROM ' . RegFourCmd::$_SQL_TABLE_NAME . ' rfg
//			JOIN ' . self::$_SQL_TABLE_NAME . ' o
//				ON o.orders_id = rfg.id_cmd
//			JOIN ' . OrdersProducts::$_SQL_TABLE_NAME . ' op
//				ON o.orders_id = op.orders_id
//			JOIN ' . ReglementsFournisseurs::$_SQL_TABLE_NAME . ' rf
//				ON rfg.id_reg = rf.id_reg
//			JOIN ' . fournisseur::$_SQL_TABLE_NAME . ' f
//				ON four_reg = id_four
//			WHERE orders_status <> ' . OrdersStatus::STATUS_SUPPRESSION . '
//			GROUP BY o.orders_id, tva_recuperable, taux_tva
//			HAVING perte < 0
//			ORDER BY date_purchased DESC';
//
//        // pour chaque ligne
//        foreach(DB::fetchAll($sql) as $row)
//        {
//            // on récupére la commande
//            $order = order::findById($row['orders_id']);
//
//            // on met à jour la propriété perte
//            $order->setPerte(round($row['perte'], 2));
//
//            // ajout au tableau de retour
//            $return[] = $order;
//        }
//
//        return $return;
//    }


    /**
     * renvoi toutes les commandes dispo pour un fournisseur trier dans le bon ordre
     * @param fournisseur $fournisseur l'objet du fournisseur
     * @return order
     */
//    public static function findAllDispoForFournisseur($fournisseur)
//    {
//        $return = array();
//
//        // paramétre de la requête
//        $aFields	 = array('obp.total_buying_price', 'os.ord_sta_available_supplier_access');
//        $aValue		 = array(array(0, '>'), 1);
//        $joinList	 = array(array('table' => 'v_order_buying_price', 'alias' => 'obp', 'joinCondition' => 't.orders_id = obp.id_order'),
//            array('table' => OrdersStatus::$_SQL_TABLE_NAME, 'alias' => 'os', 'joinCondition' => 't.orders_status = os.orders_status_id'));
//
//        // on récupére toutes les commandes disponibles pour les fournisseurs
//        $allOrder = self::findAllBy($aFields, $aValue, array(), 0, $joinList);
//
//        // pour chaque commande
//        foreach($allOrder AS $order)
//        {
//            // si la commande est refusé
//            if($order->getStatutPropositionsForFournisseur($fournisseur) == 'refuser')
//            {
//                // on la met dans notre liste des commandes en 1 pour qu'elle s'affiche en premier
//                $return['1-' . $order->getOrdersId()] = $order;
//            }
//            // commande accépté ou sans proposition
//            elseif($order->getStatutPropositionsForFournisseur($fournisseur) <> 'non_interesse')
//            {
//                // on la met dans notre liste des commandes
//                $return['0-' . $order->getOrdersId()] = $order;
//            }
//        }
//
//        // trie le tableau dans le bonne ordre
//        krsort($return);
//
//        return $return;
//    }


    /**
     * renvoi toutes les facture HT non supprimé entre 2 date
     * @param DateHeure $dateDebut
     * @param DateHeure $dateFin
     * @return order[]
     */
//    public static function findAllHTByDate(DateHeure $dateDebut, DateHeure $dateFin)
//    {
//        return self::findAllBy(array('status_ht', 'orders_status', 'date_purchased', 'date_purchased'), array(array(0, '<>'), array(OrdersStatus::STATUS_SUPPRESSION, '<>'), array($dateDebut->format(DateHeure::DATETIMEMYSQL), '>='), array($dateFin->format(DateHeure::DATETIMEMYSQL), '<=')), array('date_purchased DESC'));
//    }


    /**
     * renvoi les commande correspondant aux status
     * @param int[] $aStatut tableau des id de statuts
     * @param bool $noPoructionFileOnly =FALSE mettre TRUE pour n'avoir que les commandes qui n'ont pas de fichier en depart fab
     * @return order[]
     */
//    public static function findAllByIdStatut($aStatut, $noPoructionFileOnly = FALSE)
//    {
//        $aChamp	 = array();
//        $aValue	 = array();
//
//        // si le statut n'est pas un statut
//        if(!is_array($aStatut))
//        {
//            // on le transforme en array
//            $aStatut = array($aStatut);
//        }
//
//        // pour chaque id de statut
//        foreach($aStatut AS $idStatut)
//        {
//            // ajout du statut à la requête
//            $aChamp[]	 = 'orders_status';
//            $aValue[]	 = array($idStatut, 'IN');
//        }
//
//        // si on ne veux que les commandes qui n'ont pas de fichier en depart fab
//        if($noPoructionFileOnly)
//        {
//            // on ajoute les paramétre à la requête
//            $aChamp[]	 = 'id_dossier_depart_fab';
//            $aValue[]	 = 0;
//        }
//
//        return self::findAllBy($aChamp, $aValue);
//    }


    /**
     * renvoi toutes les commandes à envoyé au site gérant les avis
     * @return order[]
     */
//    public static function findAllForAvis()
//    {
//        // création de la date de livraison minimum pour être traité
//        $date	 = new DateHeure();
//        $duree	 = new Duree(-10, Duree::TYPE_JOUR);
//        $date->addTime($duree);
//
//        // paramétre de la requête
//        $aChamp	 = array('date_purchased', 'orders_status', 'oso.ord_sup_ord_delivery_date', 'ord_avis_sending_date', 'hos_site_secret_key');
//        $aValue	 = array(array('2016-06-01 00:00:00', '>='), array(array(OrdersStatus::STATUS_FABRICATION, OrdersStatus::STATUS_EXPEDITION, OrdersStatus::STATUS_LIVRE), '='), array($date->format(DateHeure::DATEMYSQL), '<='), array(null, 'IS'), array(null, 'IS NOT'));
//
//        // jointures
//        $joinList = array(array('table' => customer::$_SQL_TABLE_NAME, 'alias' => 'c', 'joinCondition' => 't.customers_id = c.customers_id'),
//            array('table' => TAOrderSupplierOrder::$_SQL_TABLE_NAME, 'alias' => 'oso', 'joinCondition' => 't.orders_id = oso.id_order'),
//            array('table' => siteHost::$_SQL_TABLE_NAME, 'alias' => 'h', 'joinCondition' => 'c.host = h.host_id'));
//
//        return order::findAllBy($aChamp, $aValue, array(), 0, $joinList);
//    }


    /**
     * renvoi toutes les options sur commandes ayant un id de commande d'origine
     * @param int $idOptionOrder l'id de la commande d'origine
     * @return order[]
     */
//    public static function findAllByIdOptionOrder($idOptionOrder)
//    {
//        return self::findAllBy(array('ord_option_order_id'), array($idOptionOrder));
//    }


    /**
     * indique si des fichiers sont disponible pour être utilisé dans une autre commande
     * @param int $idCustomer id du client
     * @return boolean TRUE si les fichiers sont disponibles et FALSE sinon
     */
//    public static function findAllWithfilesAvailableForOtherOrder($idCustomer)
//    {
//        // paramétre de la requête
//        $aChamp	 = array('customers_id', 'orders_status', 'orders_status', 'id_dossier');
//        $aValue	 = array($idCustomer, array(OrdersStatus::STATUS_FICHIER_ATTENTE, '!='), array(OrdersStatus::STATUS_FICHIER_ATTENTE_NOUVEAU, '!='), array('', '!='));
//
//        // on renvoi le résultat de la requête
//        return order::findAllBy($aChamp, $aValue, array('orders_id DESC'));
//    }

    /**
     * retrourne un objet order par rapport à un id crypté via la méthode ToolsSecure::cryptInput. renvoi un objet vide si aucune commande ne correspond
     * @param string $cryptedId
     * @return order
     */
//    public static function findByCryptedId($cryptedId)
//    {
//        return self::findById(ToolsSecure::decryptInput($cryptedId));
//    }

    /**
     * renvoi le nombre de commande d'un client
     * @param int $idCustomer id du client
     * @param type $withoutSuppressionAnnulation =FALSE mettre TRUE si on ne veux pas compter les commandes en suppression et celle en annulation
     * @return int
     */
//    public static function countForCustomer($idCustomer, $withoutSuppressionAnnulation = FALSE)
//    {
//        // on limitte la requete au client
//        $where = array(array('customers_id', $idCustomer, 'd'));
//
//        // si on ne veux pas les commandes en suppression et annulation
//        if($withoutSuppressionAnnulation)
//        {
//            // on exclut les commande en suppression et annulation
//            $where[] = array('orders_status', OrdersStatus::STATUS_ANNULATION, 'd', '<>');
//            $where[] = array('orders_status', OrdersStatus::STATUS_SUPPRESSION, 'd', '<>');
//        }
//
//        // on renvoi le count
//        return DB::prepareSelectAndExecuteCount(self::$_SQL_TABLE_NAME, $where);
//    }
    /**
     * Retourne les commandes d'un client avec le nombre de commande et un ordre
     * @param type $idCustomer id du client
     * @param int $nombreCommande =0 le nombre de commande à retourner par défaut on retourne tous
     * @param array $order = array('orders_id DESC') l'ordre d'affichage par défaut par ordre décroissant d'id afin d'avoir les derniéres commande
     * @return order[]
     */
//    public static function findAllByIdCustomer($idCustomer, $nombreCommande = 0, $order = array('orders_id DESC'))
//    {
//        return self::findAllBy(array('customers_id'), array($idCustomer), $order, $nombreCommande);
//    }


    /**
     * Retourne les commandes d'un client pour sa balance (hors commande en suppression et commande avant le 2009-04-15)
     * @param type $idCustomer id du client
     * @return order[]
     */
//    public static function findAllForBalance($idCustomer)
//    {
//        return self::findAllBy(array('customers_id', 'orders_status', 'date_purchased'), array($idCustomer, array(OrdersStatus::STATUS_SUPPRESSION, '<>'), array('2009-04-15', '>=')));
//    }

    /**
     * renvoi le nombre de commande effectué par des clients différent depuis une certaine durée
     * @param duree $duree la durée a chercher depuis maintenant
     * @param bool $oneByCustomer =FALSE mettre TRUE si on veux compter une seule commande par client ou FALSE pour le total des commandes
     * @param int $newCustomer =DbTable::AVEC veux-t-on les nouveaux clients utilisé les constante de DbTable
     * @param int $idProduitHost =null id de produit host fusion ou null pour mettre toutes les commandes
     * @return int
     */
//    public static function countLastDuree(duree $duree, $oneByCustomer = FALSE, $newCustomer = DbTable::AVEC, $idProduitHost = null)
//    {
//        // on créé une date a partir de maintenant
//        $date = new DateHeure();
//
//        // on calcul la date de départ pour notre requete
//        $date->removeTime($duree);
//
//        // si on veux les commande par client unique
//        if($oneByCustomer)
//        {
//            // on fera un distinct sur les customer id
//            $count = 'DISTINCT t.customers_id';
//        }
//        // on veux tous
//        else
//        {
//            $count = '*';
//        }
//
//        // paramétre pour la requête
//        $aChamps = array('date_purchased');
//        $aValues = array(array($date->format(DateHeure::DATETIMEMYSQL), '>='));
//
//        // si on ne veux que les nouveau client
//        if($newCustomer == DbTable::UNIQUEMENT)
//        {
//            // on ajoute les paramétre à la requête
//            $aChamps[]	 = 'ord_count_for_customer';
//            $aValues[]	 = 1;
//        }
//        // si on ne veux pas les nouveaux client
//        elseif($newCustomer == DbTable::SANS)
//        {
//            // on ajoute les paramétre à la requête
//            $aChamps[]	 = 'ord_count_for_customer';
//            $aValues[]	 = array(1, '>');
//        }
//
//        // si on veux limitté a un id de produit host fusion
//        if($idProduitHost != null)
//        {
//            // on ajoute les paramétre à la requête
//            $aChamps[]	 = 'ord_id_produit_host';
//            $aValues[]	 = $idProduitHost;
//        }
//
//        // on renvoi la réponse
//        return order::count($aChamps, $aValues, $count);
//    }

    /**
     * Retourne le statut, les couleurs et le nb Total des Orders par statut
     * on peux ne pas renvoyer certains statut
     * @param mixed $statusInterdits tableau des statut à ne pas afficher ou null si on veux tous les statut
     * @return array
     */
//    public static function findStatuts($statusInterdits = null)
//    {
//        $all = array();
//
//        // on compte combien il y a de commande danss chaque statut
//        $orders_pending_query	 = DB::req('SELECT orders_status,
//										COUNT(*) AS order_pending
//										FROM ' . order::$_SQL_TABLE_NAME . '
//										GROUP BY orders_status');
//        while($orders_pending			 = $orders_pending_query->fetch_array())
//        {
//            // on en fait un tableau
//            $orders_pending_status[$orders_pending['orders_status']] = $orders_pending['order_pending'];
//        }
//
//        // récupération de tous les orders status
//        $allOrderStatus = OrdersStatus::findAllActive(array('orders_status_name'));
//
//        // si on a des statut interdit
//        if($statusInterdits <> null)
//        {
//            // pour chaque statut
//            foreach($allOrderStatus AS $key => $orderStatus)
//            {
//                // si il est interdit
//                if(in_array($orderStatus->getOrdersStatusId(), $statusInterdits))
//                {
//                    // on supprime ce statut
//                    unset($allOrderStatus[$key]);
//                }
//            }
//        }
//
//        // pour chaque stauts restant
//        foreach($allOrderStatus AS $orderStatus)
//        {
//            // si on ne sais pas combien il y a de commande dans ce statut c'est qu'il n'y en a pas
//            if(!isset($orders_pending_status[$orderStatus->getOrdersStatusId()]))
//            {
//                $orders_pending_status[$orderStatus->getOrdersStatusId()] = 0;
//            }
//
//            $all[$orderStatus->getOrdersStatusId()] = array(
//                'orders_status_name' => $orderStatus->getOrdersStatusName(),
//                'orders_status_id'	 => $orderStatus->getOrdersStatusId(),
//                'color'				 => $orderStatus->getColor(),
//                'total'				 => $orders_pending_status[$orderStatus->getOrdersStatusId()]
//            );
//        }
//
//        return $all;
//    }

    /**
     * Retourne un array avec les infos d'une ligne orders
     * @param type $oIDmd5
     * @return type
     */
//    public static function loadCmdDataByIdMd5($oIDmd5)
//    {
//
//        return DB::fetchRow('select * from orders where md5(orders_id) = "' . $oIDmd5 . '"');
//    }
    /**
     * passe automatiqueemnt les commande en statut "recla traité" en "livré" au bout de 30 jours
     * @param TLog $log un objet log
     */
//    public static function reclaEnLivre($log)
//    {
//        // on récupére toutes les commandes qui sont en recla traité depuis plus de 30j
//        $sql		 = 'SELECT orders_id
//			FROM ' . self::$_SQL_TABLE_NAME . '
//			WHERE DATEDIFF(now(), last_modified) > 30
//			AND orders_status = ' . OrdersStatus::STATUS_RECLA_TRAITE;
//        $allOrdersId = DB::fetchAll($sql);
//
//        // pour chaque commandes
//        foreach($allOrdersId AS $orderId)
//        {
//            // on récupére l'objet order
//            $order = order::findById($orderId['orders_id']);
//            $order->updateStatus(OrdersStatus::STATUS_LIVRE, 'Commande passé automatiquement en statut livré', 0, '', '', 'Robot LGI');
//            $log->addLogContent('Commande ' . $order->getOrdersId() . ' passé en statut "LIVREE".');
//        }
//    }

    /**
     * renvoi tous les order lié à un id de maquette UD
     * @param int $idDesginerUserFile
     * @return order[]
     */
//    public static function findByDesginerUserFile($idDesginerUserFile)
//    {
//        return self::findAllBy(array('id_designer_user'), array($idDesginerUserFile));
//    }


    /**
     * Recuperation des commandes du client regroupees par date d'achat
     * @param customer $client		Le client dont on veut la liste de ses commandes
     * @return array Le tableau des commandes du clients avec les prix d'achat, vente, marges et nombre de commande par jour
     */
//    public static function getRecapForCustomer(customer $client)
//    {
//        $aRecapForCustomer = array(
//            'aOrderCustomer' => array(),
//            'totalAchats'	 => 0,
//            'totalCmd'		 => 0,
//            'totalMarges'	 => 0,
//            'totalVentes'	 => 0
//        );
//
//        // preparation des conditions pour la recuperation des commandes du client
//        $aNotInOrdersStatus	 = self::makeFieldAndValueArrayForFindAll('orders_status', array(4, 20, 26, OrdersStatus::STATUS_SUPPRESSION), 'NOT IN');
//        $aChamp				 = $aNotInOrdersStatus['aChamp'];
//        $aValue				 = $aNotInOrdersStatus['aValue'];
//        $aChamp[]			 = 't.customers_id';
//        $aValue[]			 = $client->getIdCustomer();
//        $joinList			 = array(
//            array('table' => OrdersProducts::$_SQL_TABLE_NAME, 'alias' => 'op', 'joinCondition' => 'op.orders_id = t.orders_id')
//        );
//
//        // recuperation des commandes du client regroupees par date d'achat
//        $allOrders = self::findAllBy($aChamp, $aValue, array('t.date_purchased DESC'), 0, $joinList);
//
//        foreach($allOrders as $order)
//        {
//            $ordersProducts = $order->getOrdersProducts();
//
//            // totaux des prix d'achat, de ventes et du nombre de commandes passees par le client
//            $aRecapForCustomer['totalAchats']	 += $order->getPrixAchat()->getMontant();
//            $aRecapForCustomer['totalCmd']		 += 1;
//            $aRecapForCustomer['totalMarges']	 += ($ordersProducts->getProductsPrice() - $order->getPrixAchat()->getMontant());
//            $aRecapForCustomer['totalVentes']	 += $ordersProducts->getProductsPrice();
//
//            // creation de la cle pour les commandes du jour
//            $dateHeurePurchased			 = new DateHeure($order->getDatePurchased());
//            $dateHeurePurchasedFormat	 = $dateHeurePurchased->format('d-m-Y');
//
//            // creation de liste des prix d'achat, de ventes et du nombre de commandes passees par le client durant la journee
//            if(!isset($aRecapForCustomer['aOrderCustomer'][$dateHeurePurchasedFormat]))
//            {
//                $aRecapForCustomer['aOrderCustomer'][$dateHeurePurchasedFormat] = array(
//                    'achats' => $order->getPrixAchat()->getMontant(),
//                    'cmd'	 => 1,
//                    'marges' => ($ordersProducts->getProductsPrice() - $order->getPrixAchat()->getMontant()),
//                    'ventes' => $ordersProducts->getProductsPrice()
//                );
//            }
//            else
//            {
//                $aRecapForCustomer['aOrderCustomer'][$dateHeurePurchasedFormat]['achats']	 += $order->getPrixAchat()->getMontant();
//                $aRecapForCustomer['aOrderCustomer'][$dateHeurePurchasedFormat]['cmd']++;
//                $aRecapForCustomer['aOrderCustomer'][$dateHeurePurchasedFormat]['marges']	 += ($ordersProducts->getProductsPrice() - $order->getPrixAchat()->getMontant());
//                $aRecapForCustomer['aOrderCustomer'][$dateHeurePurchasedFormat]['ventes']	 += $ordersProducts->getProductsPrice();
//            }
//        }
//
//        return $aRecapForCustomer;
//    }

    //TODO Service
    /**
     * Cree un nouvel objet "Orders" et le retourne
     * @param int $customersId							Identifiant du client
     * @param string $customersName						Nom complet du client
     * @param string $customersStreetAddress			Adresse du client (Voie)
     * @param string $customersCity						Adresse du client (Commune)
     * @param string $customersPostcode					Adresse du client (Code postal)
     * @param string $customersCountry					Adresse du client (Pays)
     * @param string $customersTelephone				Numero de telephone (fixe) du client
     * @param string $customersTelportable				Numero de telephone (portable)  du client
     * @param string $customersEmailAddress				Adresse mail du client
     * @param string $deliveryName						Adresse de livraison (Nom complet)
     * @param string $deliveryStreetAddress				Adresse de livraison (Voie)
     * @param string $deliveryCity						Adresse de livraison (Commune)
     * @param string $deliveryPostcode					Adresse de livraison (Code postal)
     * @param string $billingName						Adresse de facturation (Nom complet)
     * @param string $billingStreetAddress				Adresse de facturation (Voie)
     * @param string $billingCity						Adresse de facturation (Commune)
     * @param string $billingPostcode					Adresse de facturation (Code postal)
     * @param string $paymentMethod						Mode de reglement
     * @param int $ordersStatus							Identifiant du statut de la commande
     * @param string $intitule							Intitule de la commande
     * @param string $customersCompany					=null Nom de la societe
     * @param string $customersSuburb					=null Adresse du client (complement)
     * @param string $customersEmailAddress2			='' 2eme adresse mail du client
     * @param string $customersEmailAddress3			='' 3eme adresse mail du client
     * @param string $deliveryCompany					=null Adresse de livraison (Nom de la societe)
     * @param string $deliveryTelephone					=null Adresse de livraison (Telephone)
     * @param string $deliverySuburb					=null Adresse de livraison (Complement)
     * @param string $deliveryCodePorte					=null Adresse de livraison (Code porte)
     * @param int $deliveryCountryCode					=null Adresse de livraison (Code du pays)
     * @param string $billingCompany					=null Adresse de facturation (Nom de la societe)
     * @param string $billingSuburb						=null Adresse de facturation (Complement)
     * @param int $billingCountryCode					=null Adresse de facturation (Code du pays)
     * @param int $idCurrencies							=2 Monnaie
     * @param string $currencyValue						=null Monnaie (Valeur)
     * @param int $typeFichier							=0 Type de fichier
     * @param string $idDossier							='' Id du dernier dossier envoye a Station Serveur
     * @param int $idDossierDepartFab					=0 le dossier n'est pas en Depart Fab, =1 le dossier y est
     * @param int $idDesignerUser						=0 Id de la maquuette UD
     * @param int $statusHt								=0 Statut HT
     * @param string $graphiste							='' Nom du graphiste
     * @param int $ordIdCategorieRootLgi
     * @param int $ordIdProductLgi
     * @param int $ordIdProduitHost
     * @param int $ordOptionOrderId						=0 id de la commande d'origine si il s'agit d'une option sur commande, 0 sinon
     * @param int $ordCountForCustomer					=1 numéro de la commande pour le client (1 pour la premiere commande)
     * @param int $ordIdCoeffientMargeUsed				=null id du coeffeicient de reduction des marges utilisé dans cette commande
     *
     * @return \Order Objet insere en base
     */
//    public static function createNew($customersId, $customersName, $customersStreetAddress, $customersCity, $customersPostcode, $customersCountry, $customersTelephone, $customersTelportable, $customersEmailAddress, $deliveryName, $deliveryStreetAddress, $deliveryCity, $deliveryPostcode, $billingName, $billingStreetAddress, $billingCity, $billingPostcode, $paymentMethod, $ordersStatus, $intitule, $customersCompany = null, $customersSuburb = null, $customersEmailAddress2 = '', $customersEmailAddress3 = '', $deliveryCompany = null, $deliveryTelephone = null, $deliverySuburb = null, $deliveryCodePorte = null, $deliveryCountryCode = null, $billingCompany = null, $billingSuburb = null, $billingCountryCode = null, $idCurrencies = 2, $currencyValue = null, $typeFichier = 0, $idDossier = '', $idDossierDepartFab = 0, $idDesignerUser = 0, $statusHt = 0, $graphiste = '', $ordIdCategorieRootLgi = null, $ordIdProductLgi = null, $ordIdProduitHost = null, $ordSelection = null, $ordOptionOrderId = 0, $optionsId = '', $ordCountForCustomer = 1, $ordIdCoeffientMargeUsed = null)
//    {
//        // on créé une date pour la date d'achat
//        $aujourdui = new DateHeure();
//
//        $orders = new order();
//
//        $orders->setCustomersId($customersId)
//            ->setCustomersName($customersName)
//            ->setCustomersCompany($customersCompany)
//            ->setCustomersStreetAddress($customersStreetAddress)
//            ->setCustomersSuburb($customersSuburb)
//            ->setCustomersCity($customersCity)
//            ->setCustomersPostcode($customersPostcode)
//            ->setCustomersCountry($customersCountry)
//            ->setCustomersTelephone($customersTelephone)
//            ->setCustomersTelportable($customersTelportable)
//            ->setCustomersEmailAddress($customersEmailAddress)
//            ->setCustomersEmailAddress2($customersEmailAddress2)
//            ->setCustomersEmailAddress3($customersEmailAddress3)
//            ->setDeliveryName($deliveryName)
//            ->setDeliveryCompany($deliveryCompany)
//            ->setDeliveryTelephone($deliveryTelephone)
//            ->setDeliveryStreetAddress($deliveryStreetAddress)
//            ->setDeliverySuburb($deliverySuburb)
//            ->setDeliveryCodePorte($deliveryCodePorte)
//            ->setDeliveryCity($deliveryCity)
//            ->setDeliveryPostcode($deliveryPostcode)
//            ->setDeliveryCountryCode($deliveryCountryCode)
//            ->setBillingName($billingName)
//            ->setBillingCompany($billingCompany)
//            ->setBillingStreetAddress($billingStreetAddress)
//            ->setBillingSuburb($billingSuburb)
//            ->setBillingCity($billingCity)
//            ->setBillingPostcode($billingPostcode)
//            ->setBillingCountryCode($billingCountryCode)
//            ->setPaymentMethod($paymentMethod)
//            ->setDatePurchased($aujourdui->format(DateHeure::DATETIMEMYSQL))
//            ->setOrdersStatus($ordersStatus)
//            ->setIdCurrencies($idCurrencies)
//            ->setCurrencyValue($currencyValue)
//            ->setTypeFichier($typeFichier)
//            ->setIdDossier($idDossier)
//            ->setIdDossierDepartFab($idDossierDepartFab)
//            ->setIdDesignerUser($idDesignerUser)
//            ->setIntitule($intitule)
//            ->setStatusHt($statusHt)
//            ->setGraphiste($graphiste)
//            ->setOrdIdCategorieRootLgi($ordIdCategorieRootLgi)
//            ->setOrdIdProductLgi($ordIdProductLgi)
//            ->setOrdIdProduitHost($ordIdProduitHost)
//            ->setOrdSelection($ordSelection)
//            ->setOrdOptionOrderId($ordOptionOrderId)
//            ->setOrdCountForCustomer($ordCountForCustomer)
//            ->setOrdIdCoeffientMargeUsed($ordIdCoeffientMargeUsed)
//            ->save();
//
//        // on transforme les options complémentaire en tableau
//        $aOptionsid = explode('-', $optionsId);
//
//        // pour chaque option complémentaire
//        foreach($aOptionsid AS $optionId)
//        {
//            // si on a une id
//            if(is_numeric($optionId))
//            {
//                // on créé la liaison entre la commande et l'option
//                TAOrdersOptions::createNew($orders->getOrdersId(), $optionId);
//            }
//        }
//
//        return $orders;
//    }

    /**
     * Cree une commande a partir d'un element du panier
     * @param PanierContent $panierContent			Un element du panier
     * @param TPayment $tPayment					Mode de reglement
     * @param bool|array $aBank						[=FALSE] | Donnees de retour de la banque
     * @param Panier $panier						[=null] Le panier qui sert a la creation de la commande
     * @param customer $customer					[=null] Le client
     * @param AddressBook $addressBookDelivery		[=null] Adresse de livraison
     * @param AddressBook $addressBookBilling		[=null] Adresse de facturation
     * @param ReglementsClients $reglementsClients  [=null] Reglement client (pour paiement CB)
     * @param string $source =inconnu source du paiement (retour Bank ou retour client) uniquement pour les paimenet CB
     * @return Order $order	Commande inseree en base
     */
//    private static function _createNewFromPanierContent(PanierContent $panierContent, TPayment $tPayment, $aBank = FALSE, Panier $panier = null, customer $customer = null, AddressBook $addressBookDelivery = null, AddressBook $addressBookBilling = null, $reglementsClients = null, $source = 'inconnu')
//    {
//        // creation de l'objet Panier
//        if(!$panier)
//        {
//            $panier = $panierContent->getPanier();
//        }
//
//        // creation de l'objet Client
//        if(!$customer)
//        {
//            $customer = customer::findById(array($panier->getIdCustomer()));
//        }
//
//        // creation de l'adresse de livraison
//        if(!$addressBookDelivery)
//        {
//            $addressBookDelivery = AddressBook::findById(array($panier->getIdAdressBookSendto()));
//        }
//
//        // si l'adresse de livraison est invalide
//        if($addressBookDelivery->getAddressBookId() == null)
//        {
//            // on utilisera l'adresse par défaut du client
//            $addressBookDelivery = $customer->getMainAdress();
//        }
//
//        // si on a un numéro de tel dans l'adresse de livraison
//        if($addressBookDelivery->telTransporteur() != null)
//        {
//            // on s'en servira comme numéro de téléphonne pour l'adresse de livraison
//            $deliveryTel = $addressBookDelivery->telTransporteur();
//        }
//        // si on a un numéro de tel portable pour le client
//        elseif($customer->getCustomersTelportable() != null)
//        {
//            // on s'en servira comme numéro de téléphonne pour l'adresse de livraison
//            $deliveryTel = $customer->getCustomersTelportable();
//        }
//        // on a que le tel du client
//        else
//        {
//            // on s'en servira comme numéro de téléphonne pour l'adresse de livraison
//            $deliveryTel = $customer->getCustomersTelephone();
//        }
//
//        // creation de l'adressess de facturation
//        if(!$addressBookBilling)
//        {
//            $addressBookBilling = AddressBook::findById(array($panier->getIdAdressBookBillto()));
//        }
//
//        // si l'adresse de facturation est invalide
//        if($addressBookBilling->getAddressBookId() == null)
//        {
//            // on utilisera l'adresse par défaut du client
//            $addressBookBilling = $customer->getMainAdress();
//        }
//
//        // si la commande vient d'un devis "option sur commmande" ou si le client a choisi une maquette UniversDesign
//        if(preg_match('#.*(Option sur commande).*#', $panierContent->getPanConProduitDescription()) || $panierContent->getIdDesignerUser() > 0)
//        {
//            // la commande passera en statut fichier recu
//            $ordersStatus = OrdersStatus::STATUS_FICHIERS_RECUS;
//        }
//        // dans tout les autres cas
//        else
//        {
//            // la commande passera en statut attente de fichier et on enverra le mail pour les normes de fichier
//            $ordersStatus = OrdersStatus::STATUS_FICHIER_ATTENTE;
//        }
//
//        // Statut Ht de la commande : 1=HT (si Taux de TVA = 0) et 0=TTC (si Taux de TVA > 0)
//        $statusHt = 0;
//        if(intval($panier->getPanTaxTva()) == 0)
//        {
//            $statusHt = 1;
//        }
//
//        // récupération du libellé du produit
//        $libelleProduit = $panierContent->getPanConProduitDescription() . $panierContent->descriptionReductionMarge();
//
//        // creation et enregistrement de la commande
//        $order = order::createNew($customer->getCustomersId(), $customer->getNomComplet(), $customer->getMainAdress()->getStreetAdress(), $customer->getMainAdress()->getCity(), $customer->getMainAdress()->getPostCode(), $customer->getMainAdress()->getCountryName(), $customer->getCustomersTelephone(), $customer->getCustomersTelportable(), $customer->getCustomersEmailAddress(), $addressBookDelivery->getNomComplet(), $addressBookDelivery->getEntryStreetAddress(), $addressBookDelivery->getEntryCity(), $addressBookDelivery->getEntryPostcode(), $addressBookBilling->getNomComplet(), $addressBookBilling->getEntryStreetAddress(), $addressBookBilling->getEntryCity(), $addressBookBilling->getEntryPostcode(), $tPayment->getPaLibelle(), $ordersStatus, $panierContent->getPanConIntitule(), $customer->getCompany(), $customer->getMainAdress()->getSuburb(), '', '', $addressBookDelivery->getEntryCompany(), $deliveryTel, $addressBookDelivery->getEntrySuburb(), $addressBookDelivery->getEntryCodePorte(), $addressBookDelivery->getEntryCountryId(), $addressBookBilling->getEntryCompany(), $addressBookBilling->getEntrySuburb(), $addressBookBilling->getEntryCountryId(), $panierContent->calculPrix()->getCurrencies()->getCurrenciesId(), $panierContent->calculPrix()->getCurrencies()->getValeurDevisePour1Euro(), $panierContent->getPanConTypeFichier(), '', 0, $panierContent->getIdDesignerUser(), $statusHt, '', $panierContent->getPanConIdCategorieRootLgi(), $panierContent->getPanConIdProductLgi(), $panierContent->getPanConIdProduitHost(), $panierContent->getPanConSelection(), $panierContent->getPanConOptionOrderId(), $panierContent->getPanConOptionsId(), $customer->countOrder() + 1, $panierContent->getPanConIdCoeffientMargeUsed());
//
//        // utilisation des credits du panier
//        $aUseCredits		 = $order->_useCreditFromPanierContent($panierContent);
//        $finalPrice			 = $aUseCredits['finalPrice'];
//        $passStatusReglAuto	 = $aUseCredits['passStatusReglAuto'];
//
//        /*
//         * Ajout du produit de la commande
//         */
//        $ordersProducts = new OrdersProducts();
//        $ordersProducts->setOrdersId($order->getOrdersId());
//        $ordersProducts->setProductsName($libelleProduit);
//        $ordersProducts->setProductsPrice($panierContent->calculPrix()->getMontant(Prix::PRIXHT));
//        $ordersProducts->setFinalPrice($finalPrice);
//        $ordersProducts->setProductsTax($panier->getPanTaxTva());
//        $ordersProducts->setOrdProFdp($panierContent->getPanConFdp());
//        $ordersProducts->save();
//
//        // si on a une commande de PDF UD
//        if($order->getTypeFichier() == PanierContent::TYPE_FICHIER_PDF_UD)
//        {
//            // création de la commande fournisseur
//            $supplierOrder = TSupplierOrder::createNewForOrder($order->getOrdersId(), $panierContent->dateLivraisonEstime()->format(DateHeure::DATEMYSQL), fournisseur::ID_SUPPLIER_UD, $panierContent->getPanConPrixAchat(), '', '', TSupplierOrderStatus::ID_STATUS_DISPATCHED);
//        }
//        // commande classique
//        else
//        {
//            // création de la pré commande fournisseur
//            $supplierOrder = TSupplierOrder::createNewForOrder($order->getOrdersId(), $panierContent->dateLivraisonEstime()->format(DateHeure::DATEMYSQL), $panierContent->getIdFournisseurs(), $panierContent->getPanConPrixAchat(), $panierContent->getPanConFournisseur());
//        }
//
//        // on reset les réglement client pour être sur de récupéré les derniers (FC standard)
//        $order->resetReglementClient();
//
//        // si on vient de la banque : reglement client par CB
//        //if($aBank !== FALSE && $aBank['response_code'] == '00')
//        if($aBank !== FALSE && $aBank['result']['code'] == '00000')
//        {
//            // si le reglement client n'existe pas : insertion d'un reglement client
//            if($reglementsClients === null)
//            {
//                // on commence par créé le réglement
//                $reglementsClients = ReglementsClients::createFromPayline($aBank);
//            }
//
//            // il y a eu un probleme lors du réglement
//            if($reglementsClients == FALSE)
//            {
//                // on créé un log
//                $log = TLog::initLog('commande en double potentiel');
//                $log->Erreur('id de la commande : ' . $order->getOrdersId());
//                $log->Erreur('Info bank :');
//                $log->Erreur(var_export($aBank, TRUE));
//            }
//            // on a bien un reglement
//            else
//            {
//                // lier le reglement client a la cde et maj du paiment par CB et passage en statut regle
//                $order->paidByPayline($reglementsClients, $passStatusReglAuto, $source);
//            }
//        }
//
//        // création de la séléction fournisseur si néessaire
//        SelectionFournisseur::createNew($order->getOrdersId(), $supplierOrder->getIdSupplierOrder(), $panierContent->getPanConSelectionP24(), $panierContent->getIdFournisseurs());
//
//        // commentaire commande
//        $message_comment_ini = '--- Informations ---<br />Prix d\'achat : ' . $panierContent->getPrixAchat()->format() . '<br />Fournisseur : ' . $panierContent->getPanConFournisseur() . '<br />Livraison : ' . $panierContent->getPanConDelaiFab();
//
//        // si la commande vient d'un devis
//        if($panierContent->getPanConSrc() == 'devis')
//        {
//            // on recupere notre objet devis
//            $devis = Devis::findById($panierContent->getIdDevis());
//
//            // si on a récupéré notre devis
//            if($devis !== null)
//            {
//                // on met a jour le devis et on cree la lisaison entre devis et commande
//                $devis->updateDevisApresTransformationEnCommande($order->getOrdersId(), $customer->getCustomersId());
//                // ajout dans le commentaire des informations du devis
//                $message_comment_ini .= '<br />DEVIS N° <a href="index.php?action=devis/show&oID=' . $devis->getDevisId() . '">' . $devis->getDevisId() . '</a>';
//            }
//            // on a pas récupéré de devis
//            else
//            {
//                $log = TLog::initLog('Panier provenant d\'un devis sans devis');
//                $log->Erreur('id : ' . $panierContent->getIdDevis());
//            }
//        }
//
//        // ajoute un hitorique a la commande
//        $order->addHistory($message_comment_ini . '<br />' . $panier->getPanCommentaire(), $ordersStatus, 0, '', '', 'Le client');
//
//        // on met a jour le panier avec l'id de la commande
//        $panierContent->updateIdOrder($order->getOrdersId());
//
//        // si on a une reduction des marges de nouveau client
//        if($order->getMargeCoefficientUsed()->isNewCustomer())
//        {
//            // on ajoute le siret dans la table
//            TSiretUsed::createNew($customer->getSiret(), $customer->getHost());
//        }
//
//        // si la commande possede une maquette UniversDesign
//        if($panierContent->getIdDesignerUser() > 0)
//        {
//            // on recupere la maquette
//            $maquette = TDesignerUserFile::findById($panierContent->getIdDesignerUser());
//            $maquette->generatePdfHdToFile('/data/fichiers_clients/a_envoyer/' . $order->getOrdersId() . '/maquette-univers-design.pdf');
//        }
//
//        // si on a l'utilisation d'un ancien fichier
//        if($panierContent->getPanConTypeFichier() == PanierContent::TYPE_FICHIER_OTHER_ORDER)
//        {
//            // on recherche l'ancienne commande
//            $oldOrder = order::findById(array($panierContent->getPanConIdOldOrder()));
//
//            // si la commande appartient au client
//            if($oldOrder->getCustomersId() == $order->getCustomersId())
//            {
//                // on initialise le processus de transfert
//                $order->useOldOrderFiles($oldOrder);
//            }
//            // pas de commande probablement
//            else
//            {
//                // on change le type de fichier dans la commande
//                $order->setTypeFichier(PanierContent::TYPE_FICHIER_CLIENT)
//                    ->save();
//            }
//        }
//
//        return $order;
//    }


    /**
     * Cree les commandes a partir des elements d'un panier
     * @param Panier $panier		Le panier qui sert a la creation de la commande
     * @param TPayment $tPayment	Le mode de reglement de la commande
     * @param bool|array $aBank		[=FALSE] | Donnees de retour de la banque
     * @param string $source        [="inconnu"] Source du paiement (retour banque ou retour client) uniquement pour les paimenets CB
     * @return Order[] Liste des commandes crees a partir des elements d'un panier
     */
//    public static function createNewFromPanier(Panier $panier, TPayment $tPayment, $aBank = FALSE, $source = 'inconnu')
//    {
//        $aOrder = array();
//
//        // si il nous manque des informations
//        if(!is_a($panier, 'Panier') || $panier->getIdCustomer() <= 0 || $panier->getIdAdressBookSendto() <= 0 || $panier->getIdAdressBookBillto() <= 0)
//        {
//
//            $log = TLog::initLog('Création de commande depuis panier');
//            $log->Erreur("is_a Panier " . is_a($panier, 'Panier'));
//            $log->Erreur('$panier->getIdCustomer()' . $panier->getIdCustomer());
//            $log->Erreur('$panier->getIdAdressBookSendto()' . $panier->getIdAdressBookSendto());
//            $log->Erreur('$panier->getIdAdressBookBillto()' . $panier->getIdAdressBookBillto());
//
//            // on renvoi un tableau vide
//            return array();
//        }
//
//        // creation de l'objet client
//        $customer = customer::findById(array($panier->getIdCustomer()));
//
//        // creation des adresses de livraison et de facturation
//        $addressBookDelivery = AddressBook::findById(array($panier->getIdAdressBookSendto()));
//        $addressBookBilling	 = AddressBook::findById(array($panier->getIdAdressBookBillto()));
//
//        // par defaut on n'enverra pas de mail pour les normes de fichiers "Instructions IMPORTANTES pour vos fichiers"
//        $sendMailNormesFichier = FALSE;
//
//        // si on vient de la banque : creation d'un reglement client
//        $reglementsClients	 = null;
//        $montantRegle		 = 0;
//        if($aBank !== FALSE && $aBank['result']['code'] == '00000')
//        {
//            $reglementsClients = ReglementsClients::createFromPayline($aBank);
//        }
//
//        // si on a une remise dans le panier
//        if($panier->haveDiscount())
//        {
//            // création des crédits de la remise
//            TCreditMouvement::createNew($panier->getIdCustomer(), $panier->calculPrixCreditDiscount()->getMontant(Prix::PRIXTTC), TCreditMouvementType::DISCOUNT, TUser::ID_ROBOT_LGI, null, null, null, 'Remise', null, $panier->getDiscount()->getIdDiscount());
//        }
//
//        // pour chaque element du Panier
//        foreach($panier->getContent() as $panierContent)
//        {
//            // si dans le panier on a pas de fichier deja recu, on envoi le mail pour les normes de fichier
//            $fileAlreadySent = self::checkFileAlreadySentFrom($panierContent);
//            if($fileAlreadySent === FALSE)
//            {
//                $sendMailNormesFichier = TRUE;
//            }
//
//            // creation de la commande
//            $newOrder = self::_createNewFromPanierContent($panierContent, $tPayment, $aBank, $panier, $customer, $addressBookDelivery, $addressBookBilling, $reglementsClients, $source);
//
//            // si notre commande provient d'une ancienne commande
//            if($panierContent->getPanConSrc() === 'order' && $panierContent->getPanConIdOldOrder() != null)
//            {
//                // on créé le lien entre les 2 commandes
//                TAOrderRenew::createNew($panierContent->getPanConIdOldOrder(), $newOrder->getOrdersId());
//            }
//
//            // SI le client a commadé un fichier PDF et que le produit à été payé..
//            if($newOrder->getTypeFichier() == PanierContent::TYPE_FICHIER_PDF_UD && $newOrder->isFullPaid())
//            {
//                // .. On lui envoie un mail avec son fichiers
//                MailCustomer::sendMailUdFile($newOrder->getOrdersId());
//            }
//
//            // on ajoute la commande à notre tableau contenant toutes les commandes
//            $aOrder[$newOrder->getOrdersId()] = $newOrder;
//        }
//
//        // calcul du montant total TTC de la commande (somme des prix des produits apres deduction des credits utilises)
//        $montantTotalTTC = 0;
//        foreach($aOrder as $order)
//        {
//            $montantTotalTTC += $order->getPrix()->getMontant(Prix::PRIXTTC);
//        }
//
//        // si un reglement a ete effectue et que le montant paye par CB est inferieur au montant total TTC de la commande
//        if($montantRegle > 0 && $montantRegle < round($montantTotalTTC, 2))
//        {
//            // pour chaque commande
//            foreach($aOrder as $order)
//            {
//                // on ajoute un historique
//                $order->addHistory('Paiement d\'un montant de ' . $montantRegle . ' inférieur au montant total de la commande (' . $montantTotalTTC . ')', 0, 0, '', '', 'Le client');
//            }
//        }
//
//        // ===============================================================================
//        // Pour chaque élément du panier
//        foreach($panier->getContent() as $panierContent)
//        {
//            // on déplace les fichiers déjà envoyé
//            $panierContent->moveUploadedFileToOrder();
//        }
//
//        // envoi du mail au client
//        MailCustomer::sendRecapCmd($panier, $customer, $tPayment, $addressBookDelivery, 'sim@fluoo.com');
//
//        // si le client n'avais pas de commande ou une commande de ce jour
//        if($customer->firstOrderDateHeureOrToday()->format(DateHeure::DATEMYSQL) == System::today()->format(DateHeure::DATEMYSQL))
//        {
//            // on met à jour la date de la premiére commande
//            $customer->setCusFirstOrderDay(System::today()->format(DateHeure::DATEMYSQL))
//                ->save();
//        }
//
//        // on passe les paniers du client en statut "supprimé"
//        Panier::setAllDeletedByIdCustomer($panier->getIdCustomer());
//
//        return $aOrder;
//    }
    /**
     * Retourne l'historique de la commande
     * @param bool $onlyNotified =FALSE mettre TRUE pour n'avoir que les historique ou le client a était notifié
     * @return OrdersStatusHistory[] Liste les changements de statut d'une commande
     */
//    public function allOrdersStatusHistory($onlyNotified = FALSE)
//    {
//        return OrdersStatusHistory::findAllForOrder($this->getOrdersId(), $onlyNotified);
//    }

    /**
     * Cette fonction renvoi la version des fichiers envoyer par le client
     * @return string
     */
//    public function getFilesVersions()
//    {
//        // Si il existe un numéro de version
//        if(is_numeric($this->getIdDossier()))
//        {
//            $retour = 'Version ' . $this->getIdDossier();
//
//            // Si aucun fichier n'a était envoyé
//        }
//        elseif($this->getIdDossier() == '')
//        {
//            $retour = 'Aucun fichier';
//
//            // FTP ou Mail
//        }
//        elseif($this->getIdDossier() == 'Ftp' || $this->getIdDossier() == 'Mail')
//        {
//            $retour = $this->getIdDossier();
//
//            // Tous les autres cas (ancien systéme d'envoi de fichier) dans le cas ou on doit renvoyer inconnu
//        }
//        else
//        {
//            $retour = 'Vieux fichiers';
//        }
//
//        // Si il existe un numéro de maquette
//        if($this->getIdDesignerUser() != '0')
//        {
//            $retour .= ' N° maquette ' . $this->getIdDesignerUser();
//        }
//        // si le module UD est actif
//        elseif(MODULE_DESIGNER == true)
//        {
//            $retour .= ' - Pas de maquette en ligne';
//        }
//
//        return $retour;
//    }

    /**
     * Construit un \AddressBook avec l'adresse de facturation
     * @return \AddressBook
     */
//    public function constructAdressBookForBilling()
//    {
//        $oAdressBook = new AddressBook();
//
//        // on découpe nom et prénom
//        $name = AddressBook::explodeFirstLastName($this->getBillingName());
//
//        $oAdressBook->setCustomersId($this->getCustomersId())
//            ->setEntryCompany($this->getBillingCompany())
//            ->setEntryFirstname($name['firstname'])
//            ->setEntryLastname($name['lastname'])
//            ->setEntryStreetAddress($this->getBillingStreetAddress())
//            ->setEntrySuburb($this->getBillingSuburb())
//            ->setEntryPostcode($this->getBillingPostcode())
//            ->setEntryCity($this->getBillingCity())
//            ->setEntryCountryId($this->getBillingCountryCode());
//
//        return $oAdressBook;
//    }
    /**
     * Retourne l'objet Prix du montant total la commande si l'on déduit les facile crédit disponible actuellement
     * @return Prix
     */
//    public function getPrixApresCreditDispo()
//    {
//        // on prend le prix restant à payer de la commande
//        $prix = clone $this->prixRestantAPayer(TRUE);
//
//        // on retire le montant des crédit dispo
//        $prix->updateMontant(1, $this->getCustomer()->getCreditsTotauxDispos()->getMontant() * -1, Prix::PRIXTTC);
//
//        // si le prix est négatif c'est qu'on a plus de crédit que le montant de la commande
//        if($prix->getMontant() < 0)
//        {
//            // on met le prix à 0
//            $prix = new Prix(0, TCurrencies::ID_EURO, $this->getTVATaux());
//        }
//
//        return $prix;
//    }
    /**
     * renvoi TRUE si la commande à une livraison en marque blanche
     * @return boolean
     */
//    public function isMarqueBlanche()
//    {
//        // pour chaque order product
//        foreach($this->getProducts() AS $products)
//        {
//            // on regarde si on a "marque blanche" dans le descriptif du produit
//            if(preg_match('#marque blanche#', $products->getProductsName()))
//            {
//                return TRUE;
//            }
//        }
//
//        return FALSE;
//    }


    /**
     *  vérifie si un avis existe pour une commande spécifique ...
     */
//    public function isAvisOrderExists()
//    {
//        return Avis::existByIdOrder($this->getOrdersId());
//    }


    /**
     * Vérifie si il s'agit d'une option sur commande
     * @return bool TRUE si il s'agit d'une option sur commande et false sinon
     */
//    public function isOptionSurCommande()
//    {
//        // si on a un id d'option sur commande
//        if($this->getOrdOptionOrderId() > 0)
//        {
//            return TRUE;
//        }
//
//        return FALSE;
//    }


    /**
     * indique si cette commande est intégralement payé
     * @return bool true si la commande est intégralement payé
     */
//    public function isFullPaid()
//    {
//        // les commandes trop ancienne n'ayant pas de réglement
//        if($this->getOrdersId() < 37000)
//        {
//            // on les considére comme réglé
//            return true;
//        }
//
//        // si on a un reste à payer inférieur ou égale à 1 centime. On valide les commandes avec 1 centime à payer restant à cause de soucis d'arrondi
//        if($this->priceToPayAfterCreditNote()->getMontant() <= 0.01)
//        {
//            // commande payé
//            return true;
//        }
//
//        // commande non payé
//        return false;
//    }


    /**
     * Retourne la TVA de la premiere ligne de la commande. Renvoi si 0 si on n'a aucun produit.
     * @return float
     */
//    public function getTVATaux()
//    {
//        // récupération du produit
//        $products = $this->getOrdersProducts();
//
//        // si on n'a aucun produit
//        if($products == null)
//        {
//            // pas de tva
//            return 0;
//        }
//
//        // on renvoi le taux de tva
//        return round($products->getProductsTax(), 2);
//    }


    /**
     * indique si une commande est annulable
     * @return type
     */
//    public function isCancelable()
//    {
//        // on regarde si elle est dans un statut annulable
//        return $this->getStatut()->isCancelable();
//    }


    /**
     * indique si cette commande a été fait le premier jour des commandes de ce client
     * @return boolean
     */
//    public function isFirstDayOrder()
//    {
//        // si la date d'achat correspond à la date du premier jour des commandes
//        if($this->getDateHeurePurchased()->format(DateHeure::DATEMYSQL) == $this->getCustomer()->firstOrderDateHeureOrToday()->format(DateHeure::DATEMYSQL))
//        {
//            return true;
//        }
//
//        return false;
//    }


    /**
     * indique le nombre de bouton pour le listing des commandes
     */
//    public function numberOfButonInOrderList()
//    {
//        // on initialise avec 2 boutons (contactez nous et historique des emails
//        $total = 2;
//
//        // si on peux renouveller la commande
//        if($this->reorderable())
//        {
//            // on a un bouton de plus
//            $total++;
//        }
//
//        // si la commande est annulable
//        if($this->isCancelable())
//        {
//            // on a un bouton de plus
//            $total++;
//
//            // si on ne peux pas faire d'upload
//            if($this->getStatutUpload() == FALSE)
//            {
//                // on a un bouton de plus (renvoi des fichiers)
//                $total++;
//            }
//        }
//
//        // on renvoi le nombre de bouton
//        return $total;
//    }

    /**
     * Verifie si un fichier a deja ete envoye depuis un element du panier
     * @param PanierContent $panierContent	Un element du panier
     * @return boolean	TRUE si fichier recu (devis avec "option sur commande" ou maquette UD), FALSE sinon
     */
//    public static function checkFileAlreadySentFrom(PanierContent $panierContent)
//    {
//        $fileAlreadySent = FALSE;
//
//        // si la commande vient d'un devis "option sur commmande" ou si le client a choisi une maquette UniversDesign
//        if(preg_match('#.*(Option sur commande).*#', $panierContent->getPanConProduitDescription()) || $panierContent->getIdDesignerUser() > 0)
//        {
//            $fileAlreadySent = TRUE;
//        }
//
//        return $fileAlreadySent;
//    }


    /**
     * Utilisation des credits du panier
     * @param PanierContent $panierContent	Element du panier
     * @return float Prix final apres deduction des credits utilises
     */
//    private function _useCreditFromPanierContent(PanierContent $panierContent)
//    {
//        $passStatusReglAuto = TRUE;
//
//        // calcul du prix de vente avant facile crédit bonus
//        $productsPrice = $panierContent->calculPrix()->getMontant(Prix::PRIXHT);
//
//        // calcul du prix de vente aprés facile crédit bonus
//        $finalPrice = $productsPrice;
//
//        // si le client à utiliser des crédits bonus (on ajoute un centime pour ne pas avoir de probléme d'arrondi)
//        if($panierContent->getPanConCreditBonus() >= 0.01)
//        {
//            // recuperation des credits disponibles et utilises dans l'element du panier
//            $creditsDispo = $this->getCustomer()->getCreditsBonusDispos()->getMontant();
//
//            // si le client possede un nombre de credits suffisant (on ajoute un centime pour ne pas avoir de probléme d'arrondi)
//            if($creditsDispo + 0.1 >= $panierContent->getPanConCreditBonus())
//            {
//                // calcul du prix final (prix d'origine du produit - nombre de credits utilises auquel on a enlever la tva de cette ligne du panier)
//                $finalPrice = $productsPrice - Prix::RetireTVA($panierContent->getPanConCreditBonus(), $panierContent->getPanier()->getPanTaxTva(), Prix::PRIXTTC);
//
//                // creation d'un mouvement pour l'utilisation des credits du client
//                TCreditMouvement::createNew($this->getCustomer()->getCustomersId(), $panierContent->getPanConCreditBonus() * -1, TCreditMouvementType::PASSAGE_COMMANDE, null, $this->getOrdersId());
//            }
//            // sinon le client n'a plus assez de credits
//            else
//            {
//                // ne pas passer automatiquement en statut regle
//                $passStatusReglAuto = FALSE;
//
//                // envoi d'un mail d'avertissement dans l'admin
//                $this->addHistory('Plus assez de crédits bonus disponibles (' . $creditsDispo . ') pour payer cette commande (' . $productsPrice . ') avec ' . $panierContent->getPanConCreditBonus() . ' ' . System::getCurrentHost()->getHosCreditName() . '.', 0, 0, '', '', 'Le client');
//
//                // remise a zero du nombre de credits utilises dans le panier
//                $panierContent->setPanConCreditBonus(0);
//                $panierContent->save();
//            }
//        }
//
//        // si le client à utiliser des crédits bonus
//        if($panierContent->getPanConCreditStandard() >= 0.01)
//        {
//            // recuperation des credits disponibles et utilises dans l'element du panier
//            $creditsDispo = $this->getCustomer()->getCreditsStandardsDispos()->getMontant();
//
//            // si le client possede un nombre de credits suffisant
//            if($creditsDispo + 0.1 >= $panierContent->getPanConCreditStandard())
//            {
//                // création de la date
//                $date = new DateHeure();
//
//                // on créé le réglement et on l'associe à la commande
//                $reglement = ReglementsClients::createNew($panierContent->getPanConCreditStandard(), TReglementTypeClient::TYPE_FACILE_CREDIT, '', '', TUser::ID_ROBOT_LGI, $date->format(DateHeure::DATETIMEMYSQL), TCurrencies::ID_FACILE_CREDIT);
//                RegCliCmd::createNew($this->getOrdersId(), $reglement->getIdReg(), $panierContent->getPanConCreditStandard());
//            }
//            // sinon le client n'a plus assez de credits
//            else
//            {
//                // ne pas passer automatiquement en statut regle
//                $passStatusReglAuto = FALSE;
//
//                // envoi d'un mail d'avertissement dans l'admin
//                $this->addHistory('Plus assez de crédits Standard disponibles (' . $creditsDispo . ') pour payer cette commande avec ' . $panierContent->getPanConCreditBonus() . ' ' . System::getCurrentHost()->getHosCreditName() . '.', 0, 0, '', '', 'Le client');
//
//                // remise a zero du nombre de credits utilises dans le panier
//                $panierContent->setPanConCreditStandard(0);
//                $panierContent->save();
//            }
//        }
//
//        // si on a un reglement
//        if(isset($reglement))
//        {
//            // calcul du montant restant à payer
//            $resteAPayer = $finalPrice - Prix::RetireTVA($reglement->getMontantReg(), $panierContent->getPanier()->getPanTaxTva(), Prix::PRIXTTC);
//        }
//        // pas de réglement
//        else
//        {
//            // il reste a payer le final price
//            $resteAPayer = $finalPrice;
//        }
//
//        // si le prix a payer moins le reglement en FC est null (on vérifie inférieur à 1 cent sinon on peux avoir des problèmes d'arrondi)
//        if($resteAPayer < 0.01)
//        {
//            // si la commande est en attendte de paiement ou en pre suppression
//            if($this->getOrdersStatus() == OrdersStatus::STATUS_PAIEMENT_ATTENTE || $this->getOrdersStatus() == OrdersStatus::STATUS_PRE_SUPPRESSION)
//            {
//                // on passe le statut en fichier recu
//                $this->updateStatus(OrdersStatus::STATUS_FICHIERS_RECUS, 'Commande payée intégralement avec des crédits.', 0, '', '', 'Le client');
//            }
//            // la commande n'etait pas en attente de paiement
//            else
//            {
//                $this->addHistory('Commande payée intégralement avec des crédits.', 0, 0, '', '', 'Le client');
//            }
//        }
//
//        return array('finalPrice' => $finalPrice, 'passStatusReglAuto' => $passStatusReglAuto);
//    }
    /**
     * renvoi l'objet selectionfournisseur lié à cette commande ou le créé is il n'existe pas
     * @return SelectionFournisseur
     */
//    public function getSelectionFournisseur()
//    {
//        // on a pas encore recupéré la selection
//        if($this->_selectionFournisseur == null)
//        {
//            // on la récupére
//            $this->_selectionFournisseur = SelectionFournisseur::findOrCreateForOrder($this->getOrdersId());
//        }
//
//        return $this->_selectionFournisseur;
//    }


    /**
     * Obtenir le site de livraison suivant le type de client et si c'est un produit "marque blanche"
     */
//    public function getSiteLivraisonList()
//    {
//        // Ce n'est pas un client standard ou  produit marque blanche
//        if($this->isMarqueBlanche() || $this->getCustomer()->getRevendeur() != 0)
//        {
//            return 'NEUTRE';
//        }
//        // Site LGI, Fusion, etc
//        else
//        {
//            return $this->getCustomer()->getSiteHost();
//        }
//    }


    /**
     * Permet de recuprer l'etat d'avancement du bat
     * @return int etat d'avancement du bat ou 0 si n'existe pas
     */
//    public function getBatStatus()
//    {
//        // 0 = PAS DE BAT, 1 = BAT valide, 2 = BAT en cours de creation, 3 = BAT en attente validation,
//        $batStatus = 0;
//
//        // si le BAT existe
//        if($this->checkBatExists())
//        {
//            // BAT valide par defaut
//            $batStatus = 1;
//            switch($this->getOrdersStatus())
//            {
//                // BAT EN COURS DE CREATION
//                case 1 :
//                case 2 :
//                case 11 :
//                case 17 :
//                case 18 :
//                case 24 :
//                case 31 :
//                case 47 :
//                case 48 :
//                case 55 :
//                case 63 :
//                    $batStatus	 = 2;
//                    break;
//                // ATTENTE BAT CLIENT (Attente de validation client)
//                case 13 :
//                case 49 :
//                    $batStatus	 = 3;
//                    break;
//                // DEFAUT
//                default : break;
//            }
//        }
//        return $batStatus;
//    }


    /**
     * Obtenir le libelle de l'etat d'avancement d'un BAT a partir du statut du BAT
     * @param int $batStatus statut du BAT
     * @return string libelle etat avancement du BAT
     */
//    public static function getBatStatusLibelleFrom($batStatus)
//    {
//        $batStatusLibelle	 = '';
//        $batStatusList		 = array(
//            0	 => 'Pas de BAT',
//            1	 => 'Validé',
//            2	 => 'En cours de création',
//            3	 => 'En attente de validation'
//        );
//        if(isset($batStatusList[$batStatus]))
//        {
//            $batStatusLibelle = $batStatusList[$batStatus];
//        }
//        return $batStatusLibelle;
//    }

    /**
     * Verifie si un BAT existe ou pas pour les differents sites LGI et Fusion
     * @return boolean bat existe ou pas
     */
//    public function checkBatExists()
//    {
//        // recherche BAT pour Fusion
//        $ordersProducts = $this->getOrdersProducts();
//        // si on trouve dans le nom du produit : "B.A.T. électronique" ou "B.A.T. &eacute;lectronique"
//        if($ordersProducts !== null && preg_match('#B.A.T.\s(?:électronique|&eacute;lectronique)#', $ordersProducts->getProductsName()))
//        { // le BAT Fusion existe
//            return TRUE;
//        }
//
//        return FALSE;
//    }
    /**
     * Cette fonction permet de creer une ligne dans la table order history pour cette commande
     * @param string $comments			Message a ajouter dans l'historique
     * @param int $ordersStatusId		Identifiant du statut ou 0 pour ne pas changer
     * @param int $modeEnvoiDuMail		=ordersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI mode d'envoi du mail voir les constante ordersStatusHistory::TYPE_ENVOI_MAIL_...
     * @param string $sujetMail			Sujet du mail
     * @param string $mailEnvoye		Corp du mail
     * @param string $login				Nom de l'utilisateur ou une chaine vide pour prendre l'utilisateur connecte a l'admin
     * @param type $numCdeFournisseur	Numero de commande fournisseur
     * @param int $importantComment		=0 mettre 1 si le commentaire est important
     * @param array $pj					=array() un tableau pour les piéces jointes
     * @param int|null $idEmailtodbEmail =null id de l'email si cette historique est lié à un webmail
     */
//    public function addHistory($comments, $ordersStatusId = 0, $modeEnvoiDuMail = OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI, $sujetMail = '', $mailEnvoye = '', $login = '', $numCdeFournisseur = '', $importantComment = 0, $pj = array(), $idEmailtodbEmail = null)
//    {
//        // initialisation de la variable qui gére l'envoi des mail pour les cas ou on n'a pas de mail
//        $mailSent = TRUE;
//
//        // on récupére le login de l'admin connecte si besoin
//        $loginUser = $this->_recupAdminLogin($login);
//
//        // si un utilisateur est connecte dans l'admin
//        if(System::getUserConnected())
//        {
//            // on ajoute les nombres de points realises par rapport aux objectifs si on en a
//            TUserObjectifPoint::gainCommandeCommercial($this->getOrdersId(), $modeEnvoiDuMail, System::getUserConnected()->getIdUser());
//        }
//
//        // si on a des piéce jointe
//        if(isset($pj[0]))
//        {
//            $mailPieceJointe = $pj[0]['nomDernierDossierFichierPieceJointe'];
//        }
//        // pas de piéce jointe
//        else
//        {
//            $mailPieceJointe = null;
//        }
//
//        // suivant le mode d'envoi des mails
//        switch($modeEnvoiDuMail)
//        {
//            // si le client ne veux pas envoyer de mail
//            case OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI:
//                // on supprime les eventuel sujet et corp du mail
//                $sujetMail	 = '';
//                $mailEnvoye	 = '';
//
//                // pas de piéce jointe
//                $mailPieceJointe = null;
//
//                break;
//
//            // pour le cas de l'envoi spécial qui ou ne doit pas envoyer le mail
//            case OrdersStatusHistory::TYPE_ENVOI_MAIL_SPECIAL:
//                // on ne fait rien
//                break;
//
//            // pour le cas de l'envoi standard avec attente
//            case OrdersStatusHistory::TYPE_ENVOI_MAIL_STANDARD:
//                // on envoi le mail
//                $mailSent = $this->envoiMail($sujetMail, $mailEnvoye, $pj, TRUE);
//
//                break;
//
//            // pour le cas de l'envoi standard sans attente
//            case OrdersStatusHistory::TYPE_ENVOI_MAIL_STANDARD_SANS_ATTENTE:
//                // on envoi le mail sans attente
//                $mailSent = $this->envoiMail($sujetMail, $mailEnvoye, $pj, FALSE);
//
//                // on ne fait rien
//                break;
//
//            // le mode d'envoi du mail de staut n'est pas implémenté dans addHistory, uniquement dans updateStatus
//            case OrdersStatusHistory::TYPE_ENVOI_MAIL_MAIL_DU_STATUT:
//                // on commence par sauvegarder la langue par défaut actuel
//                $langueDefaut = DbTable::getLangDefaut();
//
//                // on change la langue par défaut pour prendre celle du site de la client afin de localiser le mail
//                DbTable::setLangDefaut($this->getCustomer()->getSiteHost()->getCodeLangue());
//
//                // on récupére le statut que l'on veux changer
//                $futurStatut = OrdersStatus::findById($ordersStatusId);
//
//                // remplacement des variables contenues dans l'objet et le corps du mail
//                $aReplaceVariable	 = Template::replaceVariableMultiple(array($futurStatut->getOrdersStatusSujet(), $futurStatut->getOrdersStatusMail()), array('order' => $this));
//                $sujetMail			 = $aReplaceVariable[0];
//                $mailEnvoye			 = $aReplaceVariable[1];
//
//                // on remet la langue par défaut comme avant
//                DbTable::setLangDefaut($langueDefaut);
//
//                // on envoi le mail
//                $mailSent = $this->envoiMail($sujetMail, $mailEnvoye, $pj, TRUE);
//
//                break;
//
//            default:
//                // on génére un log d'erreur
//                $log = TLog::initLog('Erreur d\envoi de mail dans l\'ajout d\'historique.');
//                $log->Erreur('Commande ' . $this->getOrdersId());
//                $log->Erreur('Mode d\'envoi du mail : ' . $modeEnvoiDuMail);
//                $log->addLogContent('Mode non reconnu');
//                break;
//        }
//
//        // si on a eu un soucis pendant l'envoi du mail
//        if(!$mailSent)
//        {
//            // on passe le commentaire en important
//            $importantComment = 1;
//
//            // on ajoute au commentaire
//            $comments = '<br>MAIL NON ENVOYE SUITE A UNE ERREUR !';
//        }
//
//        // insertion dans la base de donnees
//        OrdersStatusHistory::createNew($this->getOrdersId(), $ordersStatusId, $comments, $importantComment, $loginUser, $sujetMail, $mailEnvoye, $mailPieceJointe, $numCdeFournisseur, $idEmailtodbEmail);
//    }
    /**
     * envoi un mail a toutes les adresses du client
     * @param string $sujet sujet du mail
     * @param string $corp_mail corp du mail
     * @param array $pj	=array() un tableau pour les piéces jointes
     * @param bool $attente =TRUE doit-on attendre une seconde aprés chaque envoi de mail ?
     */
//    public function envoiMail($sujet, $corp_mail, $pj = array(), $attente = TRUE)
//    {
//        // si on a pas de sujet ou de corp de mail
//        if(trim($sujet) == '' || trim($corp_mail) == '')
//        {
//            // on créé un log
//            $log = TLog::initLog('Tentative d\'envoi d\'un mail vide.');
//            $log->Erreur('commande : ' . $this->getOrdersId());
//            $log->Erreur('sujet : ' . $sujet);
//            $log->Erreur('corp : ' . $corp_mail);
//
//            // on renvoi FALSE
//            return FALSE;
//        }
//
//        // gestion des destinataire
//        $aNom	 = array($this->getCustomersName());
//        $aMail	 = array($this->getCustomersEmailAddress());
//
//        // si le client posséde une 2e adresse
//        if($this->getCustomersEmailAddress2())
//        {
//            // ajout de la 2e adresse
//            $aNom[]	 = $this->getCustomersName();
//            $aMail[] = $this->getCustomersEmailAddress2();
//        }
//
//        // si le client posséde une 3e adresse
//        if($this->getCustomersEmailAddress3())
//        {
//            // ajout de la 2e adresse
//            $aNom[]	 = $this->getCustomersName();
//            $aMail[] = $this->getCustomersEmailAddress3();
//        }
//
//        // envoi du mail a la premiére adresse du client
//        $mailSent = Mail::sendSimpleMail($aNom, $aMail, $sujet, $corp_mail, $this->getCustomer()->getSiteHost()->getHostNom(), $this->getCustomer()->getSiteHost()->getMailInfo(), $pj, '', TRUE);
//
//        // si on doit attendre
//        if($attente)
//        {
//            // on attend 1s pour ne pas saturer les serveur mail des FAI comme orange
//            sleep(1);
//        }
//
//        return $mailSent;
//    }

    /**
     * Procedure mettant a jour le statut de la commande et creant un historique
     * @param int $status				Nouveau statut de la commande
     * @param string $comments			Message a ajouter dans l'historique
     * @param int $modeEnvoiDuMail		=ordersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI mode d'envoi du mail voir les constante ordersStatusHistory::TYPE_ENVOI_MAIL_...
     * @param string $sujetMail			Sujet du mail
     * @param string $mailEnvoye		Corp du mail
     * @param string $login				Nom de l'utilisateur ou une chaine vide pour prendre l'utilisateur connecte a l'admin
     * @param string $numCdeFournisseur	Numero de commande fournisseur
     * @param string $idDossier			Id du dossier de fichier client ou '' pour ne pas faire de changement
     * @param int $typeFichier			null pas de changement, 0 : ancienne version, 1 : fichier client, 2 : designer, 3 : choix non effectue
     * @param int $importantComment		=0 mettre 1 si le commentaire est important
     * @param array $pj					=array() un tableau pour les piéces jointes
     */
//    public function updateStatus($status, $comments = 'Mise &agrave jour du statut', $modeEnvoiDuMail = OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI, $sujetMail = '', $mailEnvoye = '', $login = '', $numCdeFournisseur = '', $idDossier = '', $typeFichier = null, $importantComment = 0, $pj = array())
//    {
//        // on récupére le login de l'admin connecte si besoin
//        $loginUser = $this->_recupAdminLogin($login);
//
//        // date actuelle
//        $dateHeureNow = new DateHeure();
//
//        // si on doit envoyé le mail automatiquement
//        if($modeEnvoiDuMail == OrdersStatusHistory::TYPE_ENVOI_MAIL_MAIL_DU_STATUT)
//        {
//            // on commence par sauvegarder la langue par défaut actuel
//            $langueDefaut = DbTable::getLangDefaut();
//
//            // on change la langue par défaut pour prendre celle du site de la client afin de localiser le mail
//            DbTable::setLangDefaut($this->getCustomer()->getSiteHost()->getCodeLangue());
//
//            // on récupére le statut que l'on veux changer
//            $futurStatut = OrdersStatus::findById($status);
//
//            // remplacement des variables contenues dans l'objet et le corps du mail
//            $aReplaceVariable	 = Template::replaceVariableMultiple(array($futurStatut->getOrdersStatusSujet(), $futurStatut->getOrdersStatusMail()), array('order' => $this));
//            $sujetMail			 = $aReplaceVariable[0];
//            $mailEnvoye			 = $aReplaceVariable[1];
//
//            // on remet la langue par défaut comme avant
//            DbTable::setLangDefaut($langueDefaut);
//
//            // on passe le type d'envoi du mail en standard
//            $modeEnvoiDuMail = OrdersStatusHistory::TYPE_ENVOI_MAIL_STANDARD;
//        }
//
//        // si la commande passe en status controle fichier FR ou M ou 48/72
//        if($status == OrdersStatus::STATUS_CONTORLE_FICHIER_48_72_FR || $status == OrdersStatus::STATUS_CONTORLE_FICHIER_FR || $status == OrdersStatus::STATUS_CONTORLE_FICHIER_M)
//        {
//            // on supprime si besoin les fichier depart fab sur stationserveur et on ajoute un message
//            $comments .= Stationserveur::deleteProductionFileForOrder($this);
//        }
//
//        // si un utilisateur est connecte dans l'admin
//        if(System::getUserConnected())
//        {
//            // recupere le produit lie a la commande
//            $aOrdersProducts = array_values($this->getProducts());
//            // on ajoute les nombres de points réalisé par rapport aux objectifs si on en a
//            if(count($aOrdersProducts) > 0)
//            {
//                TUserObjectifPoint::gainCommandeGraphiste($this->getOrdersStatus(), $status, $this->getOrdersId(), System::getUserConnected()->getIdUser(), $aOrdersProducts[0]->getProductsName());
//            }
//        }
//
//        // si la commande doit passer en statut 7-EN FABRICATION et qu'elle est "non-réglé"
//        if($status == OrdersStatus::STATUS_FABRICATION && !$this->isFullPaid())
//        {
//            // on change le statut pour 7.1 V.A EN FABRICATION
//            $status		 = OrdersStatus::STATUS_FABRICATION_VA;
//            // ajout d'un message
//            $comments	 .= '<br />Commande passée automatiquement en statut 7.1 VA en Fab &agrave; la place de "en fabrication"';
//        }
//
//        // si la commande doit passer en statut LIVRAISON et qu'elle est "non-réglé"
//        if($status == OrdersStatus::STATUS_EXPEDITION && !$this->isFullPaid())
//        {
//            // on change le statut pour 7.1 V.A EN FABRICATION
//            $status		 = OrdersStatus::STATUS_EXPEDITION_VA;
//            // ajout d'un message
//            $comments	 .= '<br />Commande passée automatiquement en statut VA en Expedition &agrave; la place de "en livraison"';
//        }
//
//        // si la commande doit passer en statut LIVRE ou RECLA TRAITE ou ERREUR DE LIVRAISON et qu'elle est "non-réglé"
//        if(($status == OrdersStatus::STATUS_LIVRE || $status == OrdersStatus::STATUS_RECLA_TRAITE || $status == OrdersStatus::STATUS_LIVRAISON_ERREUR) && !$this->isFullPaid())
//        {
//            // on change le statut pour 7.1 V.A EN FABRICATION
//            $status		 = OrdersStatus::STATUS_LIVRE_VA;
//            // ajout d'un message
//            $comments	 .= '<br />Commande passée automatiquement en statut VA en Livré &agrave; la place de "livre" ou "recla traité"';
//        }
//
//        // si la commande doit passer en statut SUPPRESSION et qu'elle posséde un réglement
//        if($status == OrdersStatus::STATUS_SUPPRESSION && count($this->getReglementClient()) > 0)
//        {
//            // on la passera en statut annulation à la place
//            $status = OrdersStatus::STATUS_ANNULATION;
//
//            // message d'erreur
//            $erreur		 = 'Commande passée en ANNULATION au lieu de SUPPRESSION car elle posséde un réglement.';
//            $comments	 .= '<br />' . $erreur;
//            FlashMessages::add(FlashMessages::TYPE_ALERT, $erreur);
//        }
//
//        // si la commande doit passer en statut ANNULATION et qu'elle ne posséde aucun réglement
//        if($status == OrdersStatus::STATUS_ANNULATION && count($this->getReglementClient()) < 1)
//        {
//            // on la passera en statut SUPPRESSION à la place
//            $status = OrdersStatus::STATUS_SUPPRESSION;
//
//            // message d'erreur
//            $erreur		 = 'Commande passée en SUPPRESSION au lieu de ANNULATION car elle ne posséde pas de réglement.';
//            $comments	 .= '<br />' . $erreur;
//            FlashMessages::add(FlashMessages::TYPE_ALERT, $erreur);
//        }
//
//        // si la commande passe en depart fab a
//        if($status == OrdersStatus::STATUS_DEPART_FAB_A)
//        {
//            // création d'un objet fournisseur de la selection
//            $fournisseur = fournisseur::findByIdWithChildObject($this->getSelectionFournisseur()->getIdFour());
//
//            // on met a jour la date de fabrication dans la selection
//            $this->getSelectionFournisseur()->setDateFournisseur($dateHeureNow->format(DateHeure::DATETIMEMYSQL))
//                ->save();
//
//            // si le fournisseur de la selection ne devrait pas avoir de selection (ou si on a pas de selection)
//            if($fournisseur->getOrdreSelection() <= 0)
//            {
//                // récupération de l'erreur
//                $erreur = TMessage::findById(TMessage::ERR_ADM_BAD_SUPPLIER_SELECT);
//
//                // on change le statut
//                $status = OrdersStatus::STATUS_DEPART_FAB_RETOUR;
//
//                // on ajoute l'erreur dans les commentaire et en flash message
//                $comments .= '<br />' . $erreur->getMesText();
//                FlashMessages::add(FlashMessages::TYPE_ERREUR, $erreur->getMesText());
//            }
//            // si le fichier n'est pas disponible et qu'on arrive pas à le copier
//            elseif(!$fournisseur->fichierDisponible($this->getOrdersId()) && !$fournisseur->copyProductionFileToSupplierFtp($this))
//            {
//                // on change le statut (inutile de mettre un message il est déjà mis par copyProductionFileToSupplierFtp();
//                $status = OrdersStatus::STATUS_DEPART_FAB_RETOUR;
//
//                // ajout de commentaire
//                $comments .= '<br />Modification automatique de statut à la place de DEPART FAB A car le fichier est indisponible.';
//            }
//        }
//
//        // si la commande passe en depart fab auto et qu'on a une seul commande fournisseur lié
//        if(($status == OrdersStatus::STATUS_DEPART_FAB_AUTO || $status == OrdersStatus::STATUS_DEPART_FAB_A) && count($this->_aOrderSupplierOrderWithActivSupplier()) == 1)
//        {
//            // pour chaque commande fournisseur lié
//            foreach($this->_aOrderSupplierOrderWithActivSupplier() as $orderSupplierOrder)
//            {
//                // passage de la commande fournisseur en lancement auto
//                $orderSupplierOrder->getSupplierOrder()->setIdSupplierOrderStatus(TSupplierOrderStatus::ID_STATUS_AUTO_LAUNCH)
//                    ->save();
//            }
//        }
//
//        // mise à jour du statut
//        $this->setOrdersStatus($status);
//
//        // mise à jour de la date de derniere modification
//        $this->setLastModified($dateHeureNow->format(DateHeure::DATETIMEMYSQL));
//
//        // si la commande n'est pas facture et qu'on veut la passer en statut 7 en fab ou en statut 6.3 depart fab auto
//        if($this->getStatusFact() <> 1 && ($status == OrdersStatus::STATUS_FABRICATION || $status == OrdersStatus::STATUS_DEPART_FAB_AUTO || $status == OrdersStatus::STATUS_FABRICATION_VA))
//        {
//            // on passe la commande en statut facture
//            $this->generateInvoice();
//            $comments .= '<br />Facture finale générée';
//        }
//
//        // si on doit mettre a jour l'id de dossier
//        if($idDossier <> '')
//        {
//            $this->setIdDossier($idDossier);
//            $comments .= '<br />Version de fichier modifiée pour : ' . $idDossier;
//        }
//
//        // si on doit mettre a jour le type de fichier
//        if($typeFichier <> null)
//        {
//            $this->setTypeFichier($typeFichier);
//        }
//
//        /**
//         *  si la commande passe en livré
//         */
//        if($status == OrdersStatus::STATUS_LIVRE)
//        {
//            // on gére les mouvement de crédits et on ajoute les éventuel commentaire
//            $comments .= TCreditMouvement::orderDelivered($this);
//        }
//
//        // sauvegarde de notre objet pour appliquer
//        $this->save();
//
//        // ajout d'un message dans l'historique
//        $this->addHistory($comments, $status, $modeEnvoiDuMail, $sujetMail, $mailEnvoye, $loginUser, $numCdeFournisseur, $importantComment, $pj);
//
//        // suivant le status on va mettre a jour la note du client.
//        // une fois qu'une note a ete maj pour cette commande elle ne sera plus jamais mise a jour
//        switch($status)
//        {
//            case OrdersStatus::STATUS_ERREUR_FICHIER :
//                $this->updateNote(-5);
//                break;
//            case OrdersStatus::STATUS_FABRICATION :
//            case OrdersStatus::STATUS_FABRICATION_VA :
//            case OrdersStatus::STATUS_LIVRE :
//                $this->updateNote(10);
//                break;
//            default:
//                break;
//        }
//    }
    /**
     * Renvoi les commande fournisseur lié à notre objet dont le fournisseur est actif
     * @return TAOrderSupplierOrder[]
     */
//    private function _aOrderSupplierOrderWithActivSupplier()
//    {
//        $return = array();
//
//        // pour chaque commande fournisseur
//        foreach($this->getAOrderSupplierOrder() as $key => $orderSupplierOrder)
//        {
//            // si le fournisseur est actif
//            if($orderSupplierOrder->getSupplierOrder()->getSupplier()->isActive())
//            {
//                // on l'ajoute
//                $return[$key] = $orderSupplierOrder;
//            }
//        }
//
//        return $return;
//    }


    /**
     * récupére le login admin si on en a pas
     * @param string $loginUser le login ou une chaine vide si il faut le récupéré
     * @return string le login
     */
//    private function _recupAdminLogin($loginUser)
//    {
//        // si aucun uilisateur n'a ete specifie et qu'on a un utilisateur connecté
//        if($loginUser == '' && System::getUserConnected())
//        {
//            // on utilise l'utilisateur qui est connecte
//            $loginUser = System::getUserConnected()->getUsLogin();
//        }
//
//        return $loginUser;
//    }


    /**
     * Met à jour la méthode de paiement
     * @param   String   $methode
     */
//    public function updateMethodePaiement($methode, $user = 'Le client')
//    {
//        $sql = 'update orders set payment_method="' . $methode . '" where orders_id=' . $this->getOrdersId();
//        DB::req($sql);
//
//        // on ajotue un historique
//        $this->addHistory('Modification de la méthode de paiement : ' . $methode, 0, 0, '', '', $user);
//    }


    /**
     * Marque une commande comme en livraison, change son statut et envoi le mail au client SI la commande n'est pas en staut livré
     * @param string $nomFournisseur nom du fournisseur
     * @param Array $texteColis texte à ajouter dans le mail contenant les références pour suivre le colis
     * @param string $commentaireHistorique commentaire qui peux être ajouter à l'historique de commande
     * @param string $user ='Robot LGI' le nom de l'utilisateur qui a mis en livraison
     */
//    public function setAsLivraison($nomFournisseur, $texteColis = null, $commentaireHistorique = '', $user = 'Robot LGI')
//    {
//        // ajout ou maj des infos du colis dans la table
//        TOrdersLivraison::createOrUpdateByTexteColis($this->getOrdersId(), $texteColis);
//
//        // commentaire de l'historique de commande
//        $commentaire = 'Commande Expédié par ' . $nomFournisseur . '<br />';
//
//        // si on a un numéro de colis
//        if(is_array($texteColis) && isset($texteColis['transporteur']))
//        {
//            $commentaire .= 'Transporteur : ' . $texteColis['transporteur'] . '<br />';
//        }
//
//        // si on a un numéro de colis
//        if(is_array($texteColis) && isset($texteColis['numColis']) && !is_array($texteColis['numColis']))
//        {
//            $commentaire .= 'Colis : ' . $texteColis['numColis'] . '<br />';
//        }
//
//        // si on a plusieurs numéros de colis
//        if(is_array($texteColis) && isset($texteColis['numColis']) && is_array($texteColis['numColis']))
//        {
//            // pour chaque numéro de colis
//            foreach($texteColis['numColis'] AS $numColis)
//            {
//                $commentaire .= 'Colis : ' . $numColis . '<br />';
//            }
//        }
//
//        $commentaire .= '<br />' . $commentaireHistorique;
//
//        // on regarde si la commande est en statut livré
//        if($this->getOrdersStatus() == OrdersStatus::STATUS_LIVRE || $this->getOrdersStatus() == OrdersStatus::STATUS_LIVRE_VA || $this->getOrdersStatus() == OrdersStatus::STATUS_EXPEDITION || $this->getOrdersStatus() == OrdersStatus::STATUS_EXPEDITION_VA)
//        {
//            // dans ce cas on va uniquement ajouté un historique
//            $this->addHistory($commentaire . '<br />Mail d&#039;expédition non envoyé car la commande est en statut livré ou expédition.', 0, 0, '', '', $user);
//        }
//        // la commande n'est pas en statut livré c'est bon
//        else
//        {
//            // si on a un txte pour le colis on l'ajoute
//            if(isset($texteColis) && $texteColis != null)
//            {
//                // initialisation du template pour les texte de colis
//                $tplTextShipping = new Template;
//
//                // assignation des infos de colis
//                $tplTextShipping->assign('texteColis', $texteColis);
//
//                // récupération du texte
//                $textShipping = $tplTextShipping->fetch('mail/expedition.tpl', null, null, null, FALSE, TRUE, FALSE, TRUE);
//
//                // on met le texte dans la commande
//                $this->setTextShipping($textShipping);
//            }
//
//            //On met à jour le statut et on ajoute un historique
//            $this->updateStatus(OrdersStatus::STATUS_EXPEDITION, $commentaire, OrdersStatusHistory::TYPE_ENVOI_MAIL_MAIL_DU_STATUT, '', '', $user);
//        }
//    }


    /**
     * Marque une commande comme livré, change son statut et envoi le mail au client SI la commande est en expédition ou en fabrication
     * @param string $commentaire le commentaire éventuel a mettre dans la commande
     * @param string $user l'utilisateur qui a fait le changement de statut
     */
//    public function setAsDelivered($commentaire = '', $user = 'Robot LGI')
//    {
//        // on regarde si la commande est dans un statut ou on va passé la commande en livré
//        if($this->getOrdersStatus() == OrdersStatus::STATUS_FABRICATION || $this->getOrdersStatus() == OrdersStatus::STATUS_FABRICATION_VA || $this->getOrdersStatus() == OrdersStatus::STATUS_EXPEDITION || $this->getOrdersStatus() == OrdersStatus::STATUS_EXPEDITION_VA)
//        {
//            // On met à jour le statut et on ajoute un historique et on informe le client
//            $this->updateStatus(OrdersStatus::STATUS_LIVRE, $commentaire, OrdersStatusHistory::TYPE_ENVOI_MAIL_MAIL_DU_STATUT, null, null, $user);
//        }
//        // autre statut
//        else
//        {
//            // dans ce cas on va uniquement ajouté un historique
//            $this->addHistory($commentaire . '<br />Mail de livraison non envoyé à cause du statut actuel de la commande.', 0, 0, '', '', $user);
//        }
//    }


    /**
     * Cette procedure met a jour la note du client par rapport a cette commande
     * La maj ne se fait que si aucune maj de note n'est lie a ctte commande
     * @param int $note	Valeur de modification de la note
     */
//    public function updateNote($note)
//    {
//        $customer = $this->getCustomer();
//        // si il n'y a pas encore de note lie a cette commande
//        if(!$customer->noteOrderExiste($this->getOrdersId()))
//        {
//            // mise à jour de la note du client
//            $customer->updateNote('Commande ' . $this->getOrdersId(), $note, 0, 'Robot LGI', $this->getOrdersId());
//        }
//    }


    /**
     * Cette fonction va créer un raglement fournisseur et le lié à la commande en cours
     * @param int $idFour id id du fournisseur
     * @param int $idTypeReg id du type de réglement
     * @param float $montantTTC montant TTC
     * @param string $reference référence (ex: numéro de commande fournisseur)
     */
//    public function addRegFour($idFour, $idTypeReg, $montantTTC, $reference = '', $intitule = '')
//    {
//        // création du reglement fournisseur
//        $reglement = ReglementsFournisseurs::createNew($idFour, $intitule, $reference, $idTypeReg, $montantTTC);
//
//        // ajout du lien entre le réglement et la commande
//        RegFourCmd::createNew($reglement->getIdReg(), $this->getOrdersId(), $montantTTC);
//    }


    /**
     * Cette fonction renvoi le nombre de réglement client associé à notre commande
     * @return int le nombre de réglement client
     */
//    public function countReglementClient()
//    {
//        return count($this->getReglementClient());
//    }


    /**
     * cette fonction vérifie que les réglement ayant servi à payer cette commande on servi uniquement à cette commande
     * @return boolean TRUE si tous les paiement on payé cette commande ou si aucun paiement n'existe
     */
//    public function reglementClientUniquementCetteCommande()
//    {
//        // pour chaque réglement associé à notre commande
//        foreach($this->getReglementClient() AS $reglement)
//        {
//            // si ce réglement à payer plusieurs commande
//            if(count($reglement->getOrders()) > 1)
//            {
//                return FALSE;
//            }
//        }
//
//        // on a tester tous les réglements aucun n'a servi sur d'autre commande
//        return TRUE;
//    }


    /**
     * Cette fonction renvoi le nombre de réglement fournisseur associé à notre commande
     * @return int le nombre de réglement fournisseur
     */
//    public function haveReglementFournisseur()
//    {
//        return RegFourCmd::existByIdOrder($this->getOrdersId());
//    }


    /**
     * indique si le tracking sera viaible par le client
     * @return boolean TRUE si il est visible par le client et FALSE sinon
     */
//    public function haveTrackingForCustomer()
//    {
//        // si on a des colis visible par le client
//        if(count($this->getLivraisons(TRUE)) >= 1)
//        {
//            // on a des choses à afficher au client
//            return TRUE;
//        }
//
//        // rien à afficher au client
//        return FALSE;
//    }

    /**
     * retourne l'url de la facture de cette commande
     * @return string
     */
//    public function getUrlFacture($download = FALSE)
//    {
//        // url de base de la facture
//        $url = HTTP_PROTOCOL . '://' . $this->getCustomer()->getSiteHost()->getAdresseWww() . '/static.php?module=generation_document&action=imprime_pdf_facture&id_o_c=' . $this->getCryptedId();
//
//        // si on veux téléchargé la facture
//        if($download)
//        {
//            // on ajoute un paramétre
//            $url .= '&download=1';
//        }
//
//        return $url;
//    }


    /**
     * retourne l'url du tracking de cette commande
     * @return string
     */
//    public function urlTracking()
//    {
//        return HTTP_PROTOCOL . '://' . $this->getCustomer()->getSiteHost()->getAdresseWww() . '/static.php?module=tracking&action=order_shipping_detail&id-order-c=' . $this->getCryptedId();
//    }


    /**
     * return true si on peux uploader des fichier et false si des fichiers on était uploader
     * @return type bool
     */
//    public function getStatutUpload()
//    {
//        if($this->getOrdersStatus() == 11 || $this->getOrdersStatus() == 17)
//        {
//            return TRUE;
//        }
//        else
//        {
//            return FALSE;
//        }
//    }


    /**
     * Dans le cas des adresses de livraison/facturation hors france renvoi un tableaux contenant des messages de livraison
     * @return array
     */
//    public function adresseHorsFrance()
//    {
//        // Initialisation de notre variable de retour
//        $retour = array();
//
//        // on récupére les departement de livraison et facturation
//        $deliveryPostcode		 = $this->getDeliveryPostcode();
//        $billingPostcode		 = $this->getBillingPostcode();
//        $departementLivraison	 = substr($deliveryPostcode, 0, 2);
//        $departementFacturation	 = substr($billingPostcode, 0, 2);
//
//        // suivant le code postal de livraison
//        switch($deliveryPostcode)
//        {
//            case 17410:
//            case 17111:
//            case 17580:
//            case 17590:
//            case 17630:
//            case 17670:
//            case 17740:
//            case 17880:
//            case 17940:
//                $retour[]	 = 'Livraison Ile de Ré';
//                break;
//            case 17190:
//            case 17310:
//            case 17370:
//            case 17480:
//            case 17550:
//            case 17650:
//            case 17840:
//                $retour[]	 = 'Livraison Ile d\'Oléron';
//                break;
//            case 56360:
//            case 56390:
//                $retour[]	 = 'Livraison Belle-Île-en-Mer';
//                break;
//            case 56170:
//                $retour[]	 = 'Livraison Ile d\'houat';
//                break;
//            case 56590:
//                $retour[]	 = 'Livraison Ile de groix';
//                break;
//            case 85350:
//                $retour[]	 = 'Livraison Ile d\'Yeu';
//                break;
//            case 29242:
//                $retour[]	 = 'Livraison Ile d\'Ouessant';
//                break;
//            case 29259:
//                $retour[]	 = 'Livraison Ile Moléne';
//                break;
//            case 29990:
//                $retour[]	 = 'Livraison Île-de-Sein';
//                break;
//            default:
//                break;
//        }
//
//        // suivant le departement de livraison
//        switch($departementLivraison)
//        {
//            case 20:
//                $retour[]	 = 'Livraison en Corse';
//                break;
//            case 97:
//            case 98:
//                $retour[]	 = 'Livraison dans les DOM-TOM';
//                break;
//            default:
//                break;
//        }
//
//        // suivant le departement de facturation
//        switch($departementFacturation)
//        {
//            case 97:
//            case 98:
//                $retour[] = 'Facturation dans les DOM-TOM';
//                break;
//            default:
//                break;
//        }
//
//        // livraison a l'etranger : si on a un code de pays pour l'adresse du pays qui n'est pas le code pour la France
//        if(is_numeric($this->getDeliveryCountryCode()) && $this->getDeliveryCountryCode() != Countries::ID_FR)
//        {
//            $retour[] = 'Livraison à l\'étranger';
//        }
//        // si le code postal de livraison ne commence pas par un chiffre ou ne contient que 4 caracteres
//        else if(preg_match('#^\D#', $deliveryPostcode) || preg_match('#^.{4}$#', $deliveryPostcode))
//        {
//            $retour[] = 'Livraison à l\'étranger';
//        }
//
//        // on renvoi notre tableau
//        return $retour;
//    }
//
    /**
     * Recupere le message a afficher lorsqu'on a des adresses hors France
     * @return string	Le message pour les adresses hors France
     */
//    public function messageAdresseHorsFrance()
//    {
//        $messageAdresseHorsFrance = '';
//
//        // on recupere les messages des adresses hors France
//        $aAdresseHorsFrance = $this->adresseHorsFrance();
//        foreach($aAdresseHorsFrance as $adresseHorsFrance)
//        {
//            $messageAdresseHorsFrance .= $adresseHorsFrance . "<br />";
//        }
//
//        return $messageAdresseHorsFrance;
//    }


    /**
     * renvoi true si la commande est une commande fluoo (compte de dominique)
     * @return bool
     */
//    public function isFluoo()
//    {
//        // si on est sur le compte de Dominique ou celui de Patrice ou celui de Nathalie
//        if($this->getCustomersId() == 7968 || $this->getCustomersId() == 47131 || $this->getCustomersId() == 80714)
//        {
//            return TRUE;
//        }
//        else
//        {
//            return FALSE;
//        }
//    }


    /**
     * assigne une maquette à notre commande
     * @param int $idmaquette
     * @return boolean flase en cas d'erreur
     */
//    public function setMaquetteMyDesign($idmaquette, $viaAdmin = null)
//    {
//        // on charge l'objet maquette
//        $maquetteClient = TDesignerUserFile::findById($idmaquette);
//
//        // la maquette n'existe pas ou plus
//        if($maquetteClient->getIdDesignerUserFile() == null)
//        {
//            FlashMessages::add(FlashMessages::TYPE_ERREUR, 'Cette maquette n\'existe pas.');
//            return FALSE;
//        }
//
//        //Si on vient de l'admin
//        if($viaAdmin === null)
//        {
//            // la maquette n'appartient pas à ce client
//            if($maquetteClient->getIdCustomer() <> $this->getCustomersId())
//            {
//                FlashMessages::add(FlashMessages::TYPE_ERREUR, 'Cette maquette ne vous appartient pas.');
//                return FALSE;
//            }
//        }
//
//        // copie de la maquette dans le dossier depart fab
//        $maquetteClient->generatePdfHdToFile('/data/fichiers_clients/a_envoyer/' . $this->getOrdersId() . '/maquette-univers-design.pdf');
//
//        // maj de l'objet
//        $this->setTypeFichier(2)
//            ->setIdDesignerUser($maquetteClient->getIdDesignerUserFile())
//            ->save();
//
//        // maj du statut
//        $this->updateStatus(OrdersStatus::STATUS_FICHIERS_RECUS, 'Le client à choisi la maquette univers design ' . $maquetteClient->getIdDesignerUserFile(), 0, '', '', 'Le client');
//
//        return FALSE;
//    }


    /**
     * ajoute une proposition d'un fournisseur pour cette commande
     * @param int $idFournisseur id du fournisseur
     * @param flaot $montant montant de la porposition
     * @param int $statut statut de la proposition 1 accepté, 0 pas interessé
     * @return TOrdersPropositionFournisseur la proposition
     */
//    public function addPropositionFournisseur($idFournisseur, $montant, $statut = 1)
//    {
//        // on reinitialise le tableau des propositions fournisseurs
//        $this->_propositionsFournisseurs = null;
//
//        // on renvoi la nouvelle proposition
//        return TOrdersPropositionFournisseur::createNew($this->getOrdersId(), $idFournisseur, $montant, $statut);
//    }


    /**
     * renvoi toutes les propositions fournisseurs pour cette commande.
     * @return TOrdersPropositionFournisseur
     */
//    public function getPropositionsFournisseurs()
//    {
//        // si on a pas encore récupéré les propositions des fournisseurs
//        if($this->_propositionsFournisseurs == null)
//        {
//            // on les récupére
//            $this->_propositionsFournisseurs = TOrdersPropositionFournisseur::findAllForOrder($this->getOrdersId());
//        }
//
//        return $this->_propositionsFournisseurs;
//    }


    /**
     * getteur du tableau des sous objet DateHeure des dates de livrraison lié à notre objet
     * @return DateHeure[]
     */
//    public function getADeliveryDate()
//    {
//        // on a pas encore chercher
//        if($this->_aDeliveryDate === null)
//        {
//            $this->_aDeliveryDate = array();
//
//            // pour chaque commande lié
//            foreach($this->getAOrderSupplierOrder() as $orderSupplierOrder)
//            {
//                // si le fournisseur indique de ne pas afficher la date de livraison
//                if(!$orderSupplierOrder->getSupplierOrder()->getSupplier()->showDelivzery())
//                {
//                    // on passe à la commande fournisseur suivante
//                    continue;
//                }
//
//                // on récupére la date, on la met en clef pour ne pas avoir plusieurs fois la même
//                $this->_aDeliveryDate[$orderSupplierOrder->getDeliveryDate()->format(DateHeure::DATEMYSQL)] = $orderSupplierOrder->getDeliveryDate();
//            }
//
//            // on tri
//            ksort($this->_aDeliveryDate);
//        }
//
//        return $this->_aDeliveryDate;
//    }


    /**
     * getteur du tableau des sous objet DateHeure des dates de livrraison sécurisé lié à notre objet
     * @return DateHeure[]
     */
//    public function aDeliveryDateSecure()
//    {
//        $return = array();
//
//        // pour chaque date de livraison
//        foreach($this->getADeliveryDate() as $deliveryDate)
//        {
//            // on ajoute la date sécurisé
//            $return[] = DateHeure::jPlusX(5, $deliveryDate);
//        }
//
//        return $return;
//    }

    /**
     * renvoi la dderniere proposition d'un fournisseur ou FALSE si on en a pas
     * @param int $idFournisseur id du fournisseur
     * @return TOrdersPropositionFournisseur|boolean
     */
//    public function lastPropositionFournisseur($idFournisseur)
//    {
//        // pour chaque proposition fournisseur
//        foreach($this->getPropositionsFournisseurs() AS $proposition)
//        {
//            // si la proposition est une proposition de notre fournisseur
//            if($proposition->getIdFournisseurs() == $idFournisseur)
//            {
//                // on renvoi la proposition
//                return $proposition;
//            }
//        }
//
//        // on a eu aucune proposition qui corresponde à ce fournisseur
//        return FALSE;
//    }


    /**
     * renvoi le staut de ces propositions pour un fournisseur
     * @param fournisseur $fournisseur l'objet du fournisseur
     * @return string "" aucune proposition, "accepter" la proposition est accepté, "refuser" la proposition est refuser, "non_interesse" si le fournisseur n'est pas intéréssé par cette commande
     */
//    public function getStatutPropositionsForFournisseur($fournisseur)
//    {
//        // si on a pas encore récupéré le statut par rapport à cette propostion fournisseur
//        if(!isset($this->_statutPropositionsForFournisseur[$fournisseur->getIdFour()]))
//        {
//            // par défaut pas de statut
//            $this->_statutPropositionsForFournisseur[$fournisseur->getIdFour()] = '';
//
//            // on récupére la proposition du fournisseur
//            $proposition = $this->lastPropositionFournisseur($fournisseur->getIdFour());
//
//            // le fournisseur n'est pas intéréssé par la commande
//            if($proposition && $proposition->getOrdProFouStatut() == 0)
//            {
//                // on affichera un fond rouge
//                $this->_statutPropositionsForFournisseur[$fournisseur->getIdFour()] = 'non_interesse';
//            }
//            // si le prix d'achat est supérieur ou égale à la proposition
//            elseif($proposition && round($this->getPrixAchat()->getMontant() * ((100 - $fournisseur->getPourcentageRemise()) / 100), 2) >= $proposition->getOrdProFouMontant())
//            {
//                // la commande est accepter
//                $this->_statutPropositionsForFournisseur[$fournisseur->getIdFour()] = 'accepter';
//            }
//            // la proposition est trop chére
//            elseif($proposition)
//            {
//                // on affichera un fond rouge
//                $this->_statutPropositionsForFournisseur[$fournisseur->getIdFour()] = 'refuser';
//            }
//        }
//
//        // on renvoi le statut
//        return $this->_statutPropositionsForFournisseur[$fournisseur->getIdFour()];
//    }


    /**
     * retourne le login de la graphiste en charge de la commande ou non défini si aucune graphiste n'est actuellement sur la commande
     * @return string
     */
//    public function getGraphisteOuNonDefini()
//    {
//        if($this->getGraphiste() == '')
//        {
//            return 'Non Défini';
//        }
//        else
//        {
//            return $this->getGraphiste();
//        }
//    }
    /**
     * utilise les crédits disponible pour cette commande
     * @return bool true en cas de succés et false en cas de probléme
     */
//    public function useCredit()
//    {
//        $error = false;
//
//        // si le prix restant à payer de la commande est inférieur ou égale à 0
//        if($this->isFullPaid())
//        {
//            // ajout d'un message
//            FlashMessages::addByIdMessage(TMessage::ERR_ORDER_ALREADY_PAID);
//
//            // on indique une erreur
//            $error = true;
//        }
//
//        // si la commande est facturée
//        if($this->getStatusFact())
//        {
//            // ajout d'un message
//            FlashMessages::addByIdMessage(TMessage::ERR_CREDITS_ON_INVOICE);
//
//            // on indique une erreur
//            $error = true;
//        }
//
//        // le client n'a plus de crédits
//        if($this->getCustomer()->getCreditsTotauxDispos()->getMontant() <= 0)
//        {
//            // ajout d'un message
//            FlashMessages::addByIdMessage(TMessage::ERR_CREDITS_INSUFFICIENT);
//
//            // on indique une erreur
//            $error = true;
//        }
//
//        // si on a des d'erreur
//        if($error == true)
//        {
//            // on quitte la fonction
//            return false;
//        }
//
//        // si le montant de crédit bonus dispo est suffisant pour payer l'intégralité de la commande
//        if($this->getCustomer()->getCreditsBonusDispos()->getMontant() >= $this->prixRestantAPayer(TRUE)->getMontant(Prix::PRIXTTC))
//        {
//            // création du mouvement
//            TCreditMouvement::createNew($this->getCustomer()->getCustomersId(), $this->prixRestantAPayer(TRUE)->getMontant(Prix::PRIXTTC) * -1, TCreditMouvementType::PASSAGE_COMMANDE, null, $this->getOrdersId());
//
//            // calcul du nouveau prix de la commande (si on avait un réglement avant d'utiliser les FC Bonus
//            $nouveauPrixCommande = clone $this->getPrix();
//            $nouveauPrixCommande->soustractPrix($this->prixRestantAPayer(TRUE));
//
//            // mise à jour du montant de la commande
//            $this->updateMontant($nouveauPrixCommande)
//                ->save();
//
//            System::getUserConnected();
//
//            // si la commande est en attendte de paiement ou en pre suppression
//            if($this->getOrdersStatus() == OrdersStatus::STATUS_PAIEMENT_ATTENTE || $this->getOrdersStatus() == OrdersStatus::STATUS_PRE_SUPPRESSION)
//            {
//                // on passe le statut en fichier recu
//                $this->updateStatus(OrdersStatus::STATUS_FICHIERS_RECUS, 'Commande payée intégralement avec des crédits.', 0, '', '', $this->adminLoginOrCustomer());
//            }
//            // la commande n'était pas en attente de paiement
//            else
//            {
//                $this->addHistory('Commande payée intégralement avec des crédits.', 0, 0, '', '', $this->adminLoginOrCustomer());
//            }
//
//            return true;
//        }
//
//        // on récupére le montant de crédit Bonus
//        $montantCreditBonusUtilise = $this->getCustomer()->getCreditsBonusDispos()->getMontant();
//
//        // si on a des crédits bonus
//        if($montantCreditBonusUtilise >= 0.01)
//        {
//            // création du mouvement
//            TCreditMouvement::createNew($this->getCustomer()->getCustomersId(), $montantCreditBonusUtilise * -1, TCreditMouvementType::PASSAGE_COMMANDE, null, $this->getOrdersId());
//
//            // mise à jour du montant de la commande
//            $this->updateMontant($this->getPrix()->updateMontant(1, $montantCreditBonusUtilise * -1, Prix::PRIXTTC));
//
//            // ajout d'un historique
//            $this->addHistory('Commande payée en partie avec ' . $montantCreditBonusUtilise . ' crédits bonus.', 0, 0, '', '', $this->adminLoginOrCustomer());
//        }
//
//        // utilisation des crédits standard
//        $this->useCreditStandard();
//
//        return true;
//    }


    /**
     * utilise les crédit standard du client si il en posséde
     */
//    private function useCreditStandard()
//    {
//        // récupération des crédit standard disponible pour le client
//        $montantCreditStandardDispo = $this->getCustomer()->getCreditsStandardsDispos()->getMontant();
//
//        // si on a pas assez de crédit standard
//        if($montantCreditStandardDispo < 0.01)
//        {
//            // on quitte la fonction
//            return TRUE;
//        }
//
//        // si le montant de crédit standard dispo est suffisant pour payer l'intégralité de la commande
//        if($montantCreditStandardDispo >= $this->prixRestantAPayer(TRUE)->getMontant(Prix::PRIXTTC))
//        {
//            // on créé le réglement
//            $reglement = ReglementsClients::createNew($this->prixRestantAPayer(TRUE)->getMontant(Prix::PRIXTTC), TReglementTypeClient::TYPE_FACILE_CREDIT, '', '', TUser::ID_ROBOT_LGI, '', TCurrencies::ID_FACILE_CREDIT);
//            RegCliCmd::createNew($this->getOrdersId(), $reglement->getIdReg(), $this->prixRestantAPayer(TRUE)->getMontant(Prix::PRIXTTC));
//
//            // si la commande est en attendte de paiement ou en pre suppression
//            if($this->getOrdersStatus() == OrdersStatus::STATUS_PAIEMENT_ATTENTE || $this->getOrdersStatus() == OrdersStatus::STATUS_PRE_SUPPRESSION)
//            {
//                // on passe le statut en fichier recu
//                $this->updateStatus(OrdersStatus::STATUS_FICHIERS_RECUS, 'Commande payée intégralement avec ' . $this->prixRestantAPayer(TRUE)->getMontant(Prix::PRIXTTC) . ' crédits standards.', 0, '', '', $this->adminLoginOrCustomer());
//            }
//            // la commande n'était pas en attente de paiement
//            else
//            {
//                $this->addHistory('Commande payée intégralement avec ' . $this->prixRestantAPayer(TRUE)->getMontant(Prix::PRIXTTC) . ' crédits standards.', 0, 0, '', '', $this->adminLoginOrCustomer());
//            }
//
//            return TRUE;
//        }
//
//        // on créé le réglement
//        $reglement = ReglementsClients::createNew($montantCreditStandardDispo, TReglementTypeClient::TYPE_FACILE_CREDIT, '', '', TUser::ID_ROBOT_LGI, '', TCurrencies::ID_FACILE_CREDIT);
//        RegCliCmd::createNew($this->getOrdersId(), $reglement->getIdReg(), $montantCreditStandardDispo);
//
//        // ajout d'un historique
//        $this->addHistory('Commande payée en partie avec ' . $montantCreditStandardDispo . ' crédits standards.', 0, 0, '', '', $this->adminLoginOrCustomer());
//    }
    /**
     * met à jour le montant de la commande
     * @param Prix $prix le nouveau Prix
     * @param int $typePrix le type de prix
     * @return $this notre objet
     */
//    private function updateMontant(Prix $prix)
//    {
//        // on réinitialise le prix
//        $this->_prix = $prix;
//
//        // mise à jour du final price dans les produits
//        $this->getOrdersProducts()->setFinalPrice($prix->getMontant())
//            ->save();
//
//        // on renvoi notre objet
//        return $this;
//    }


    /**
     * Mettre a jour les differents elements de la commande et autre lorsqu'elle est payee
     * @param ReglementsClients $reglement  Le reglement client
     * @param bool $passStatusReglAuto      [=TRUE] Passer en statut regle automatiquement
     * @param string $source                [="inconnu"] Source du paiement (retour banque ou retour client)
     */
//    public function paidByPayline($reglement, $passStatusReglAuto = TRUE, $source = 'inconnu')
//    {
//        // on lit le réglement à la commande
//        RegCliCmd::createNew($this->getOrdersId(), $reglement->getIdReg(), $this->prixRestantAPayer()->getMontant(Prix::PRIXTTC));
//
//        // passer automatiquement en statut regle
//        if($passStatusReglAuto === TRUE)
//        {
//            // on passe la commande en statut facturée
//            $this->generateInvoice();
//        }
//        $this->save();
//
//        // passer automatiquement en statut regle
//        if($passStatusReglAuto === TRUE)
//        {
//            // si la commande est en attendte de paiement ou en pre suppression
//            if($this->getOrdersStatus() == OrdersStatus::STATUS_PAIEMENT_ATTENTE || $this->getOrdersStatus() == OrdersStatus::STATUS_PRE_SUPPRESSION)
//            {
//                // on passe le statut en fichier recu
//                $this->updateStatus(OrdersStatus::STATUS_FICHIERS_RECUS, 'Paiement CB effectué (' . $source . ') : la commande était en attente de paiment on la passe en fichier reçu.', 0, '', '', 'Le client');
//            }
//            // la commande n'était pas en attente de paiement
//            else
//            {
//                $this->addHistory('Paiement CB effectué (' . $source . ') : Pas de changement de statut.', 0, 0, '', '', 'Le client');
//            }
//        }
//    }


    /**
     * renvoi true si cette commande est en attente de paiement
     * une commande en attente de paiement est une commande non réglé et pas dans le statut suppression
     * @return boolean
     */
//    public function attentePaiement()
//    {
//        // si la commande est réglé ou si elle est e suppression ou si le prix est inférieur ou égale à 0
//        if($this->isFullPaid() || $this->getOrdersStatus() == OrdersStatus::STATUS_SUPPRESSION || $this->getOrdersStatus() == OrdersStatus::STATUS_ANNULATION || $this->getPrix()->getMontant() <= 0)
//        {
//            return FALSE;
//        }
//        // commande toujours en attente de paiement
//        else
//        {
//            return TRUE;
//        }
//    }


    /**
     * Est ce que la commande peux être recommandé
     * @return boolean
     */
//    public function reorderable()
//    {
//        // on vérifie si le produit lgi lié à l'id de produit existe
//        return Products::existById($this->getOrdIdProductLgi());
//    }


    /**
     * objet Prix du montant restant à payer pôur cette commande
     * @param bool $notNull =FALSE mettre TRUE transformera tous les prix inférieur ou égale à 1 centimes (donc tous les prix négatif) en un prix à 0
     * @return Prix
     */
//    public function prixRestantAPayer($notNull = FALSE)
//    {
//        // on commence par récupérer le prix de notre commande (FC déduit)
//        $prixRestantAPayer = clone $this->getPrix();
//
//        // pour chaque réglement de la commande
//        foreach($this->getReglementClient() AS $reglement)
//        {
//            // on récupére le prix du réglement
//            $prixReglement = $reglement->prixPourCommande($this);
//
//            // on retire le prix du reglement à la commande
//            $prixRestantAPayer->soustractPrix($prixReglement);
//        }
//
//        // si on ne veux pas de prix null et que notre prix est inférieur ou égale à 1 centimes
//        if($notNull && $prixRestantAPayer->getMontant() <= 0.01)
//        {
//            // on met le prix à 0
//            $prixRestantAPayer->setMontant(0);
//        }
//
//        return $prixRestantAPayer;
//    }


    /**
     * indique le montant restant à payer aprés avoir retirer les avoir lié à cette commande
     * @param bool $abs =FALSE mettre TRUE si on veux la valeur absolu donc toujours positive
     */
//    public function priceToPayAfterCreditNote($abs = FALSE)
//    {
//        // on récupére le prix à payer hors avoir
//        $return = $this->prixRestantAPayer();
//
//        // on récupére un eventuel avoir lié à notre commande
//        $creditNote = Avoir::findByOrderId($this->getOrdersId());
//
//        // si on a un avoir
//        if($creditNote != null)
//        {
//            // on retire le montant de l'avoir à notre prix
//            $return->soustractPrix($creditNote->getPrix());
//        }
//
//        // si on a un prix négatif et qu'on veux une valeur absolu
//        if($abs && $return->getMontant() < 0)
//        {
//            // on inverse le prix
//            $return->setMontant($return->getMontant() * -1);
//        }
//
//        return $return;
//    }


    /**
     * indique si l'avis pour cette commande ne sera jamais envoyé
     * @return boolean TRUE si l'avis ne sera jamais envoyé FALSE sinon
     */
//    public function avisSentNever()
//    {
//        // si la date d'envoi de l'avis correspond à la date pour ne jamais l'envoyé
//        if($this->getOrdAvisSendingDate() == self::AVIS_NEVER_SENT_DATE)
//        {
//            return TRUE;
//        }
//        else
//        {
//            return FALSE;
//        }
//    }


    /**
     * L'avis a était envoyé au site d'avis
     * @return boolean
     */
//    public function avisSent()
//    {
//        if($this->avisSentNever() || $this->getOrdAvisSendingDate() == null)
//        {
//            return FALSE;
//        }
//        else
//        {
//            return TRUE;
//        }
//    }


    /**
     * renvoi l'url de la fiche technique ou null si on a pas de fiche technique
     * @return string|null
     */
//    public function ficheTechnique()
//    {
//        // si on a pas d'id de produit lgi
//        if($this->getOrdIdProductLgi() != null)
//        {
//            // on récupére le produit
//            $produitLgi = Products::findById(array($this->getOrdIdProductLgi()));
//
//            // si le produit ou la catégorie sont inactif
//            if($produitLgi->getActive() != 1 || $produitLgi->getCategorie() == FALSE || $produitLgi->getCategorie()->getActive() != 1)
//            {
//                // pas de fiche technique
//                return null;
//            }
//
//            // on renvoi l'url de la fiche technique
//            return 'http://' . $this->getCustomer()->getSiteHost()->constructAdresseWeb() . '/' . $produitLgi->getCategorie()->getAccessUrl() . '#categorie-conseil';
//        }
//
//        // si on a pas d'id de produit host fusion
//        if($this->getOrdIdProduitHost() == null)
//        {
//            // pas de fiche technique
//            return null;
//        }
//
//        // on récupére le produit fusion
//        $produitHost = TProduitHost::findById(array($this->getOrdIdProduitHost(), $this->getCustomer()->getSiteHost()->getHostProduct()));
//
//        // si le produit n'est pas actif
//        if(!$produitHost->isActif())
//        {
//            // pas de fiche technique
//            return null;
//        }
//
//        // On renvoi la maquette en volume ou null si on ne la trouve pas
//        return $produitHost->chercheMaquette($this->getOrdSelection(), $this->getCustomer()->getSiteHost()->getHostId());
//    }


    /**
     * Est ce que c'est une commande FREE PAO
     * @return boolean
     */
//    public function isFreePao()
//    {
//
//        // recherche pour Free PAO
//        $ordersProducts = $this->getOrdersProducts();
//
//        // si on trouve dans le nom du produit : "CREATION GRATUITE"
//        return ($ordersProducts !== null && preg_match('#CREATION GRATUITE#', $ordersProducts->getProductsName()));
//    }


    /**
     * Retourne le numero de la solution de FREE PAO
     * @return int
     */
//    public function getFreePaoNumSolution()
//    {
//        $matches = array();
//
//        // recherche BAT pour Free PAO
//        $ordersProducts = $this->getOrdersProducts();
//
//        // si on trouve dans le nom du produit : "B.A.T. électronique" ou "B.A.T. &eacute;lectronique"
//        if($ordersProducts !== null && preg_match('#SOLUTION.([123])#', $ordersProducts->getProductsName(), $matches))
//        {
//            // La solution est la
//            if(isset($matches[1]))
//            {
//                return $matches[1];
//            }
//            else
//            {
//                return 0;
//            }
//        }
//
//        return 0;
//    }


    /**
     * Retourne les ID de la solution 1 pour la free PAO...
     * @return type
     */
//    public function getHistoryFreePaoUd()
//    {
//        $matches = array();
//        $aRep	 = array();
//
//        $aHistory = (OrdersStatusHistory::findAllForOrder($this->ordersId));
//        krsort($aHistory);
//
//        foreach($aHistory as $h)
//        {
//            $comment = $h->getComments();
//
//            if(preg_match('#FreePAO : Suppression Gabarit UD pour la solution \d#', $comment, $matches))
//            {
//
//                return array();
//            }
//            elseif(preg_match('#FreePAO : Gabarit UD pour la solution \d : (([\da-zA-Z]+\-?){0,3})#', $comment, $matches))
//            {
//                return explode('-', $matches[1]);
//            }
//        }
//
//        return $aRep;
//    }


    /**
     * créé un webmail de demande de facture papier
     * @return boolean TRUE en cas de succés et FALSE en cas de probléme
     */
//    public function createWebmailForPaperInvoice()
//    {
//        // si on n'a pas l'option facture papier
//        if(!$this->haveOption(ProductOption::OPTION_ID_PAPER_INVOICE))
//        {
//            // on ne fait rien
//            return TRUE;
//        }
//
//        // remplacement des variables Smarty contenus dans l'objet et le corps du mail
//        $tpl	 = new Template();
//        $tpl->assign('order', $this);
//        $message = $tpl->fetch('mail/send-paper-invoice.tpl', null, null, null, FALSE, TRUE, FALSE, TRUE);
//
//        // on créé un nouveau webmail
//        EmailtodbEmail::createNew($this->getCustomer()->getCustomersEmailAddress(), $this->getCustomer()->getNomComplet(), $this->getCustomer()->getSiteHost()->getMailInfo(), null, 'Facture papier pour la commande ' . $this->getOrdersId(), mb_strlen($message), $this->getCustomer()->getHost(), 0, '', $message);
//
//        // ajout d'un message
//        FlashMessages::addByIdMessage(TMessage::SUC_SAMPLES_AUTO_MAIL);
//
//        return TRUE;
//    }


    /**
     * vérifie si notre commande posséde une option complémentaire spécifique
     * @param int $idOption id de l'option complémentaire
     * @return boolean TRUE si l'option est présente FALSE sinon
     */
//    public function haveOption($idOption)
//    {
//        // pour chaque option lié à la commande
//        foreach($this->getAOptions() AS $option)
//        {
//            // si c'est l'option cherché
//            if($option->getIdOptions() == $idOption)
//            {
//                // on a trouvé
//                return TRUE;
//            }
//        }
//
//        return FALSE;
//    }


    /**
     * vérifie si notre commande posséde une facture chorus
     * @return boolean TRUE si une facture chorus est présente FALSE sinon
     */
//    public function haveChorusInvoice()
//    {
//        // si on a au moins une facture chorus
//        if(count($this->getAChorusInvoice()) > 0)
//        {
//            return TRUE;
//        }
//
//        return FALSE;
//    }


    /**
     * renvoi un objet prix pour l'accés fournisseur
     * @param fournisseur $supplier le fournisseur
     * @return \Prix
     */
//    public function fournisseurPrice(fournisseur $supplier)
//    {
//        return new Prix(floor($this->getPrixAchat()->getMontant() * ((100 - $supplier->getPourcentageRemise()) / 100)));
//    }


    /**
     * utilise les fichiers d'une autres commande pour cette commande
     * @param order $oldOrder l'ancienne commande
     */
//    public function useOldOrderFiles(order $oldOrder)
//    {
//        // on enregistre dans la table prévu pour
//        OrdersFilesOldOrders::createNew($this->getOrdersId(), $oldOrder->getOrdersId());
//
//        // on change de statut la commande
//        $this->updateStatus(OrdersStatus::STATUS_FILES_IN_TRANSFER, 'Utilisation des fichiers de la commande "' . $oldOrder->getOrdersId() . '".', OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI, '', '', '', '', '', 1);
//    }
//
//
    /**
     * cette fonction gére tout ce qui est nécessaire pour la facturation
     * Elle va sauvegarder notre objet
     */
//    public function generateInvoice()
//    {
//        // si la commande est déjà facturé
//        if($this->getStatusFact() == 1)
//        {
//            // on ne fait rien
//            return TRUE;
//        }
//
//        // on passe la commande en facturé
//        $this->setStatusFact(1)
//            ->save();
//
//        // gestion des factures papier si besoin
//        $this->createWebmailForPaperInvoice();
//
//        // si le client a besoin d'une facture chorus
//        if($this->getCustomer()->isOnChorus())
//        {
//            // si on n'a pas encore facturé sur chorus
//            if(TChorusInvoice::countByIdOrder($this->getOrdersId()) < 1)
//            {
//                // création de la facture chorus
//                TChorusInvoice::createNew($this->getOrdersId(), $this->getCustomer()->getSiret(), $this->getCustomer()->getCusChorusService());
//
//                // ajout de l'urgence
//                TEmergencyWork::createNew($this->getOrdersId(), TEmergencyWork::ID_TYPE_ORDER, TUser::ID_ROBOT_LGI, 16);
//
//                // on envoi pour prévenir qu'il faut faire la facturation Chorus
//                Mail::sendSimpleMail('govaertsd@gmail.com', 'govaertsd@gmail.com', 'Facturation Chorus ' . $this->getOrdersId(), 'La facturation Chorus Pro est à réaliser pour la commande ' . $this->getOrdersId(), $this->getCustomer()->getSiteHost()->getMailInfo(), $this->getCustomer()->getSiteHost()->getMailInfo());
//            }
//        }
//
//        return TRUE;
//    }


    /**
     * Renvoi les numéro de version du dernier fichier présent chez chaque fournisseur
     * @return type
     */
//    public function outsourceFileVersion()
//    {
//        $return = array();
//
//        // pour chaque sous traitant
//        foreach(TOutsource::findAll() as $outsource)
//        {
//            // on créé la ligne pour ce sous traitant
//            $return[$outsource->getIdOutsource()]['name']		 = $outsource->getOutName();
//            $return[$outsource->getIdOutsource()]['idDossier']	 = FALSE;
//            $return[$outsource->getIdOutsource()]['type']		 = $outsource->getOutType();
//            $return[$outsource->getIdOutsource()]['icone']		 = $outsource->getOutIcone();
//        }
//
//        // pour chaque fichier présent chez un sous traitant pour cette commande
//        foreach(TOutsourceFile::maxOutsourceFileVersionByIdOrder($this->getOrdersId()) as $fileVersion)
//        {
//            // on met à jour le numéro de version
//            $return[$fileVersion['id_outsource']]['idDossier'] = $fileVersion['last_id_dossier'];
//        }
//
//        return $return;
//    }


    /**
     * Renvoi un tableau des icone de tous les sous traitant chez qui le fichier a été envoyé
     * @return array
     */
//    public function iconeOutsourceWithFileSended()
//    {
//        $return = array();
//
//        // récupération des derniéres version des fichiers de la commande
//        $allOutsourceFile = $this->outsourceFileVersion();
//
//        // si on n'a pas de fichier sur stationserveur
//        if(!isset($allOutsourceFile[TOutsource::ID_STATIONSERVEUR]) || $allOutsourceFile[TOutsource::ID_STATIONSERVEUR]['idDossier'] == FALSE)
//        {
//            // il n'y aura de fichier nulle part
//            return $return;
//        }
//
//        // derniere version de référence (stationserveur)
//        $outsourceFileReference = $allOutsourceFile[TOutsource::ID_STATIONSERVEUR];
//
//        // pour chaque fichier outsource
//        foreach($allOutsourceFile as $idOutsource => $outsourceFile)
//        {
//            // si on est sur le sous traitant de référence
//            if($idOutsource == TOutsource::ID_STATIONSERVEUR)
//            {
//                // on ne le traite pas
//                continue;
//            }
//
//            // si la version de fichier est la même que celle de référence
//            if($outsourceFile['idDossier'] == $outsourceFileReference['idDossier'])
//            {
//                // on ajoute ce sous traitant au retour
//                $return[$outsourceFile['name']] = $outsourceFile['icone'];
//            }
//        }
//
//        return $return;
//    }


    /**
     * Renvoi la liste des noms de fichiers normalement présent pour cette commande
     * @param bool $checkAvailable [=FALSE] mettre TRUE si on veux vérifier la disponibilité des fichiers
     * @return string[]|FALSE une liste de nom de fichier ou FALSE si on n'a pas d'information
     */
//    public function filesList($checkAvailable = FALSE)
//    {
//        // on récupére la liste des fichiers de la selection
//        return $this->getSelectionFournisseur()->filesList($this->getOrdersId(), $checkAvailable);
//    }


    /**
     * Renvoi le login de l'utilisateur admin connécté si il y en a un sinon renvoi "Le client"
     * @return string
     */
//    public function adminLoginOrCustomer()
//    {
//        // récupération d'un eventuel utilisateur admin connécté
//        $user = System::getUserConnected();
//
//        // si on a un utilisateur
//        if($user != null)
//        {
//            // on renvoi le login de l'utilisateur
//            return $user->getUsLogin();
//        }
//
//        return 'Le client';
//    }


    /**
     * vérifie si il y a un soucis avec les réglement par rapport aux avoir
     * @return boolean true si tout va bien et false si on a un avoir sans remboursement ou un remboursement sans avoir
     */
//    public function checkCreditNoteWithPayment()
//    {
//        // par défaut pas de réglement de remboursement
//        $haveRefund = false;
//
//        // on récupére un eventuel avoir lié à notre commande
//        $creditNote = Avoir::findByOrderId($this->getOrdersId());
//
//        // si on a un avoir
//        if($creditNote != null)
//        {
//            $haveCreditNote = true;
//        }
//        // pas d'avoir
//        else
//        {
//            $haveCreditNote = false;
//        }
//
//        // pour chaque réglement lié à notre commande
//        foreach($this->getReglementClient() as $payment)
//        {
//            // si on a un réglement négatif (réglement)
//            if($payment->getMontantReg() < 0.01)
//            {
//                $haveRefund = true;
//
//                // iutile de chercher plus loin
//                break;
//            }
//        }
//
//        // si tout est bon
//        if($haveCreditNote == $haveRefund)
//        {
//            return true;
//        }
//        // avoir ou rémboursement manquant
//        else
//        {
//            return false;
//        }
//    }


    /**
     * renvoi toutes les propositions fournisseurs active pour une commande
     * @return TOrdersPropositionFournisseur
     */
//    public function supplierOfferActive()
//    {
//        $return = array();
//
//        // pour chaque proposition fournisseur
//        foreach($this->getPropositionsFournisseurs() as $key => $supplierOffer)
//        {
//            // si la proposition est active
//            if($supplierOffer->getOrdProFouStatut() == 1)
//            {
//                // on lajoute à notre tableau de retour
//                $return[$key] = $supplierOffer;
//            }
//        }
//
//        return $return;
//    }


}
