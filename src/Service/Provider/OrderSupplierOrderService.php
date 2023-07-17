<?php
declare(strict_types=1);
namespace App\Service\Provider;

use App\Entity\TAOrderSupplierOrder;
use Doctrine\ORM\EntityManagerInterface;

class OrderSupplierOrderService
{
    public function __construct(
        private EntityManagerInterface $entityManager

    ){}

    /**
     * Cré un nouvel objet "TAOrderSupplierOrder" et le retourne
     * @param int $idOrder id de notre commande
     * @param int $idSupplierOrder id de la commande fournisseur
     * @param date $ordSupOrdDeliveryDate date de la livraison estimé au format mysql
     * @param float $ordSupOrdPriceWithoutTax [=0] ventilation du montant HT de la commande fournisseur
     * @param int|null $ordSupOrdJobId [=null] id du job pour les commandes multiples
     * @return TAOrderSupplierOrder Nouvel Objet inseré en base
     */
    public function createNew(int $idOrder, int $idSupplierOrder,\DateTime $ordSupOrdDeliveryDate, $ordSupOrdPriceWithoutTax = 0, $ordSupOrdJobId = null): TAOrderSupplierOrder
    {
        // on créé un objet dateheure avec la date comme ca pas de probléme de format fr ou en
        $date = new DateTime($ordSupOrdDeliveryDate);

        // on créé notre nouvel objet en base
        $orderSupplierOrder = new TAOrderSupplierOrder();
        $orderSupplierOrder->setIdOrder($idOrder)
            ->setIdSupplierOrder($idSupplierOrder)
            ->setPriceWithoutTax($ordSupOrdPriceWithoutTax)
            ->setDeliveryDate($date->format(DateHeure::DATEMYSQL))
            ->setJobId($ordSupOrdJobId);

        $this->entityManager->persist($orderSupplierOrder);
        $this->entityManager->flush();

        return $orderSupplierOrder;
    }
}