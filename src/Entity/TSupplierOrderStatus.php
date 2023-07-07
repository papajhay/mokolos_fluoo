<?php
declare(strict_types=1);
namespace App\Entity;

use App\Repository\TSupplierOrderStatusRepository;
use Doctrine\ORM\Mapping as ORM;

//$_SQL_TABLE_NAME = 'lesgrand.t_supplier_order_status';

#[ORM\Entity(repositoryClass: TSupplierOrderStatusRepository::class)]
class TSupplierOrderStatus
{
    // =================== Constantes ===================

    /**
     * id du statut : inconnu
     */
    const ID_STATUS_UNKNOWN = 0;

    /**
     * id du statut : pré commande
     */
    const ID_STATUS_PRE_ORDER = 2;

    /**
     * id du statut : erreur
     */
    //const ID_STATUS_ERROR = 10;

    /**
     * id du statut : lancement auto
     */
    const ID_STATUS_AUTO_LAUNCH = 11;

    /**
     * id du statut : commande accépté
     */
    //const ID_STATUS_ACCEPTED = 3;

    /**
     * id du statut : commande en attente de fichier
     */
    //const ID_STATUS_FILE_WAITING = 6;

    /**
     * id du statut : commande en fichier reçu
     */
    //const ID_STATUS_FILE_RECEIVED = 7;

    /**
     * id du statut : commande en fichier validé
     */
    //const ID_STATUS_FILE_VALID = 8;

    /**
     * id du statut : commande en production
     */
    const ID_STATUS_PRODUCTION = 5;

    /**
     * id du statut : commande expédié
     */
    const ID_STATUS_DISPATCHED = 4;

    /**
     * id du statut : commande annulée
     */
    //const ID_STATUS_CANCELED = 9;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // classe pour le sprite pour l'icone
    private ?string $icon = null;

    #[ORM\Column(length: 255)]
    //nom du statut
    private ?string $name = null;

    #[ORM\Column]
    //ordre du statut
    private ?int $supplierOrderStatusOrder = null;

    #[ORM\Column]
    //indique si ce statut est actif, 2 indique un statut important
    private ?int $active = null;

    #[ORM\Column(length: 255)]
    //Nom de ce statut comme il apparait dans l'accés fournisseur
    private ?string $supplierAccessName = null;
    //private $supOrdStaSupplierAccessName = 'Inconnu';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

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

    public function getSupplierOrderStatusOrder(): ?int
    {
        return $this->supplierOrderStatusOrder;
    }

    public function setSupplierOrderStatusOrder(int $supplierOrderStatusOrder): static
    {
        $this->supplierOrderStatusOrder = $supplierOrderStatusOrder;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getSupplierAccessName(): ?string
    {
        return $this->supplierAccessName;
    }

    public function setSupplierAccessName(string $supplierAccessName): static
    {
        $this->supplierAccessName = $supplierAccessName;

        return $this;
    }

    //TODO Service
    /**
     * indique si le statut est actif
     * @return boolean true si le statut est actif et false sinon
     */
//    public function isActive()
//    {
//        // statut inactif
//        if($this->getSupOrdStaActive() == 0)
//        {
//            return false;
//        }
//
//        return true;
//    }
}
