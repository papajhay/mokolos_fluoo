<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\TSupplierOrder;
use App\Entity\TSupplierOrderStatus;
use App\Repository\TSupplierOrderStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class SupplierOrderService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TSupplierOrderStatusRepository $orderStatusRepository
    ){}

    /**
     * met à jour le statut d'une commande fournisseur uniquement si l'ordre est plus important que le statut actuel
     * @param int $newIdSupplierStatus le nouveau statut
     * @return bool true si on a modifié le statut et false sinon
     */
    public function updateStatusIfAfterCurrent(TSupplierOrder $supplierOrder, int $newIdSupplierStatus): bool
    {
        // on récupére le nouveau statut
        $newSupplierStatus = $this->orderStatusRepository->findById($newIdSupplierStatus);


        // si la commande est déjà dans le bon statut
        if($newSupplierStatus->getSupplierOrderStatusOrder() == $supplierOrder->getStatus()->getSupplierOrderStatusOrder())
        {
            // on quitte la fonction en indiquant qu'on a fait la modif
            return true;
        }

        // si notre nouveau statut est plus avancé que le statut actuel ou si notre nouveau statut est un statut important
        if($newSupplierStatus->getSupplierOrderStatusOrder() > $supplierOrder->getStatus()->getSupplierOrderStatusOrder() || $newSupplierStatus->getActive() == 2)
        {
            // on met à jour le statut
            $supplierOrder->setIdSupplierOrderStatus($newIdSupplierStatus);

            $this->entityManager->persist($supplierOrder);
            $this->entityManager->flush();

            // on a bien fait la modif
            return true;
        }

        // aucune modification effectué
        return false;
    }


    /**
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
    public static function updatePreOrderOrCreateNew($idOrder, $deliveryDate = null, $supplierId = fournisseur::ID_SUPPLIER_UNKNOWN, $ordSupOrdPriceWithoutTax = 0, $supplierOrderId = '', $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRE_ORDER, $jobId = null, $supOrdInformationForSupplier = '', $supplierOrder = null)
    {
        $orderSupplierOrder = null;

        // on récupére la commande
        $order = order::findById(array($idOrder));

        // si on n'a pas trouvé la commande
        if(!$order->exist())
        {
            // on renvoi un mail d'erreur
            $log = TLog::initLog('commande fournisseur avec commande incorrect');
            $log->Erreur('Commande non trouvé : ' . $idOrder);

            // on quitte la fonction
            return false;
        }

        // si on n'a pas donné de commande fournisseur et qu'on a un numéro de commande du fournisseur
        if($supplierOrder == null && $supplierOrderId != '')
        {
            // on commence par chercher si notre commande fournisseur n'existe pas déjà
            $supplierOrder = TSupplierOrder::findBySupplierId($supplierOrderId, $supplierId, $jobId);
        }

        // si on n'a pas trouvé
        if($supplierOrder == null)
        {
            // pour chaque commande fournisseur lié à notre commande
            foreach($order->getAOrderSupplierOrder() as $orderSupplierOrderToTest)
            {
                // si la commande fournisseur appartient à ce fournisseur et est en pré commande ou lancement auto
                if($orderSupplierOrderToTest->getSupplierOrder()->getIdSupplier() == $supplierId && ($orderSupplierOrderToTest->getSupplierOrder()->getIdSupplierOrderStatus() == TSupplierOrderStatus::ID_STATUS_PRE_ORDER || $orderSupplierOrderToTest->getSupplierOrder()->getIdSupplierOrderStatus() == TSupplierOrderStatus::ID_STATUS_AUTO_LAUNCH))
                {
                    // on récupére cette commande fournisseur
                    $orderSupplierOrder = $orderSupplierOrderToTest;

                    // on quitte la boucle
                    break;
                }
            }

            // pour chaque commande fournisseur lié à notre commande
            foreach($order->getAOrderSupplierOrder() as $orderSupplierOrderToTest)
            {
                // si la commande fournisseur appartient à ce fournisseur et n'a pas de numéro de commande
                if($orderSupplierOrderToTest->getSupplierOrder()->getIdSupplier() == $supplierId && $orderSupplierOrderToTest->getSupplierOrder()->getSupOrdId() == '')
                {
                    // on récupére cette commande fournisseur
                    $orderSupplierOrder = $orderSupplierOrderToTest;

                    // on quitte la boucle
                    break;
                }
            }

            // pour chaque commande fournisseur lié à notre commande
            foreach($order->getAOrderSupplierOrder() as $orderSupplierOrderToTest)
            {
                // si la commande fournisseur est en pré commande
                if($orderSupplierOrderToTest->getSupplierOrder()->getIdSupplierOrderStatus() == TSupplierOrderStatus::ID_STATUS_PRE_ORDER)
                {
                    // on récupére cette commande fournisseur
                    $orderSupplierOrder = $orderSupplierOrderToTest;

                    // on quitte la boucle
                    break;
                }
            }

            // pour chaque commande fournisseur lié à notre commande
            foreach($order->getAOrderSupplierOrder() as $orderSupplierOrderToTest)
            {
                // si la commande fournisseur est inconnu
                if($orderSupplierOrderToTest->getSupplierOrder()->getIdSupplierOrderStatus() == TSupplierOrderStatus::ID_STATUS_UNKNOWN)
                {
                    // on récupére cette commande fournisseur
                    $orderSupplierOrder = $orderSupplierOrderToTest;

                    // on quitte la boucle
                    break;
                }
            }
        }
        // on a trouvé la pré commande
        else
        {
            // on recherche la liaison entre commande et commande fournisseur
            $orderSupplierOrder = TAOrderSupplierOrder::findById(array($idOrder, $supplierOrder->getIdSupplierOrder()));

            // on met à jout les propriété de l'objet ou cas ou la commande fournisseur n'est pas lié à cette commande, le reste sera mis à jour juste aprés
            $orderSupplierOrder->setIdOrder($idOrder)
                ->setIdSupplierOrder($supplierOrder->getIdSupplierOrder());
        }

        // on transforme la date en objet date heure
        $delivery = new DateHeure($deliveryDate);

        // si on a une pré commade fournisseur
        if($orderSupplierOrder != null)
        {
            // si on a un prix
            if($ordSupOrdPriceWithoutTax !== null)
            {
                // on met à jour le prix
                $orderSupplierOrder->setOrdSupOrdPriceWithoutTax($ordSupOrdPriceWithoutTax);
            }

            // si on a une date
            if($deliveryDate !== null)
            {
                // on met à jour la date
                $orderSupplierOrder->setOrdSupOrdDeliveryDate($delivery->format(DateHeure::DATEMYSQL));
            }

            // on met à jour tous les éléments de la commande fournisseur
            $orderSupplierOrder->setOrdSupOrdJobId($jobId)
                ->save();
            $orderSupplierOrder->getSupplierOrder()->setIdSupplier($supplierId)
                ->setSupOrdId($supplierOrderId)
                ->setIdSupplierOrderStatus((int) $idSupplierOrderStatus)
                ->setSupOrdInformationForSupplier($supOrdInformationForSupplier)
                ->save();

            // on récupére la command fournisseur mise à jour
            $supplierOrder = $orderSupplierOrder->getSupplierOrder();
        }
        // on avait pas de pré commande fournisseur
        else
        {
            // si on n'a pas de prix
            if($ordSupOrdPriceWithoutTax === null)
            {
                // on met un prix à 0
                $ordSupOrdPriceWithoutTax = 0;
            }

            // si on n'a pas dee date
            if($deliveryDate === null)
            {
                // on créé une date par défaut
                $delivery = DateHeure::jPlusX(5);
            }

            // on créé la commande fournisseur
            $supplierOrder = TSupplierOrder::createNewForOrder($idOrder, $delivery->format(DateHeure::DATEMYSQL), $supplierId, $ordSupOrdPriceWithoutTax, '', $supplierOrderId, $idSupplierOrderStatus, $jobId);
        }

        return $supplierOrder;
    }

}
