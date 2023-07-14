<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Provider;
use App\Entity\TAOrderSupplierOrder;
use App\Entity\TSupplierOrder;
use App\Entity\TSupplierOrderStatus;
use App\Repository\OrderRepository;
use App\Repository\TAOrderSupplierOrderRepository;
use App\Repository\TSupplierOrderRepository;
use App\Repository\TSupplierOrderStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class SupplierOrderService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TSupplierOrderRepository $TSupplierOrderRepository,
        private TSupplierOrderStatusRepository $orderStatusRepository,
        private TAOrderSupplierOrderRepository $TAOrderSupplierOrderRepository,
        private OrderRepository $orderRepository,
        private BaseProvider $baseProvider,
        private OrderSupplierOrderService $orderSupplierOrderService
    ){}

    /**
     * getteur du sous objet des commandes lié à notre objet
     * @return TAOrderSupplierOrder[]
     */
    public function getAOrderSupplierOrder(TSupplierOrder  $supplierOrder, ?int $jobId = null): array
    {
        // si on n'a pas encore chercher notre objet

        if($supplierOrder->getTaOrderSupplierOrders() === null)
        {
            $supplierOrder->getTaOrderSupplierOrders() ;
        }

        // si on ne recherche pas par job id
        if($jobId == null)
        {
            // on renvoi direct nos sous objet
            return $supplierOrder->getTaOrderSupplierOrders();
        }

        $return = array();

        // pour chaque objet
        foreach($supplierOrder->getTaOrderSupplierOrders() as $orderSupplierOrder)
        {
            // si l'objet correspond au job ou n'a pas de job
            if($orderSupplierOrder->getJobId() == null || $orderSupplierOrder->getJobId() == $jobId)
            {
                // on l'ajoute à notre retour
                $return[] = $orderSupplierOrder;
            }
        }

        return $return;
    }

    /**
     * Renvoi la commande fournisseur correspondant au job ou à l'id de commande si elle existe
     * @param TSupplierOrder[] $allSupplierOrder le tableau des commande fournisseur
     * @param int|null $jobId id du job ou null si on n'en a pas
     * @param int|null $idOrder [=null] id de la commande ou null si on n'en a pas
     * @return TSupplierOrder
     */
    private function _selectSupplierOrderByJobIdOrIdOrder($allSupplierOrder, $jobId, $idOrder = null)
    {
        $allSupplierOrderWithJobOk = array();

        // si on n'a pas de numéro de job
        if($jobId == null)
        {
            // on renvoi la commande fournisseur selon le numéro de commande
            return self::_selectSupplierOrderByIdOrder($allSupplierOrder, $idOrder);
        }

        // pour chaque commande fournisseur
        foreach($allSupplierOrder as $supplierOrder)
        {
            // pour chaque commande lié
            foreach($supplierOrder->getAOrderSupplierOrder() as $orderSupplierOrder)
            {
                // si on a un job qui correspond
                if($orderSupplierOrder->getJobId() == $jobId)
                {
                    // on ajoute à la liste des job validé
                    $allSupplierOrderWithJobOk[] = $supplierOrder;
                }
            }
        }

        // si on a des job correct
        if(count($allSupplierOrderWithJobOk) > 0)
        {
            // on cherche dedans par numéro de commande
            return self::_selectSupplierOrderByIdOrder($allSupplierOrderWithJobOk, $idOrder);
        }

        // on cherche dedans par numéro de commande
        return self::_selectSupplierOrderByIdOrder($allSupplierOrderWithJobOk, $idOrder);
    }

    /**
     * Recherche une commande fournisseur par rapport a un numéro de commande fournisseur et un id de job fournisseur.
     * Si on ne trouve rien recherche plutot par rapport à l'id de commande
     * Cherche parmi les fournisseur inconnu si on ne trouve pas.
     * @param string $supplierOrderId numéro de commande chez le fournisseur
     * @param int $supplierId id du fournisseur
     * @param int|null $jobId [= null] id du job ou null si non applicable
     * @param int|null $idorder [= null] id de la commande ou null si non applicable
     * @return TSupplierOrder|null la commande fournisseur ou null si on n'a aucune commande fournisseur correspondante
     */
    private function findBySupplierId($supplierOrderId, $supplierId, $jobId = null, $idOrder = null)
    {
        // on cherche toute les commandes fournisseur correspondante
        $allSupplierOrder = $this->TSupplierOrderRepository->findAllBySupplierOrderIdAndIdProvider($supplierOrderId, $supplierId);

        // si on a qu'un seul résultat
        if(count($allSupplierOrder) == 1)
        {
            // on renvoi la commande fournisseur
            return array_values($allSupplierOrder)[0];
        }

        // si on a plusieurs résultats
        if(count($allSupplierOrder) > 1)
        {
            // on renvoi la commande fournisseur
            return self::_selectSupplierOrderByJobIdOrIdOrder($allSupplierOrder, $jobId, $idOrder);
        }

        // on regarde si on trouve une commande appartenant a un fournisseur inconnu avec ce numéro
        $allSupplierOrderUnknown = $this->TSupplierOrderRepository->findAllBySupplierOrderIdAndIdProvider($supplierOrderId, TProvider::ID_SUPPLIER_UNKNOWN);

        // si on a qu'un seul résultat
        if(count($allSupplierOrderUnknown) == 1)
        {
            // on va réatribué cette commande fournisseur à notre fournisseur
            array_values($allSupplierOrderUnknown)[0]->setIdSupplier($supplierId);

            $this->entityManager->persist(array_values($allSupplierOrderUnknown)[0]);
            $this->entityManager->flush();

            // on renvoi la commande fournisseur
            return array_values($allSupplierOrderUnknown)[0];
        }

        // si on a plusieurs résultats
        if(count($allSupplierOrderUnknown) > 1)
        {
            // on récupére la commande fournisseur
            $supplierOrderUnknown = self::_selectSupplierOrderByJobId($allSupplierOrderUnknown, $jobId);

            // on va réatribué cette commande fournisseur à notre fournisseur
            $supplierOrderUnknown->setIdSupplier($supplierId);
            $this->entityManager->persist($supplierOrderUnknown);
            $this->entityManager->flush();

            // on renvoi la commande fournisseur
            return $supplierOrderUnknown;
        }

        // on quitte la fonction
        return null;
    }
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
    protected function createNewForOrder(int $idOrder, \DateTime $deliveryDate, int $idSupplier = Provider::ID_SUPPLIER_UNKNOWN, $ordSupOrdPriceWithoutTax = 0, $supOrdInformation = '', $supOrdId = '', $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRE_ORDER, $jobId = null, $supOrdInformationForSupplier = ''): TSupplierOrder
    {
        // si on n'a pas d'id fournisseur
        if($idSupplier == null)
        {
            // on recherche le fournisseur par rapport aux informations fourni
            $supplierIdByName = $this->baseProvider->supplierIdBySupplierInformation($supOrdInformation);

            // si on n'a pas trouvé
            if($supplierIdByName == false)
            {
                // on prend un fournisseur inconnu
                $idSupplier = Provider::ID_SUPPLIER_UNKNOWN;
            }
            // on a trouvé un fournisseur
            else
            {
                // on récupére le bon id de fournisseur
                $idSupplier = $supplierIdByName['idSupplier'];

                // on prend le nom du fournisseur modifié
                $supOrdInformation = $supplierIdByName['supplierInformation'];
            }
        }

        // on créé la commande fournisseur
        $supplierOrder = new TSupplierOrder();
        $supplierOrder->setIdSupplier($idSupplier)
            ->setSupplierOrderdId($supOrdId)
            ->setIdSupplierOrderStatus($idSupplierOrderStatus)
            ->setSupplierOrderInformation($supOrdInformation)
            ->setInformationForSupplier($supOrdInformationForSupplier);
        $this->entityManager->persist($supplierOrder);
        $this->entityManager->flush();

        // liaison avec la commande
        $this->orderSupplierOrderService->createNew($idOrder, $supplierOrder->getIdSupplierOrder(), $deliveryDate, $ordSupOrdPriceWithoutTax, $jobId);

        return $supplierOrder;
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
     * @return TSupplierOrder
     */
    private function updatePreOrderOrCreateNew(int $idOrder, $deliveryDate = null, $supplierId = Provider::ID_SUPPLIER_UNKNOWN, $ordSupOrdPriceWithoutTax = 0, $supplierOrderId = '', $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRE_ORDER, $jobId = null, $supOrdInformationForSupplier = '', $supplierOrder = null): TSupplierOrder
    {
        $orderSupplierOrder = null;


        // on récupére la commande
        $order = $this->orderRepository->find($idOrder);

        // si on n'a pas trouvé la commande
        if(!$order->exist())
        {
            // on renvoi un mail d'erreur
//            $log = TLog::initLog('commande fournisseur avec commande incorrect');
//            $log->Erreur('Commande non trouvé : ' . $idOrder);

            // on quitte la fonction
            return false;
        }

        // si on n'a pas donné de commande fournisseur et qu'on a un numéro de commande du fournisseur
        if($supplierOrder == null && $supplierOrderId != '')
        {
            // on commence par chercher si notre commande fournisseur n'existe pas déjà
            $supplierOrder = $this->findBySupplierId($supplierOrderId, $supplierId, $jobId);
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
            $orderSupplierOrder = $this->TAOrderSupplierOrderRepository->findById($idOrder, $supplierOrder->getIdSupplierOrder());

            // on met à jout les propriété de l'objet ou cas ou la commande fournisseur n'est pas lié à cette commande, le reste sera mis à jour juste aprés
            $orderSupplierOrder->setIdOrder($idOrder)
                ->setIdSupplierOrder($supplierOrder->getIdSupplierOrder());
        }

        // on transforme la date en objet date heure
        $delivery = new DateTime($deliveryDate);

        // si on a une pré commade fournisseur
        if($orderSupplierOrder != null)
        {
            // si on a un prix
            if($ordSupOrdPriceWithoutTax !== null)
            {
                // on met à jour le prix
                $orderSupplierOrder->setPriceWithoutTax($ordSupOrdPriceWithoutTax);
            }

            // si on a une date
            if($deliveryDate !== null)
            {
                // on met à jour la date
                $orderSupplierOrder->setDeliveryDate($delivery->format(DateHeure::DATEMYSQL));
            }

            // on met à jour tous les éléments de la commande fournisseur
            $orderSupplierOrder->setJobId($jobId);
            $this->entityManager->persist();

            $orderSupplierOrder->getSupplierOrder()->setIdSupplier($supplierId)
                ->setSupplierOrderId($supplierOrderId)
                ->setIdSupplierOrderStatus((int) $idSupplierOrderStatus)
                ->setInformationForSupplier($supOrdInformationForSupplier);
            $this->entityManager->persist();
            $this->entityManager->flush();

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
            $supplierOrder = $this->createNewForOrder($idOrder, $delivery->format(DateHeure::DATEMYSQL), $supplierId, $ordSupOrdPriceWithoutTax, '', $supplierOrderId, $idSupplierOrderStatus, $jobId);

        }

        return $supplierOrder;
    }

    /**
     * Remplace le lien avec une commande fournisseur par notre commande fournissuer
     * @param TAOrderSupplierOrder $orderSupplierOrder la commande fournisseur à modifier
     * @return true
     */
    private function _replaceSupplierOrder(TAOrderSupplierOrder $orderSupplierOrder) : bool
    {
        // on récupére la commande fournisseur à supprimer
        $supplierOrder = $orderSupplierOrder->getTSupplierOrder();

        // on relie la commande fournisseur à notre commande actuel
        $orderSupplierOrder->setIdSupplierOrder($this->getIdSupplierOrder());
        $this->entityManager->persist($orderSupplierOrder);
        $this->entityManager->flush();

        // on su^pprime l'ancienne commande fournisseur
        $this->TSupplierOrderRepository->remove($supplierOrder);

        return true;
    }

    /**
     * Renvoi la 1er liaison commande/commande fournisseur lié à notre objet ou null si aucune commande n'est lié (théoriquement impossible)
     * @return TAOrderSupplierOrder|null
     */
    public function getFirstOrderSupplierOrder(TSupplierOrder $supplierOrder): ?TAOrderSupplierOrder
    {
        // si aucune commande n'est lié
        if($supplierOrder->countOrder() == 0)
        {
            // on quitte la fonction
            return null;
        }

        // on renvoi la 1er liaison commande/commande fournisseur lié
        return $supplierOrder->getAOrderSupplierOrder()[0];
    }

    /**
     * Créé un lien entre notre commande fournisseur et une commande
     * @param int $idOrder id de la commande à lié
     * @return boolean true en cas de succés et false si la commande n'existe pas
     */
    private function _createLinkWithOrder(TSupplierOrder  $supplierOrder, int $idOrder): bool
    {
        // on récupére la commande
        $order = $this->orderRepository->find($idOrder);

        // si la commande n'existe pas
        if(!$order->exist())
        {
            // on ne fait rien
            return false;
        }

        // pour chaque commande fournisseur
        foreach($order->getTaOrderSupplierOrders() as $orderSupplierOrder)
        {
            // si on a la bon numéro de  commande fournisseur et id fournisseur
            if($supplierOrder->getSupplierOrderId() == $orderSupplierOrder->getTSupplierOrder()->getSupplierOrderId() && $supplierOrder->getIdProvider() == $orderSupplierOrder->getTSupplierOrder()->getIdProvider())
            {
                // on va remplacer la commande fournisseur
                return $this->_replaceSupplierOrder($orderSupplierOrder);
            }
        }

        // pour chaque commande fournisseur
        foreach($order->getTaOrderSupplierOrders() as $orderSupplierOrder)
        {
            // si on a la bon numéro de  commande fournisseur et id fournisseur
            if($supplierOrder->getSupplierOrderId() == $orderSupplierOrder->getTSupplierOrder()->getSupplierOrderId())
            {
                // on va remplacer la commande fournisseur
                return $this->_replaceSupplierOrder($orderSupplierOrder);
            }
        }

        // pour chaque commande fournisseur
        foreach($order->getTaOrderSupplierOrders() as $orderSupplierOrder)
        {
            // si on une  commande fournisseur en pré comande
            if($orderSupplierOrder->getTSupplierOrder()->getIdSupplierOrderStatus() == TSupplierOrderStatus::ID_STATUS_PRE_ORDER)
            {
                // on va remplacer la commande fournisseur
                return $this->_replaceSupplierOrder($orderSupplierOrder);
            }
        }
        // on va créé le lien avec la commande
        $newTAOrderSupplierOrder = new TAOrderSupplierOrder();
        $newTAOrderSupplierOrder->setTOrder($idOrder)
            ->setIdSupplierOrder($supplierOrder->getId())
            ->setDeliveryDate($this->getFirstOrderSupplierOrder($supplierOrder)->getDeliveryDate());

        $this->TAOrderSupplierOrderRepository->save($newTAOrderSupplierOrder);
        //TAOrderSupplierOrder::createNew($idOrder, $supplierOrder->getId(), $supplierOrder->getFirstOrderSupplierOrder()->getDeliveryDate());

        // on auitte la fonction
        return true;
    }



    /**
     * Relie la commande fournisseur à toutes les commandes nécessaire
     * @param int|int[] $idOrderMixed id de la commande ou des commandes si on a un array ou null si on n'a aucune info
     */
    public function linkWithAllOrder(TSupplierOrder  $supplierOrder,$idOrderMixed)
    {
        // si on n'a pas d'id de commande
        if($idOrderMixed == null)
        {
            // on n'a rien à faire
            return true;
        }

        // si on n'a pas un tableau
        if(!is_array($idOrderMixed))
        {
            // on le transforme en tableau
            $idOrderMixed = array($idOrderMixed);
        }

        // pour chaque numéro de commande à vérifier
        foreach($idOrderMixed as $idOrder)
        {
            // on n'a pas encore trouvé cette commande
            $orderFound = false;

            // pour chaque commande lié à notre commande fournisseur
            foreach($supplierOrder->getTaOrderSupplierOrders() as $orderSupplierOrder)
            {
                // si elle correspond à notre commande
                if($orderSupplierOrder->getIdOrder() == $idOrder)
                {
                    // on a trouvé
                    $orderFound = true;
                    break;
                }
            }

            // si on a trouvé la commande
            if($orderFound == true)
            {
                // on passe au suivante
                continue;
            }

            // on va créé le lien avec la commande
            $this->_createLinkWithOrder($idOrder);
        }

        return true;
    }

}
