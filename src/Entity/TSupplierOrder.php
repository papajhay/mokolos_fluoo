<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TSupplierOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// $_SQL_TABLE_NAME = 'lesgrand.t_supplier_order';
#[ORM\Entity(repositoryClass: TSupplierOrderRepository::class)]
class TSupplierOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    // numéro de la commande chez le fournisseur
    private ?int $supplierOrderId = null;

    #[ORM\Column(length: 255)]
    // information sur le fournisseur
    private ?string $supplierOrderInformation = null;

    #[ORM\Column(length: 255)]
    // information à destination du fournisserur
    private ?string $informationForSupplier = null;

    #[ORM\Column]
    // date de la derniére mise à jour du statut
    private ?\DateTimeImmutable $lastupdate = null;

    #[ORM\Column]
    // date heure de la derniére mise à jour du statut
    private ?\DateTimeImmutable $dateLastUpdate = null;

    #[ORM\Column]
    // ableau des sous objet DateHeure des dates de livrraison lié à notre objet
    private ?\DateTimeImmutable $aDeleveryDate = null;

    /**
     * sous objet du statut de la commande.
     * @var TSupplierOrderStatus
     */
    private $_status;

    #[ORM\OneToMany(mappedBy: 'tSupplierOrder', targetEntity: TAOrderSupplierOrder::class)]
    private Collection $taOrderSupplierOrders;

    #[ORM\ManyToOne(inversedBy: 'tSupplierOrders')]
    private ?Provider $provider = null;

    #[ORM\ManyToOne(inversedBy: 'tSupplierOrders')]
    private ?TSupplierOrderStatus $supplierOrderStatus = null;

    public function __construct()
    {
        $this->taOrderSupplierOrders = new ArrayCollection();
    }

    /**
     * tableau des sous objet TAOrderSupplierOrder lié à notre objet.
     * @var TAOrderSupplierOrder
     */
    // private $_aOrderSupplierOrder = null;

    /**
     * le sous objet SelectionFournisseur lié à notre objet.
     * @var SelectionFournisseur
     */
    // private $_supplierSelection = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupplierOrderId(): ?int
    {
        return $this->supplierOrderId;
    }

    public function setSupplierOrderId(int $supplierOrderId): static
    {
        $this->supplierOrderId = $supplierOrderId;

        return $this;
    }

    public function getSupplierOrderInformation(): ?string
    {
        return $this->supplierOrderInformation;
    }

    public function setSupplierOrderInformation(string $supplierOrderInformation): static
    {
        $this->supplierOrderInformation = $supplierOrderInformation;

        return $this;
    }

    public function getInformationForSupplier(): ?string
    {
        return $this->informationForSupplier;
    }

    public function setInformationForSupplier(string $informationForSupplier): static
    {
        $this->informationForSupplier = $informationForSupplier;

        return $this;
    }

    public function getLastupdate(): ?\DateTimeImmutable
    {
        return $this->lastupdate;
    }

    public function setLastupdate(\DateTimeImmutable $lastupdate): static
    {
        $this->lastupdate = $lastupdate;

        return $this;
    }

    public function getDateLastUpdate(): ?\DateTimeImmutable
    {
        return $this->dateLastUpdate;
    }

    public function setDateLastUpdate(\DateTimeImmutable $dateLastUpdate): static
    {
        $this->dateLastUpdate = $dateLastUpdate;

        return $this;
    }

    public function getADeleveryDate(): ?\DateTimeImmutable
    {
        return $this->aDeleveryDate;
    }

    public function setADeleveryDate(\DateTimeImmutable $aDeleveryDate): static
    {
        $this->aDeleveryDate = $aDeleveryDate;

        return $this;
    }

    /**
     * getteur du sous objet du statut de la commande.
     */
    public function getStatus(): TSupplierOrderStatus
    {
        // si on n'a pas encore chercher notre objet
        if (null === $this->_status) {
            $this->_status = TSupplierOrderStatus::findById($this->getIdSupplierOrderStatus());
        }

        return $this->_status;
    }

    /**
     * @return Collection<int, TAOrderSupplierOrder>
     */
    public function getTaOrderSupplierOrders(): Collection
    {
        return $this->taOrderSupplierOrders;
    }

    public function addTaOrderSupplierOrder(TAOrderSupplierOrder $taOrderSupplierOrder): static
    {
        if (!$this->taOrderSupplierOrders->contains($taOrderSupplierOrder)) {
            $this->taOrderSupplierOrders->add($taOrderSupplierOrder);
            $taOrderSupplierOrder->setTSupplierOrder($this);
        }

        return $this;
    }

    public function removeTaOrderSupplierOrder(TAOrderSupplierOrder $taOrderSupplierOrder): static
    {
        if ($this->taOrderSupplierOrders->removeElement($taOrderSupplierOrder)) {
            // set the owning side to null (unless already changed)
            if ($taOrderSupplierOrder->getTSupplierOrder() === $this) {
                $taOrderSupplierOrder->setTSupplierOrder(null);
            }
        }

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    public function getSupplierOrderStatus(): ?TSupplierOrderStatus
    {
        return $this->supplierOrderStatus;
    }

    public function setSupplierOrderStatus(?TSupplierOrderStatus $supplierOrderStatus): static
    {
        $this->supplierOrderStatus = $supplierOrderStatus;

        return $this;
    }

    // Todo: Repository

    /*
     * Renvoi toutes les commandes fournisseur à vérifier
     * @return TSupplierOrder[]
     */
    //    public static function findAllToCheck()
    //    {
    //        $return = array();
    //
            // récupération de tous les statut
    //        foreach(TSupplierOrderStatus::findAll() as $supplierOrderStatus)
    //        {
                // on ajoute ce statut dans le retour
    //            $return[$supplierOrderStatus->getIdSupplierOrderStatus()]['status']			 = $supplierOrderStatus;
    //            $return[$supplierOrderStatus->getIdSupplierOrderStatus()]['supplierOrders']	 = array();
    //        }
    //
            // paraméte de la requête
    //        $aFields = array('id_supplier_order_status', 'id_supplier_order_status', 'id_supplier_order_status', 'id_supplier_order_status', 'id_supplier_order_status');
    //        $aValue	 = array(array(TSupplierOrderStatus::ID_STATUS_PRE_ORDER, '<>'), array(TSupplierOrderStatus::ID_STATUS_DISPATCHED, '<>'), array(TSupplierOrderStatus::ID_STATUS_PRODUCTION, '<>'), array(TSupplierOrderStatus::ID_STATUS_UNKNOWN, '<>'), array(TSupplierOrderStatus::ID_STATUS_CANCELED, '<>'));
    //        $order	 = array('id_supplier', 'sup_ord_last_update DESC');
    //
            // pour chaque commande fournisseur
    //        foreach(TSupplierOrder::findAllBy($aFields, $aValue, $order) as $supplierOrder)
    //        {
                // on l'ajoute à notre tableau de retour
    //            $return[$supplierOrder->getIdSupplierOrderStatus()]['supplierOrders'][] = $supplierOrder;
    //        }
    //
            // pour chaque statut
    //        foreach(array_keys($return) as $idStatus)
    //        {
                // si on n'a aucune commande dans ce statut
    //            if(count($return[$idStatus]['supplierOrders']) < 1)
    //            {
                    // on supprime ce statut
    //                unset($return[$idStatus]);
    //            }
    //        }
    //
    //        return $return;
    //    }

    /*
     * Renvoi toutes les commandes fournisseur en production et en retard
     * @return TSupplierOrder[}
     */
    //    public static function findAllProductionDelayed()
    //    {
            // paraméte de la requête
    //        $aFields	 = array('id_supplier_order_status', 'ord_sup_ord_delivery_date');
    //        $aValue		 = array(TSupplierOrderStatus::ID_STATUS_PRODUCTION, array(System::today()->format(DateHeure::DATEMYSQL), '<='));
    //        $order		 = array('id_supplier', 'sup_ord_last_update DESC');
    //        $joinList	 = array(array('table' => TAOrderSupplierOrder::$_SQL_TABLE_NAME, 'alias' => 'oso', 'joinCondition' => 't.id_supplier_order = oso.id_supplier_order'));
    //
            // pour chaque commande fournisseur
    //        return TSupplierOrder::findAllBy($aFields, $aValue, $order, 0, $joinList);
    //    }

    /*
     * Renvoi les commandes fournisseurs à traiter pour l'accés fournisseur
     * @param fournisseur $supplier
     * @return TSupplierOrder[]
     */
    //    public static function findAllSupplierAccesWaiting(fournisseur $supplier)
    //    {
            // paraméte de la requête
    //        $aFields	 = array('id_supplier_order_status', 'id_supplier_order_status', 't.id_supplier', 'sf.id_four');
    //        $aValue		 = array(array(TSupplierOrderStatus::ID_STATUS_AUTO_LAUNCH, 'IN'), array(TSupplierOrderStatus::ID_STATUS_ACCEPTED, 'IN'), $supplier->getIdFour(), $supplier->getIdFour());
    //        $order		 = array('id_supplier_order_status DESC', 'sf.date_fournisseur DESC', 'id_supplier_order DESC');
    //        $joinList	 = array(array('table' => SelectionFournisseur::$_SQL_TABLE_NAME, 'alias' => 'sf', 'joinCondition' => 't.id_supplier_order = sf.id_supplier_order'));
    //
            // pour chaque commande fournisseur
    //        return TSupplierOrder::findAllBy($aFields, $aValue, $order, 0, $joinList);
    //    }

    /*
     * Renvoi les commandes fournisseurs à traiter pour l'accés fournisseur
     * @param fournisseur $supplier
     * @return TSupplierOrder[]
     */
    //    public static function findAllSupplierAccesProduction(fournisseur $supplier)
    //    {
            // paraméte de la requête
    //        $aFields	 = array('id_supplier_order_status', 't.id_supplier', 'sf.id_four');
    //        $aValue		 = array(TSupplierOrderStatus::ID_STATUS_PRODUCTION, $supplier->getIdFour(), $supplier->getIdFour());
    //        $order		 = array('id_supplier_order_status DESC', 'sf.date_fournisseur DESC', 'id_supplier_order DESC');
    //        $joinList	 = array(array('table' => SelectionFournisseur::$_SQL_TABLE_NAME, 'alias' => 'sf', 'joinCondition' => 't.id_supplier_order = sf.id_supplier_order'));
    //
            // pour chaque commande fournisseur
    //        return TSupplierOrder::findAllBy($aFields, $aValue, $order, 0, $joinList);
    //    }

    /*
     * Renvoi les commandes fournisseurs expédié pour l'accés fournisseur
     * @param fournisseur $supplier
     * @return TSupplierOrder[]
     */
    //    public static function findAllSupplierAccesDispatched(fournisseur $supplier)
    //    {
            // paraméte de la requête
    //        $aFields	 = array('id_supplier_order_status', 't.id_supplier', 'sf.id_four', 'o.orders_status', 'o.orders_status', 'o.orders_status', 'o.orders_status', 'o.orders_status', 'o.orders_status');
    //        $aValue		 = array(TSupplierOrderStatus::ID_STATUS_DISPATCHED, $supplier->getIdFour(), $supplier->getIdFour(), array(OrdersStatus::STATUS_EXPEDITION, 'IN'), array(OrdersStatus::STATUS_RECLA_TRAITE, 'IN'), array(OrdersStatus::STATUS_RECLA_VALIDEE, 'IN'), array(OrdersStatus::STATUS_RECLA_A_VALIDE, 'IN'), array(OrdersStatus::STATUS_RECLA_EN_TRAITEMENT, 'IN'), array(OrdersStatus::STATUS_EXPEDITION_VA, 'IN'));
    //        $order		 = array('id_supplier_order_status DESC', 'sf.date_fournisseur DESC', 'id_supplier_order DESC');
    //        $joinList	 = array(array('table' => SelectionFournisseur::$_SQL_TABLE_NAME, 'alias' => 'sf', 'joinCondition' => 't.id_supplier_order = sf.id_supplier_order'),
    //            array('table' => TAOrderSupplierOrder::$_SQL_TABLE_NAME, 'alias' => 'oso', 'joinCondition' => 't.id_supplier_order = oso.id_supplier_order'),
    //            array('table' => order::$_SQL_TABLE_NAME, 'alias' => 'o', 'joinCondition' => 'oso.id_order = o.orders_id'));
    //
            // pour chaque commande fournisseur
    //        return TSupplierOrder::findAllBy($aFields, $aValue, $order, 0, $joinList);
    //    }

    //    public static function statsByMonth()
    //    {
    //        $return = array('count' => array());
    //
            // récupération de tous les fournisseurs
    //        $allSupplier = fournisseur::findAll();
    //
            // paramétre de la requête pour les stats
    //        $aTable			 = array(array('table' => TSupplierOrder::$_SQL_TABLE_NAME, 'alias' => 'so'), array('table' => TAOrderSupplierOrder::$_SQL_TABLE_NAME, 'alias' => 'oso'));
    //        $fields			 = array('so.id_supplier', 'count(*) as so_count', 'DATE_FORMAT(oso.ord_sup_ord_delivery_date, "%Y-%m") AS month', 'SUM(oso.ord_sup_ord_price_without_tax) as price');
    //        $where			 = array(array('id_supplier_order_status', TSupplierOrderStatus::ID_STATUS_DISPATCHED, 'd'), array('oso.ord_sup_ord_delivery_date', '2022-01-01', 's', '>='));
    //        $joinCondition	 = array('so.id_supplier_order = oso.id_supplier_order');
    //        $groupByList	 = array('so.id_supplier', 'month');
    //        $order			 = array('month DESC', 'so_count DESC');
    //
            // recherche de tous les gabarits avec  le nombre de commande associé
    //        $allData = DB::prepareSelectAndExecuteAndFetchAll($aTable, $fields, $where, 0, $order, $joinCondition, $groupByList);
    //
            // pour chaque ligne
    //        foreach($allData as $dataLine)
    //        {
                // pas encore de total pour ce mois
    //            if(!isset($return['count']['total'][$dataLine['month']]))
    //            {
                    // on le créé
    //                $return['count']['total'][$dataLine['month']]	 = $dataLine['so_count'];
    //                $return['amount']['total'][$dataLine['month']]	 = new Prix(round($dataLine['price']), 2, 0);
    //
                    // on créé le mois
    //                $return['month'][$dataLine['month']] = new DateHeure($dataLine['month'] . '-01');
    //            }
                // on a déjà un total
    //            else
    //            {
                    // ajout au total
    //                $return['count']['total'][$dataLine['month']] += $dataLine['so_count'];
    //                $return['amount']['total'][$dataLine['month']]->addPrix(new Prix(round($dataLine['price']), 2, 0));
    //            }
    //        }
    //
            // pour chaque ligne
    //        foreach($allData as $dataLine)
    //        {
                // on ajoute les info au retour
    //            $return['count']['detail'][$dataLine['month']][$dataLine['id_supplier']]['supplier'] = $allSupplier[$dataLine['id_supplier']];
    //            $return['count']['detail'][$dataLine['month']][$dataLine['id_supplier']]['count']	 = $dataLine['so_count'];
    //            $return['count']['detail'][$dataLine['month']][$dataLine['id_supplier']]['percent']	 = round($dataLine['so_count'] / $return['count']['total'][$dataLine['month']] * 100, 2);
    //            $return['amount']['detail'][$dataLine['month']][$dataLine['price']]['supplier']		 = $allSupplier[$dataLine['id_supplier']];
    //            $return['amount']['detail'][$dataLine['month']][$dataLine['price']]['price']		 = new Prix(round($dataLine['price']), 2, 0);
    //            $return['amount']['detail'][$dataLine['month']][$dataLine['price']]['percent']		 = round($dataLine['price'] / $return['amount']['total'][$dataLine['month']]->getMontant() * 100, 2);
    //        }
    //
            // pour chaque mois
    //        foreach(array_keys($return['amount']['detail']) as $month)
    //        {
                // on tri les stats par montant total
    //            krsort($return['amount']['detail'][$month]);
    //        }
    //
    //        return $return;
    //    }

    // TODO Service
    /*
     * Renvoi le nombre de commande lié à notre commande fournisseur
     * @return int
     */
    //    public function countOrder()
    //    {
            // on compte le nombre de sous objet
    //        return count($this->getAOrderSupplierOrder());
    //    }

    /*
     * Renvoi la 1er commande lié à notre objet ou null si aucune commande n'est lié (théoriquement impossible)
     * @return order|null
     */
    //    public function getFirstOrder()
    //    {
            // si aucune commande n'est lié
    //        if($this->countOrder() == 0)
    //        {
                // on quitte la fonction
    //            return null;
    //        }
    //
            // on renvoi la 1er commande lié
    //        return $this->getFirstOrderSupplierOrder()->getOrder();
    //    }

    /*
     * Renvoi la 1er liaison commande/commande fournisseur lié à notre objet ou null si aucune commande n'est lié (théoriquement impossible)
     * @return TAOrderSupplierOrder|null
     */
    //    public function getFirstOrderSupplierOrder()
    //    {
            // si aucune commande n'est lié
    //        if($this->countOrder() == 0)
    //        {
                // on quitte la fonction
    //            return null;
    //        }
    //
            // on renvoi la 1er liaison commande/commande fournisseur lié
    //        return $this->getAOrderSupplierOrder()[0];
    //    }

    /*
     * renvoi l'id de la commande crypté
     * @return string
     */
    //    public function getCryptedId()
    //    {
    //        return ToolsSecure::cryptInput($this->getIdSupplierOrder());
    //    }

    /*
     * Cré un nouvel objet "TSupplierOrder" et le lie a une commande et le retourne
     * @param int $idOrder id de la commande chez nous
     * @param date $deliveryDate date de livraison estimé
     * @param int $idSupplier [=fournisseur::ID_SUPPLIER_UNKNOWN] id du fournisseur
     * @param float $ordSupOrdPriceWithoutTax [=0] ventilation du montant HT de la commande fournisseur
     * @param float $supOrdInformation [=''] information sur le fournisserur
     * @param string $supOrdId [=""] numéro de la commande chez le fournisseur
     * @param int $idSupplierOrderStatus [=TSupplierOrderStatus::ID_STATUS_PRE_ORDER] statut de la commande chez le fournisseur
     * @param int|null $jobId [=null] id du job pour les commandes multiples
     * @param string $supOrdInformationForSupplier [=''] information à destination du fournisserur
     * @return TSupplierOrder Nouvel Objet inseré en base
     */
    //    public static function createNewForOrder($idOrder, $deliveryDate, $idSupplier = fournisseur::ID_SUPPLIER_UNKNOWN, $ordSupOrdPriceWithoutTax = 0, $supOrdInformation = '', $supOrdId = '', $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRE_ORDER, $jobId = null, $supOrdInformationForSupplier = '')
    //    {
            // si on n'a pas d'id fournisseur
    //        if($idSupplier == null)
    //        {
                // on recherche le fournisseur par rapport aux informations fourni
    //            $supplierIdByName = fournisseur::supplierIdBySupplierInformation($supOrdInformation);
    //
                // si on n'a pas trouvé
    //            if($supplierIdByName == false)
    //            {
                    // on prend un fournisseur inconnu
    //                $idSupplier = fournisseur::ID_SUPPLIER_UNKNOWN;
    //            }
                // on a trouvé un fournisseur
    //            else
    //            {
                    // on récupére le bon id de fournisseur
    //                $idSupplier = $supplierIdByName['idSupplier'];
    //
                    // on prend le nom du fournisseur modifié
    //                $supOrdInformation = $supplierIdByName['supplierInformation'];
    //            }
    //        }
    //
            // on créé la commande fournisseur
    //        $supplierOrder = new TSupplierOrder();
    //        $supplierOrder->setIdSupplier($idSupplier)
    //            ->setSupOrdId($supOrdId)
    //            ->setIdSupplierOrderStatus($idSupplierOrderStatus)
    //            ->setSupOrdInformation($supOrdInformation)
    //            ->setSupOrdInformationForSupplier($supOrdInformationForSupplier)
    //            ->save();
    //
            // liaison avec la commande
    //        TAOrderSupplierOrder::createNew($idOrder, $supplierOrder->getIdSupplierOrder(), $deliveryDate, $ordSupOrdPriceWithoutTax, $jobId);
    //
    //        return $supplierOrder;
    //    }

    /*
     * Cré un nouvel objet "TSupplierOrder" et le lie a une commande et le retourne
     * @param array $aIdOrder tableau des id des commandes chez nous
     * @param date $deliveryDate date de livraison estimé
     * @param int $idSupplier [=fournisseur::ID_SUPPLIER_UNKNOWN] id du fournisseur
     * @param float $ordSupOrdPriceWithoutTax [=0] montant HT de la commande fournisseur
     * @param float $supOrdInformation [=''] information sur le fournisserur
     * @param string $supOrdId [=""] numéro de la commande chez le fournisseur
     * @param int $idSupplierOrderStatus [=TSupplierOrderStatus::ID_STATUS_PRE_ORDER] statut de la commande chez le fournisseur
     * @param int|null $jobId [=null] id du job pour les commandes multiples
     * @param string $supOrdInformationForSupplier [=''] information à destination du fournisserur
     * @return TSupplierOrder Nouvel Objet inseré en base
     */
    //    public static function createNewForMultipleOrder($aIdOrder, $deliveryDate, $idSupplier = fournisseur::ID_SUPPLIER_UNKNOWN, $ordSupOrdPriceWithoutTax = 0, $supOrdInformation = '', $supOrdId = '', $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRE_ORDER, $jobId = null, $supOrdInformationForSupplier = '')
    //    {
    //        $totalAmount = 0;
    //
            // si on a aucune commande
    //        if(!is_array($aIdOrder) || count($aIdOrder) < 1)
    //        {
                // on quitte la fonction
    //            return false;
    //        }
    //
            // on récupére le nombre de commande
    //        $countOrders = count($aIdOrder);
    //
            // on extrait le numéro de la premiére commande
    //        $firstIdOrder = array_shift($aIdOrder);
    //
            // on arrondi le prix d'achat
    //        $buyingPrice = round($ordSupOrdPriceWithoutTax, 2);
    //
            // si on a une seul commande.
    //        if($countOrders == 1)
    //        {
                // on appel la fonction qui créé notre commande fournisseur pour une commande
    //            return TSupplierOrder::createNewForOrder($firstIdOrder, $deliveryDate, $idSupplier, $buyingPrice, $supOrdInformation, $supOrdId, $idSupplierOrderStatus, $jobId, $supOrdInformationForSupplier);
    //        }
    //
            // on calcul le prix d'achat par commande
    //        $buyingPriceByOrder = round($buyingPrice / $countOrders, 2);
    //
            // création de la commande fournisseur et liaison à la premiére commande avec le prix total
    //        $supplierOrder = TSupplierOrder::createNewForOrder($firstIdOrder, $deliveryDate, $idSupplier, $buyingPrice, $supOrdInformation, $supOrdId, $idSupplierOrderStatus, $jobId, $supOrdInformationForSupplier);
    //
            // pour chaque autre commande
    //        foreach($aIdOrder as $idOrder)
    //        {
                // liaison avec la commande
    //            TAOrderSupplierOrder::createNew($idOrder, $supplierOrder->getIdSupplierOrder(), $deliveryDate, $buyingPriceByOrder, $jobId);
    //
                // on ajoute le montant au total
    //            $totalAmount = $buyingPriceByOrder;
    //        }
    //
            // on récupére la premiére commande lié à notre commande fournisseur
    //        $firstOrderSupplierOrder = TAOrderSupplierOrder::findById(array($firstIdOrder, $supplierOrder->getIdSupplierOrder()));
    //
            // on met à jour le prix pour être sur que le total ventilé soit parfait et qu'on ne perde pas 1 centime à cause des arrondi
    //        $firstOrderSupplierOrder->setOrdSupOrdPriceWithoutTax($buyingPrice - $totalAmount)
    //            ->save();
    //
            // on renvoi la commande fournisseur
    //        return $supplierOrder;
    //    }

    /*
     * avant de sauvegarder on met à jour la date de derniére modification
     */
    //    protected function _preSave()
    //    {
    //        parent::_preSave();
    //
            // on met à jour la date de derniére modification
    //        $this->setSupOrdLastUpdate(System::today()->format(DateHeure::DATETIMEMYSQL));
    //    }

    /*
     * avant de supprimer
     */
    //    protected function _preDelete()
    //    {
    //        parent::_preDelete();
    //
            // pour chaque association entre commande et commande fournisseur
    //        foreach($this->getAOrderSupplierOrder() as $orderSupplierOrder)
    //        {
                // on le supprime
    //            $orderSupplierOrder->delete();
    //        }
    //    }

    /*
     * Recherche une commande fournisseur par rapport a un numéro de commande fournisseur et un id de job fournisseur.
     * Si on ne trouve rien recherche plutot par rapport à l'id de commande
     * Cherche parmi les fournisseur inconnu si on ne trouve pas.
     * @param string $supplierOrderId numéro de commande chez le fournisseur
     * @param int $supplierId id du fournisseur
     * @param int|null $jobId [= null] id du job ou null si non applicable
     * @param int|null $idorder [= null] id de la commande ou null si non applicable
     * @return TSupplierOrder|null la commande fournisseur ou null si on n'a aucune commande fournisseur correspondante
     */
    //    public static function findBySupplierId($supplierOrderId, $supplierId, $jobId = null, $idOrder = null)
    //    {
            // on cherche toute les commandes fournisseur correspondante
    //        $allSupplierOrder = TSupplierOrder::findAllBy(array('sup_ord_id', 'id_supplier'), array($supplierOrderId, $supplierId));
    //
            // si on a qu'un seul résultat
    //        if(count($allSupplierOrder) == 1)
    //        {
                // on renvoi la commande fournisseur
    //            return array_values($allSupplierOrder)[0];
    //        }
    //
            // si on a plusieurs résultats
    //        if(count($allSupplierOrder) > 1)
    //        {
                // on renvoi la commande fournisseur
    //            return self::_selectSupplierOrderByJobIdOrIdOrder($allSupplierOrder, $jobId, $idOrder);
    //        }
    //
            // on regarde si on trouve une commande appartenant a un fournisseur inconnu avec ce numéro
    //        $allSupplierOrderUnknown = TSupplierOrder::findAllBy(array('sup_ord_id', 'id_supplier'), array($supplierOrderId, fournisseur::ID_SUPPLIER_UNKNOWN));
    //
            // si on a qu'un seul résultat
    //        if(count($allSupplierOrderUnknown) == 1)
    //        {
                // on va réatribué cette commande fournisseur à notre fournisseur
    //            array_values($allSupplierOrderUnknown)[0]->setIdSupplier($supplierId)
    //                ->save();
    //
                // on renvoi la commande fournisseur
    //            return array_values($allSupplierOrderUnknown)[0];
    //        }
    //
            // si on a plusieurs résultats
    //        if(count($allSupplierOrderUnknown) > 1)
    //        {
                // on récupére la commande fournisseur
    //            $supplierOrderUnknown = self::_selectSupplierOrderByJobId($allSupplierOrderUnknown, $jobId);
    //
                // on va réatribué cette commande fournisseur à notre fournisseur
    //            $supplierOrderUnknown->setIdSupplier($supplierId)
    //                ->save();
    //
                // on renvoi la commande fournisseur
    //            return $supplierOrderUnknown;
    //        }
    //
            // on quitte la fonction
    //        return null;
    //    }

    /*
     * Renvoi la commande fournisseur correspondant au job ou à l'id de commande si elle existe
     * @param TSupplierOrder[] $allSupplierOrder le tableau des commande fournisseur
     * @param int|null $jobId id du job ou null si on n'en a pas
     * @param int|null $idOrder [=null] id de la commande ou null si on n'en a pas
     * @return TSupplierOrder
     */
    //    private static function _selectSupplierOrderByJobIdOrIdOrder($allSupplierOrder, $jobId, $idOrder = null)
    //    {
    //        $allSupplierOrderWithJobOk = array();
    //
            // si on n'a pas de numéro de job
    //        if($jobId == null)
    //        {
                // on renvoi la commande fournisseur selon le numéro de commande
    //            return self::_selectSupplierOrderByIdOrder($allSupplierOrder, $idOrder);
    //        }
    //
            // pour chaque commande fournisseur
    //        foreach($allSupplierOrder as $supplierOrder)
    //        {
                // pour chaque commande lié
    //            foreach($supplierOrder->getAOrderSupplierOrder() as $orderSupplierOrder)
    //            {
                    // si on a un job qui correspond
    //                if($orderSupplierOrder->getOrdSupOrdJobId() == $jobId)
    //                {
                        // on ajoute à la liste des job validé
    //                    $allSupplierOrderWithJobOk[] = $supplierOrder;
    //                }
    //            }
    //        }
    //
            // si on a des job correct
    //        if(count($allSupplierOrderWithJobOk) > 0)
    //        {
                // on cherche dedans par numéro de commande
    //            return self::_selectSupplierOrderByIdOrder($allSupplierOrderWithJobOk, $idOrder);
    //        }
    //
            // on cherche dedans par numéro de commande
    //        return self::_selectSupplierOrderByIdOrder($allSupplierOrderWithJobOk, $idOrder);
    //    }

    /*
     * Renvoi la commande fournisseur correspondant a l'id de commande si elle existe
     * @param TSupplierOrder[] $allSupplierOrder le tableau des commande fournisseur
     * @param int|null $idOrder id de la commande
     * @return TSupplierOrder
     */
    //    private static function _selectSupplierOrderByIdOrder($allSupplierOrder, $idOrder)
    //    {
            // si on n'a pas de numéro de commande
    //        if($idOrder == null)
    //        {
                // on renvoi la 1er commande fournisseur
    //            return array_values($allSupplierOrder)[0];
    //        }
    //
            // pour chaque commande fournisseur
    //        foreach($allSupplierOrder as $supplierOrder)
    //        {
                // pour chaque commande lié
    //            foreach($supplierOrder->getAOrderSupplierOrder() as $orderSupplierOrder)
    //            {
                    // si on a un job qui correspond
    //                if($orderSupplierOrder->getIdOrder() == $idOrder)
    //                {
                        // on renvoi cette commande fournisseur
    //                    return $supplierOrder;
    //                }
    //            }
    //        }
    //
            // on renvoi la 1er commande fournisseur
    //        return array_values($allSupplierOrder)[0];
    //    }
    //

    /*
     * renvoi une commande (ou false) pour une commande en depart fab
     * @return TSupplierOrder|false la commande ou false si on a pas de commande
     */
    //    public static function findForSupplierAutoLaunch()
    //    {
            // initialisation du where
    //        $aChamp	 = array('id_supplier_order_status');
    //        $aValue	 = array(TSupplierOrderStatus::ID_STATUS_AUTO_LAUNCH);
    //
            // tri aléatoire
    //        $order = array('rand()');
    //
            // pour chaque commande fournisseur qu'on a trouvé
    //        foreach(self::findAllBy($aChamp, $aValue, $order) as $supplierOrder)
    //        {
                //si on n'a pas de méthode pour envoyé chez le fournisseur (c'est que c'est une commande pour l'accés fournisseur
    //            if(!$supplierOrder->getSupplier()->haveSupplierOrderAutoLaunch())
    //            {
                    // on passe à la suivante
    //                continue;
    //            }
    //
                // on renvoi cette commande fournisseur
    //            return $supplierOrder;
    //        }
    //
            // on a pas trouvé de commande
    //        return false;
    //    }

    /*
     * retrourne un objet TSupplierOrder par rapport à un id crypté via la méthode ToolsSecure::cryptInput. renvoi un objet vide si aucune commande ne correspond
     * @param string $cryptedId
     * @return TSupplierOrder
     */
    //    public static function findByCryptedId($cryptedId)
    //    {
    //        return self::findById(ToolsSecure::decryptInput($cryptedId));
    //    }

    /*
     * Met à jour une éventuel pré commande fournisseur ou créé une commande fournisseur si besoin
     * @param int $idOrder id de la commande chez nous
     * @param date|null $deliveryDate [=null] date de livraison estimé. Mettre null conservera la date existante si elle existe et créera une date par défaut sinon.
     * @param int $supplierId [=fournisseur::ID_SUPPLIER_UNKNOWN] id du fournisseur
     * @param float|null $ordSupOrdPriceWithoutTax [=0] ventilation du montant HT de la commande fournisseur.  Mettre null conservera le prix existant si il existe et mettra 0 sinon.
     * @param string $supplierOrderId [=""] numéro de la commande chez le fournisseur
     * @param int $idSupplierOrderStatus [=TSupplierOrderStatus::ID_STATUS_PRE_ORDER] statut de la commande chez le fournisseur
     * @param int|null $jobId [=null] id du job pour les commandes multiples
     * @param string $supOrdInformationForSupplier [=''] information à destination du fournisserur
     * @param TSupplierOrder|null $supplierOrder [=null] la commande fournisseur si on l'a sinon null pour la chercher ou la créé
     * @return boolean
     */
    //    public static function updatePreOrderOrCreateNew($idOrder, $deliveryDate = null, $supplierId = fournisseur::ID_SUPPLIER_UNKNOWN, $ordSupOrdPriceWithoutTax = 0, $supplierOrderId = '', $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRE_ORDER, $jobId = null, $supOrdInformationForSupplier = '', $supplierOrder = null)
    //    {
    //        $orderSupplierOrder = null;
    //
            // on récupére la commande
    //        $order = order::findById(array($idOrder));
    //
            // si on n'a pas trouvé la commande
    //        if(!$order->exist())
    //        {
                // on renvoi un mail d'erreur
    //            $log = TLog::initLog('commande fournisseur avec commande incorrect');
    //            $log->Erreur('Commande non trouvé : ' . $idOrder);
    //
                // on quitte la fonction
    //            return false;
    //        }
    //
            // si on n'a pas donné de commande fournisseur et qu'on a un numéro de commande du fournisseur
    //        if($supplierOrder == null && $supplierOrderId != '')
    //        {
                // on commence par chercher si notre commande fournisseur n'existe pas déjà
    //            $supplierOrder = TSupplierOrder::findBySupplierId($supplierOrderId, $supplierId, $jobId);
    //        }
    //
            // si on n'a pas trouvé
    //        if($supplierOrder == null)
    //        {
                // pour chaque commande fournisseur lié à notre commande
    //            foreach($order->getAOrderSupplierOrder() as $orderSupplierOrderToTest)
    //            {
                    // si la commande fournisseur appartient à ce fournisseur et est en pré commande ou lancement auto
    //                if($orderSupplierOrderToTest->getSupplierOrder()->getIdSupplier() == $supplierId && ($orderSupplierOrderToTest->getSupplierOrder()->getIdSupplierOrderStatus() == TSupplierOrderStatus::ID_STATUS_PRE_ORDER || $orderSupplierOrderToTest->getSupplierOrder()->getIdSupplierOrderStatus() == TSupplierOrderStatus::ID_STATUS_AUTO_LAUNCH))
    //                {
                        // on récupére cette commande fournisseur
    //                    $orderSupplierOrder = $orderSupplierOrderToTest;
    //
                        // on quitte la boucle
    //                    break;
    //                }
    //            }
    //
                // pour chaque commande fournisseur lié à notre commande
    //            foreach($order->getAOrderSupplierOrder() as $orderSupplierOrderToTest)
    //            {
                    // si la commande fournisseur appartient à ce fournisseur et n'a pas de numéro de commande
    //                if($orderSupplierOrderToTest->getSupplierOrder()->getIdSupplier() == $supplierId && $orderSupplierOrderToTest->getSupplierOrder()->getSupOrdId() == '')
    //                {
                        // on récupére cette commande fournisseur
    //                    $orderSupplierOrder = $orderSupplierOrderToTest;
    //
                        // on quitte la boucle
    //                    break;
    //                }
    //            }
    //
                // pour chaque commande fournisseur lié à notre commande
    //            foreach($order->getAOrderSupplierOrder() as $orderSupplierOrderToTest)
    //            {
                    // si la commande fournisseur est en pré commande
    //                if($orderSupplierOrderToTest->getSupplierOrder()->getIdSupplierOrderStatus() == TSupplierOrderStatus::ID_STATUS_PRE_ORDER)
    //                {
                        // on récupére cette commande fournisseur
    //                    $orderSupplierOrder = $orderSupplierOrderToTest;
    //
    //                    // on quitte la boucle
    //                    break;
    //                }
    //            }
    //
                // pour chaque commande fournisseur lié à notre commande
    //            foreach($order->getAOrderSupplierOrder() as $orderSupplierOrderToTest)
    //            {
                    // si la commande fournisseur est inconnu
    //                if($orderSupplierOrderToTest->getSupplierOrder()->getIdSupplierOrderStatus() == TSupplierOrderStatus::ID_STATUS_UNKNOWN)
    //                {
                        // on récupére cette commande fournisseur
    //                    $orderSupplierOrder = $orderSupplierOrderToTest;
    //
                        // on quitte la boucle
    //                    break;
    //                }
    //            }
    //        }
            // on a trouvé la pré commande
    //        else
    //        {
                // on recherche la liaison entre commande et commande fournisseur
    //            $orderSupplierOrder = TAOrderSupplierOrder::findById(array($idOrder, $supplierOrder->getIdSupplierOrder()));
    //
                // on met à jout les propriété de l'objet ou cas ou la commande fournisseur n'est pas lié à cette commande, le reste sera mis à jour juste aprés
    //            $orderSupplierOrder->setIdOrder($idOrder)
    //                ->setIdSupplierOrder($supplierOrder->getIdSupplierOrder());
    //        }
    //
            // on transforme la date en objet date heure
    //        $delivery = new DateHeure($deliveryDate);
    //
            // si on a une pré commade fournisseur
    //        if($orderSupplierOrder != null)
    //        {
                // si on a un prix
    //            if($ordSupOrdPriceWithoutTax !== null)
    //            {
                    // on met à jour le prix
    //                $orderSupplierOrder->setOrdSupOrdPriceWithoutTax($ordSupOrdPriceWithoutTax);
    //            }
    //
                // si on a une date
    //            if($deliveryDate !== null)
    //            {
                    // on met à jour la date
    //                $orderSupplierOrder->setOrdSupOrdDeliveryDate($delivery->format(DateHeure::DATEMYSQL));
    //            }
    //
                // on met à jour tous les éléments de la commande fournisseur
    //            $orderSupplierOrder->setOrdSupOrdJobId($jobId)
    //                ->save();
    //            $orderSupplierOrder->getSupplierOrder()->setIdSupplier($supplierId)
    //                ->setSupOrdId($supplierOrderId)
    //                ->setIdSupplierOrderStatus((int) $idSupplierOrderStatus)
    //                ->setSupOrdInformationForSupplier($supOrdInformationForSupplier)
    //                ->save();
    //
                // on récupére la command fournisseur mise à jour
    //            $supplierOrder = $orderSupplierOrder->getSupplierOrder();
    //        }
            // on avait pas de pré commande fournisseur
    //        else
    //        {
                // si on n'a pas de prix
    //            if($ordSupOrdPriceWithoutTax === null)
    //            {
                    // on met un prix à 0
    //                $ordSupOrdPriceWithoutTax = 0;
    //            }
    //
                // si on n'a pas dee date
    //            if($deliveryDate === null)
    //            {
                    // on créé une date par défaut
    //                $delivery = DateHeure::jPlusX(5);
    //            }
    //
                // on créé la commande fournisseur
    //            $supplierOrder = TSupplierOrder::createNewForOrder($idOrder, $delivery->format(DateHeure::DATEMYSQL), $supplierId, $ordSupOrdPriceWithoutTax, '', $supplierOrderId, $idSupplierOrderStatus, $jobId);
    //        }
    //
    //        return $supplierOrder;
    //    }

    /*
     * renvoi le prix de cette commande fournisseur. Somme de toutes les prix des $orderSupplierOrder lié
     * @return \Prix
     */
    //    public function price()
    //    {
            // initialisation avec un prix à 0. avec le taux de TVA du fournisseur
    //        $return = new Prix(0, TCurrencies::ID_EURO, $this->getSupplier()->getTauxTva(), Prix::PRIXHT);
    //
            // pour chaque commande lié
    //        foreach($this->getAOrderSupplierOrder() as $orderSupplierOrder)
    //        {
                // on ajoute le prix
    //            $return->addPrix($orderSupplierOrder->getPrice());
    //        }
    //
    //        return $return;
    //    }

    /*
     * Relie la commande fournisseur à toutes les commandes nécessaire
     * @param int|int[] $idOrderMixed id de la commande ou des commandes si on a un array ou null si on n'a aucune info
     */
    //    public function linkWithAllOrder($idOrderMixed)
    //    {
            // si on n'a pas d'id de commande
    //        if($idOrderMixed == null)
    //        {
                // on n'a rien à faire
    //            return true;
    //        }
    //
            // si on n'a pas un tableau
    //        if(!is_array($idOrderMixed))
    //        {
                // on le transforme en tableau
    //            $idOrderMixed = array($idOrderMixed);
    //        }
    //
            // pour chaque numéro de commande à vérifier
    //        foreach($idOrderMixed as $idOrder)
    //        {
                // on n'a pas encore trouvé cette commande
    //            $orderFound = false;

                // pour chaque commande lié à notre commande fournisseur
    //            foreach($this->getAOrderSupplierOrder() as $orderSupplierOrder)
    //            {
                    // si elle correspond à notre commande
    //                if($orderSupplierOrder->getIdOrder() == $idOrder)
    //                {
                        // on a trouvé
    //                    $orderFound = true;
    //                    break;
    //                }
    //            }
    //
                // si on a trouvé la commande
    //            if($orderFound == true)
    //            {
                    // on passe au suivante
    //                continue;
    //            }
    //
                // on va créé le lien avec la commande
    //            $this->_createLinkWithOrder($idOrder);
    //        }
    //
    //        return true;
    //    }

    /*
     * Créé un lien entre notre commande fournisseur et une commande
     * @param int $idOrder id de la commande à lié
     * @return boolean true en cas de succés et false si la commande n'existe pas
     */
    //    private function _createLinkWithOrder($idOrder)
    //    {
            // on récupére la commande
    //        $order = order::findById(array($idOrder));
    //
            // si la commande n'existe pas
    //        if(!$order->exist())
    //        {
                // on ne fait rien
    //            return false;
    //        }
    //
            // pour chaque commande fournisseur
    //        foreach($order->getAOrderSupplierOrder() as $orderSupplierOrder)
    //        {
                // si on a la bon numéro de  commande fournisseur et id fournisseur
    //            if($this->getSupOrdId() == $orderSupplierOrder->getSupplierOrder()->getSupOrdId() && $this->getIdSupplier() == $orderSupplierOrder->getSupplierOrder()->getIdSupplier())
    //            {
                    // on va remplacer la commande fournisseur
    //                return $this->_replaceSupplierOrder($orderSupplierOrder);
    //            }
    //        }
    //
            // pour chaque commande fournisseur
    //        foreach($order->getAOrderSupplierOrder() as $orderSupplierOrder)
    //        {
                // si on a la bon numéro de  commande fournisseur et id fournisseur
    //            if($this->getSupOrdId() == $orderSupplierOrder->getSupplierOrder()->getSupOrdId())
    //            {
                    // on va remplacer la commande fournisseur
    //                return $this->_replaceSupplierOrder($orderSupplierOrder);
    //            }
    //        }
    //
            // pour chaque commande fournisseur
    //        foreach($order->getAOrderSupplierOrder() as $orderSupplierOrder)
    //        {
                // si on une  commande fournisseur en pré comande
    //            if($orderSupplierOrder->getSupplierOrder()->getIdSupplierOrderStatus() == TSupplierOrderStatus::ID_STATUS_PRE_ORDER)
    //            {
                    // on va remplacer la commande fournisseur
    //                return $this->_replaceSupplierOrder($orderSupplierOrder);
    //            }
    //        }
    //
            // on va créé le lien avec la commande
    //        TAOrderSupplierOrder::createNew($idOrder, $this->getIdSupplierOrder(), $this->getFirstOrderSupplierOrder()->getDeliveryDate());
    //
            // on auitte la fonction
    //        return true;
    //    }

    /*
     * Remplace le lien avec une commande fournisseur par notre commande fournissuer
     * @param TAOrderSupplierOrder $orderSupplierOrder la commande fournisseur à modifier
     * @return true
     */
    //    private function _replaceSupplierOrder(TAOrderSupplierOrder $orderSupplierOrder)
    //    {
            // on récupére la commande fournisseur à supprimer
    //        $supplierOrder = $orderSupplierOrder->getSupplierOrder();
    //
            // on relie la commande fournisseur à notre commande actuel
    //        $orderSupplierOrder->setIdSupplierOrder($this->getIdSupplierOrder())
    //            ->save();
    //
            // on su^pprime l'ancienne commande fournisseur
    //        $supplierOrder->delete();
    //
    //        return true;
    //    }
}
