<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAOrderSupplierOrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAOrderSupplierOrderRepository::class)]
// $_SQL_TABLE_NAME = 'lesgrand.ta_order_supplier_order';
class TAOrderSupplierOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    // id de notre commande
    private ?int $idOrder = null;

    #[ORM\Column]
    // id de la commande fournisseur
    private ?int $idSupplierOrder = null;

    #[ORM\Column]
    // ventilation du montant HT de la commande fournisseur
    private ?float $priceWithoutTax = null;

    #[ORM\Column]
    // date de la livraison estimé
    private ?\DateTimeImmutable $deliveryDate = null;

    #[ORM\Column]
    // id du job pour les commandes multiples
    private ?int $jobId = null;

    #[ORM\ManyToOne(inversedBy: 'taOrderSupplierOrders')]
    private ?TSupplierOrder $tSupplierOrder = null;

    #[ORM\ManyToOne(inversedBy: 'taOrderSupplierOrders')]
    private ?Order $tOrder = null;

    /**
     * sous objet de la commande.
     * @var order
     */
    // private $_order = null;

    /**
     * sous objet de la commande fournisseur.
     * @var TSupplierOrder
     */
    // private $_supplierOrder = null;

    /**
     * objet du prix de notre objet.
     * @var Prix
     */
    // private $_price = null;

    /**
     * objet DateHeure de la livraison estimé.

     * @return DateHeure

     */
    // private $_deliveryDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOrder(): ?int
    {
        return $this->idOrder;
    }

    public function setIdOrder(int $idOrder): static
    {
        $this->idOrder = $idOrder;

        return $this;
    }

    public function getIdSupplierOrder(): ?int
    {
        return $this->idSupplierOrder;
    }

    public function setIdSupplierOrder(int $idSupplierOrder): static
    {
        $this->idSupplierOrder = $idSupplierOrder;

        return $this;
    }

    public function getPriceWithoutTax(): ?float
    {
        return $this->priceWithoutTax;
    }

    public function setPriceWithoutTax(float $priceWithoutTax): static
    {
        $this->priceWithoutTax = $priceWithoutTax;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeImmutable
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(\DateTimeImmutable $deliveryDate): static
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    public function getJobId(): ?int
    {
        return $this->jobId;
    }

    public function setJobId(int $jobId): static
    {
        $this->jobId = $jobId;

        return $this;
    }

    public function getTSupplierOrder(): ?TSupplierOrder
    {
        return $this->tSupplierOrder;
    }

    public function setTSupplierOrder(?TSupplierOrder $tSupplierOrder): static
    {
        $this->tSupplierOrder = $tSupplierOrder;

        return $this;
    }

    /**
     * getteur pour le sous objet de la commande.
     * @return order
     */
    //    public function getOrder()
    //    {
    //        // si on n'a pas encore cherché
    //        if($this->_order == null)
    //        {
    //            $this->_order = order::findById($this->getIdOrder());
    //        }
    //
    //        return $this->_order;
    //    }

    /**
     * getteur pour le sous objet de la commande fornisseur.
     * @return TSupplierOrder
     */
    //    public function getSupplierOrder()
    //    {
    //        // si on n'a pas encore cherché
    //        if($this->_supplierOrder == null)
    //        {
    //            $this->_supplierOrder = TSupplierOrder::findById($this->getIdSupplierOrder());
    //        }
    //
    //        return $this->_supplierOrder;
    //    }

    /**
     * getteur de l'objet prix.
     * @return Prix
     */
    //    public function getPrice()
    //    {
    //        // si on n'a pas encore cherché
    //        if($this->_price == null)
    //        {
    //            $this->_price = new Prix($this->getOrdSupOrdPriceWithoutTax(), TCurrencies::ID_EURO, $this->getSupplierOrder()->getSupplier()->getTauxTva(), Prix::PRIXHT);
    //        }
    //
    //        return $this->_price;
    //    }

    /**
     * getteur de l'objet DateHeure de la livraison estimé.
     * @return DateHeure
     */
    //    public function getDeliveryDate()
    //    {
    //        // si on n'a pas encore cherché
    //        if($this->_deliveryDate == null)
    //        {
    //            $this->_deliveryDate = new DateHeure($this->getOrdSupOrdDeliveryDate());
    //        }
    //
    //        return $this->_deliveryDate;
    //    }
    // TODO Repository
    /**
     * Renvoi toutes les liaisons entre commandes et commandes fournisseur lié à une commande.
     * @return TAOrderSupplierOrder[]
     */
    //    public static function findAllByIdOrder($idOrder)
    //    {
    //        // paramétres de la requête
    //        $aFields = array('id_order');
    //        $aValue	 = array($idOrder);
    //
    //        // on renvoi les résultat
    //        return TAOrderSupplierOrder::findAllBy($aFields, $aValue);
    //    }

    /**
     * Renvoi toutes les liaisons entre commandes et commandes fournisseur lié à une commande fournisseur.
     * @return TAOrderSupplierOrder[]
     */
    //    public static function findAllByIdSupplierOrder($idSupplierOrder)
    //    {
    //        // paramétres de la requête
    //        $aFields = array('id_supplier_order');
    //        $aValue	 = array($idSupplierOrder);
    //
    //        // on renvoi les résultat
    //        return TAOrderSupplierOrder::findAllBy($aFields, $aValue);
    //    }

    // TODO Service
    /**
     * Cré un nouvel objet "TAOrderSupplierOrder" et le retourne.
     * @return TAOrderSupplierOrder Nouvel Objet inseré en base
     */
    //    public static function createNew($idOrder, $idSupplierOrder, $ordSupOrdDeliveryDate, $ordSupOrdPriceWithoutTax = 0, $ordSupOrdJobId = null)
    //    {
    //        // on créé un objet dateheure avec la date comme ca pas de probléme de format fr ou en
    //        $date = new DateHeure($ordSupOrdDeliveryDate);
    //
    //        // on créé notre nouvel objet en base
    //        $orderSupplierOrder = new TAOrderSupplierOrder();
    //        $orderSupplierOrder->setIdOrder($idOrder)
    //            ->setIdSupplierOrder($idSupplierOrder)
    //            ->setOrdSupOrdPriceWithoutTax($ordSupOrdPriceWithoutTax)
    //            ->setOrdSupOrdDeliveryDate($date->format(DateHeure::DATEMYSQL))
    //            ->setOrdSupOrdJobId($ordSupOrdJobId)
    //            ->save();
    //
    //        return $orderSupplierOrder;
    //    }
    /**
     * Renvoi un objet prix du prix d'achat avec une remise de 5%.
     * @return \Prix
     */
    //    public function priceDiscounted()
    //    {
    //        // on prend le taux de tva du fournisseur
    //        return new Prix($this->getOrdSupOrdPriceWithoutTax() * 0.95, TCurrencies::ID_EURO, $this->getSupplierOrder()->getSupplier()->getTauxTva(), Prix::PRIXHT);
    //    }

    public function getTOrder(): ?Order
    {
        return $this->tOrder;
    }

    public function setTOrder(?Order $tOrder): static
    {
        $this->tOrder = $tOrder;

        return $this;
    }
}
