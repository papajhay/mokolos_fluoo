<?php

declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\AchattodbEmail;
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
        private OrderSupplierOrderService $orderSupplierOrderService,
        private BaseProvider $baseProvider
    ) {
    }

    /**
     * getteur du sous objet des commandes lié à notre objet.
     */
    public function getAOrderSupplierOrder(TSupplierOrder $supplierOrder, int $jobId = null): \Doctrine\Common\Collections\Collection|array
    {
        // si on n'a pas encore chercher notre objet

        if (null === $supplierOrder->getTaOrderSupplierOrders()) {
            $supplierOrder->getTaOrderSupplierOrders();
        }

        // si on ne recherche pas par job id
        if (null === $jobId) {
            // on renvoi direct nos sous objet
            return $supplierOrder->getTaOrderSupplierOrders();
        }

        $return = [];

        // pour chaque objet
        foreach ($supplierOrder->getTaOrderSupplierOrders() as $orderSupplierOrder) {
            // si l'objet correspond au job ou n'a pas de job
            if (null === $orderSupplierOrder->getJobId() || $orderSupplierOrder->getJobId() === $jobId) {
                // on l'ajoute à notre retour
                $return[] = $orderSupplierOrder;
            }
        }

        return $return;
    }

    /**
     * Renvoi la commande fournisseur correspondant a l'id de commande si elle existe.
     * @param TSupplierOrder[] $allSupplierOrder le tableau des commande fournisseur
     * @param int|null         $idOrder          id de la commande
     */
    private static function _selectSupplierOrderByIdOrder(array $allSupplierOrder, ?int $idOrder): TSupplierOrder
    {
        // si on n'a pas de numéro de commande
        if (null === $idOrder) {
            // on renvoi la 1er commande fournisseur
            return array_values($allSupplierOrder)[0];
        }

        // pour chaque commande fournisseur
        foreach ($allSupplierOrder as $supplierOrder) {
            // pour chaque commande lié
            foreach ($supplierOrder->getAOrderSupplierOrder() as $orderSupplierOrder) {
                // si on a un job qui correspond
                if ($orderSupplierOrder->getIdOrder() === $idOrder) {
                    // on renvoi cette commande fournisseur
                    return $supplierOrder;
                }
            }
        }

        // on renvoi la 1er commande fournisseur
        return array_values($allSupplierOrder)[0];
    }

    /**
     * Renvoi la commande fournisseur correspondant au job ou à l'id de commande si elle existe.
     * @param TSupplierOrder[] $allSupplierOrder le tableau des commande fournisseur
     * @param int|null         $jobId            id du job ou null si on n'en a pas
     * @param int|null         $idOrder          [=null] id de la commande ou null si on n'en a pas
     */
    private function _selectSupplierOrderByJobIdOrIdOrder(array $allSupplierOrder, ?int $jobId, int $idOrder = null): TSupplierOrder
    {
        $allSupplierOrderWithJobOk = [];

        // si on n'a pas de numéro de job
        if (null === $jobId) {
            // on renvoi la commande fournisseur selon le numéro de commande
            return $this->_selectSupplierOrderByIdOrder($allSupplierOrder, $idOrder);
        }

        // pour chaque commande fournisseur
        foreach ($allSupplierOrder as $supplierOrder) {
            // pour chaque commande lié
            foreach ($supplierOrder->getAOrderSupplierOrder() as $orderSupplierOrder) {
                // si on a un job qui correspond
                if ($orderSupplierOrder->getJobId() === $jobId) {
                    // on ajoute à la liste des job validé
                    $allSupplierOrderWithJobOk[] = $supplierOrder;
                }
            }
        }

        // si on a des job correct
        if (count($allSupplierOrderWithJobOk) > 0) {
            // on cherche dedans par numéro de commande
            return $this->_selectSupplierOrderByIdOrder($allSupplierOrderWithJobOk, $idOrder);
        }

        // on cherche dedans par numéro de commande
        return $this->_selectSupplierOrderByIdOrder($allSupplierOrderWithJobOk, $idOrder);
    }

    /**
     * Recherche une commande fournisseur par rapport a un numéro de commande fournisseur et un id de job fournisseur.
     * Si on ne trouve rien recherche plutot par rapport à l'id de commande
     * Cherche parmi les fournisseur inconnu si on ne trouve pas.
     * @param  string              $supplierOrderId numéro de commande chez le fournisseur
     * @param  int                 $supplierId      id du fournisseur
     * @param  int|null            $jobId           [= null] id du job ou null si non applicable
     * @return TSupplierOrder|null la commande fournisseur ou null si on n'a aucune commande fournisseur correspondante
     */
    public function findBySupplierId(string $supplierOrderId, int $supplierId, int $jobId = null): ?TSupplierOrder
    {// on cherche toute les commandes fournisseur correspondante
        $allSupplierOrder = $this->TSupplierOrderRepository->findAllBySupplierOrderIdAndIdProvider($supplierOrderId, $supplierId);

        // si on a qu'un seul résultat
        if (1 === count($allSupplierOrder)) {
            // on renvoi la commande fournisseur
            return array_values($allSupplierOrder)[0];
        }

        // si on a plusieurs résultats
        if (count($allSupplierOrder) > 1) {
            // on renvoi la commande fournisseur
            return $this->_selectSupplierOrderByJobIdOrIdOrder($allSupplierOrder, $jobId, null);
        }

        // on regarde si on trouve une commande appartenant a un fournisseur inconnu avec ce numéro
        $allSupplierOrderUnknown = $this->TSupplierOrderRepository->findAllBySupplierOrderIdAndIdProvider($supplierOrderId, TProvider::ID_SUPPLIER_UNKNOWN);

        // si on a qu'un seul résultat
        if (1 === count($allSupplierOrderUnknown)) {
            // on va réatribué cette commande fournisseur à notre fournisseur
            array_values($allSupplierOrderUnknown)[0]->setIdSupplier($supplierId);

            $this->entityManager->persist(array_values($allSupplierOrderUnknown)[0]);
            $this->entityManager->flush();

            // on renvoi la commande fournisseur
            return array_values($allSupplierOrderUnknown)[0];
        }

        // si on a plusieurs résultats
        if (count($allSupplierOrderUnknown) > 1) {
            // on récupére la commande fournisseur
            $supplierOrderUnknown = $this->_selectSupplierOrderByJobId($allSupplierOrderUnknown, $jobId);

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
     * met à jour le statut d'une commande fournisseur uniquement si l'ordre est plus important que le statut actuel.
     * @param  int  $newIdSupplierStatus le nouveau statut
     * @return bool true si on a modifié le statut et false sinon
     */
    public function updateStatusIfAfterCurrent(TSupplierOrder $supplierOrder, int $newIdSupplierStatus): bool
    {
        // on récupére le nouveau statut
        $newSupplierStatus = $this->orderStatusRepository->findById($newIdSupplierStatus);

        // si la commande est déjà dans le bon statut
        if ($newSupplierStatus->getSupplierOrderStatusOrder() === $supplierOrder->getStatus()->getSupplierOrderStatusOrder()) {
            // on quitte la fonction en indiquant qu'on a fait la modif
            return true;
        }

        // si notre nouveau statut est plus avancé que le statut actuel ou si notre nouveau statut est un statut important
        if ($newSupplierStatus->getSupplierOrderStatusOrder() > $supplierOrder->getStatus()->getSupplierOrderStatusOrder() || 2 === $newSupplierStatus->getActive()) {
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
     * Cré un nouvel objet "TSupplierOrder" et le lie a une commande et le retourne.
     * @param  int            $idOrder                      id de la commande chez nous
     * @param  \DateTime      $deliveryDate                 date de livraison estimé
     * @param  int            $idSupplier                   [=fournisseur::ID_SUPPLIER_UNKNOWN] id du fournisseur
     * @param  float|int      $ordSupOrdPriceWithoutTax     [=0] ventilation du montant HT de la commande fournisseur
     * @param  float|string   $supOrdInformation            [=''] information sur le fournisserur
     * @param  string         $supOrdId                     [=""] numéro de la commande chez le fournisseur
     * @param  int            $idSupplierOrderStatus        [=TSupplierOrderStatus::ID_STATUS_PRE_ORDER] statut de la commande chez le fournisseur
     * @param  int|null       $jobId                        [=null] id du job pour les commandes multiples
     * @param  string         $supOrdInformationForSupplier [=''] information à destination du fournisserur
     * @return TSupplierOrder Nouvel Objet inseré en base
     */
    protected function createNewForOrder(int $idOrder, \DateTime $deliveryDate, int $idSupplier = Provider::ID_SUPPLIER_UNKNOWN, float|int $ordSupOrdPriceWithoutTax = 0, float|string $supOrdInformation = '', string $supOrdId = '', int $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRE_ORDER, int $jobId = null, string $supOrdInformationForSupplier = ''): TSupplierOrder
    {
        // si on n'a pas d'id fournisseur
        if (null === $idSupplier) {
            // on recherche le fournisseur par rapport aux informations fourni
            $supplierIdByName = $this->baseProvider->supplierIdBySupplierInformation($supOrdInformation);

            // si on n'a pas trouvé
            if (false === $supplierIdByName) {
                // on prend un fournisseur inconnu
                $idSupplier = Provider::ID_SUPPLIER_UNKNOWN;
            }
            // on a trouvé un fournisseur
            else {
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
     * Met à jour une éventuel pré commande fournisseur ou créé une commande fournisseur si besoin.
     * @param int                 $idOrder                      id de la commande chez nous
     * @param \DateTime|null      $deliveryDate                 [=null] date de livraison estimé. Mettre null conservera la date existante si elle existe et créera une date par défaut sinon.
     * @param int                 $supplierId                   [=fournisseur::ID_SUPPLIER_UNKNOWN] id du fournisseur
     * @param float|int|null      $ordSupOrdPriceWithoutTax     [=0] ventilation du montant HT de la commande fournisseur.  Mettre null conservera le prix existant si il existe et mettra 0 sinon.
     * @param string              $supplierOrderId              [=""] numéro de la commande chez le fournisseur
     * @param int                 $idSupplierOrderStatus        [=TSupplierOrderStatus::ID_STATUS_PRE_ORDER] statut de la commande chez le fournisseur
     * @param int|null            $jobId                        [=null] id du job pour les commandes multiples
     * @param string              $supOrdInformationForSupplier [=''] information à destination du fournisserur
     * @param TSupplierOrder|null $supplierOrder                [=null] la commande fournisseur si on l'a sinon null pour la chercher ou la créé
     */
    public function updatePreOrderOrCreateNew(int $idOrder, \DateTime $deliveryDate = null, int $supplierId = Provider::ID_SUPPLIER_UNKNOWN, float|int|null $ordSupOrdPriceWithoutTax = 0, string $supplierOrderId = '', int $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRE_ORDER, int $jobId = null, string $supOrdInformationForSupplier = '', TSupplierOrder $supplierOrder = null): TSupplierOrder
    {
        $orderSupplierOrder = null;

        // on récupére la commande
        $order = $this->orderRepository->find($idOrder);

        // si on n'a pas trouvé la commande
        if (!$order->exist()) {
            // on renvoi un mail d'erreur
            //            $log = TLog::initLog('commande fournisseur avec commande incorrect');
            //            $log->Erreur('Commande non trouvé : ' . $idOrder);

            // on quitte la fonction
            return false;
        }

        // si on n'a pas donné de commande fournisseur et qu'on a un numéro de commande du fournisseur
        if (null === $supplierOrder && '' !== $supplierOrderId) {
            // on commence par chercher si notre commande fournisseur n'existe pas déjà
            $supplierOrder = $this->findBySupplierId($supplierOrderId, $supplierId, $jobId);
        }

        // si on n'a pas trouvé
        if (null === $supplierOrder) {
            // pour chaque commande fournisseur lié à notre commande
            foreach ($order->getAOrderSupplierOrder() as $orderSupplierOrderToTest) {
                // si la commande fournisseur appartient à ce fournisseur et est en pré commande ou lancement auto
                if ($orderSupplierOrderToTest->getSupplierOrder()->getIdSupplier() === $supplierId && (TSupplierOrderStatus::ID_STATUS_PRE_ORDER === $orderSupplierOrderToTest->getSupplierOrder()->getIdSupplierOrderStatus() || TSupplierOrderStatus::ID_STATUS_AUTO_LAUNCH === $orderSupplierOrderToTest->getSupplierOrder()->getIdSupplierOrderStatus())) {
                    // on récupére cette commande fournisseur
                    $orderSupplierOrder = $orderSupplierOrderToTest;

                    // on quitte la boucle
                    break;
                }
            }

            // pour chaque commande fournisseur lié à notre commande
            foreach ($order->getAOrderSupplierOrder() as $orderSupplierOrderToTest) {
                // si la commande fournisseur appartient à ce fournisseur et n'a pas de numéro de commande
                if ($orderSupplierOrderToTest->getSupplierOrder()->getIdSupplier() === $supplierId && '' === $orderSupplierOrderToTest->getSupplierOrder()->getSupOrdId()) {
                    // on récupére cette commande fournisseur
                    $orderSupplierOrder = $orderSupplierOrderToTest;

                    // on quitte la boucle
                    break;
                }
            }

            // pour chaque commande fournisseur lié à notre commande
            foreach ($order->getAOrderSupplierOrder() as $orderSupplierOrderToTest) {
                // si la commande fournisseur est en pré commande
                if (TSupplierOrderStatus::ID_STATUS_PRE_ORDER === $orderSupplierOrderToTest->getSupplierOrder()->getIdSupplierOrderStatus()) {
                    // on récupére cette commande fournisseur
                    $orderSupplierOrder = $orderSupplierOrderToTest;

                    // on quitte la boucle
                    break;
                }
            }

            // pour chaque commande fournisseur lié à notre commande
            foreach ($order->getAOrderSupplierOrder() as $orderSupplierOrderToTest) {
                // si la commande fournisseur est inconnu
                if (TSupplierOrderStatus::ID_STATUS_UNKNOWN === $orderSupplierOrderToTest->getSupplierOrder()->getIdSupplierOrderStatus()) {
                    // on récupére cette commande fournisseur
                    $orderSupplierOrder = $orderSupplierOrderToTest;

                    // on quitte la boucle
                    break;
                }
            }
        }
        // on a trouvé la pré commande
        else {
            // on recherche la liaison entre commande et commande fournisseur
            $orderSupplierOrder = $this->TAOrderSupplierOrderRepository->findById($idOrder, $supplierOrder->getIdSupplierOrder());

            // on met à jout les propriété de l'objet ou cas ou la commande fournisseur n'est pas lié à cette commande, le reste sera mis à jour juste aprés
            $orderSupplierOrder->setIdOrder($idOrder)
                ->setIdSupplierOrder($supplierOrder->getIdSupplierOrder());
        }

        // on transforme la date en objet date heure
        $delivery = new DateTime($deliveryDate);

        // si on a une pré commade fournisseur
        if (null !== $orderSupplierOrder) {
            // si on a un prix
            if (null !== $ordSupOrdPriceWithoutTax) {
                // on met à jour le prix
                $orderSupplierOrder->setPriceWithoutTax($ordSupOrdPriceWithoutTax);
            }

            // si on a une date
            if (null !== $deliveryDate) {
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
        else {
            // si on n'a pas de prix
            if (null === $ordSupOrdPriceWithoutTax) {
                // on met un prix à 0
                $ordSupOrdPriceWithoutTax = 0;
            }

            // si on n'a pas dee date
            if (null === $deliveryDate) {
                // on créé une date par défaut
                $delivery = DateHeure::jPlusX(5);
            }

            // on créé la commande fournisseur
            $supplierOrder = $this->createNewForOrder($idOrder, $delivery->format(DateHeure::DATEMYSQL), $supplierId, $ordSupOrdPriceWithoutTax, '', $supplierOrderId, $idSupplierOrderStatus, $jobId);
        }

        return $supplierOrder;
    }

    /**
     * Remplace le lien avec une commande fournisseur par notre commande fournissuer.
     * @param TAOrderSupplierOrder $orderSupplierOrder la commande fournisseur à modifier
     */
    private function _replaceSupplierOrder(TAOrderSupplierOrder $orderSupplierOrder): void
    {
        // on récupére la commande fournisseur à supprimer
        $supplierOrder = $orderSupplierOrder->getTSupplierOrder();

        // on relie la commande fournisseur à notre commande actuel
        $orderSupplierOrder->setIdSupplierOrder($this->getIdSupplierOrder());
        $this->entityManager->persist($orderSupplierOrder);
        $this->entityManager->flush();

        // on su^pprime l'ancienne commande fournisseur
        $this->TSupplierOrderRepository->remove($supplierOrder);
    }

    /**
     * Renvoi le nombre de commande lié à notre commande fournisseur.
     */
    public function countOrder(TSupplierOrder $supplierOrder): int
    {
        // on compte le nombre de sous objet
        return count($supplierOrder->getAOrderSupplierOrder());
    }

    /**
     * Renvoi la 1er liaison commande/commande fournisseur lié à notre objet ou null si aucune commande n'est lié (théoriquement impossible).
     */
    public function getFirstOrderSupplierOrder(TSupplierOrder $supplierOrder): ?TAOrderSupplierOrder
    {
        // si aucune commande n'est lié
        if (0 === $this->countOrder($supplierOrder)) {
            // on quitte la fonction
            return null;
        }

        // on renvoi la 1er liaison commande/commande fournisseur lié
        return $supplierOrder->getAOrderSupplierOrder()[0];
    }

    /**
     * Créé un lien entre notre commande fournisseur et une commande.
     * @param  int  $idOrder id de la commande à lié
     * @return void true en cas de succés et false si la commande n'existe pas
     */
    private function _createLinkWithOrder(TSupplierOrder $supplierOrder, int $idOrder): void
    {
        // on récupére la commande
        $order = $this->orderRepository->find($idOrder);

        // si la commande n'existe pas
        if (!$order->exist()) {
            // on ne fait rien
            return;
        }

        // pour chaque commande fournisseur
        foreach ($order->getTaOrderSupplierOrders() as $orderSupplierOrder) {
            // si on a la bon numéro de  commande fournisseur et id fournisseur
            if ($supplierOrder->getSupplierOrderId() === $orderSupplierOrder->getTSupplierOrder()->getSupplierOrderId() && $supplierOrder->getIdProvider() === $orderSupplierOrder->getTSupplierOrder()->getIdProvider()) {
                // on va remplacer la commande fournisseur
                $this->_replaceSupplierOrder($orderSupplierOrder);

                return;
            }
        }

        // pour chaque commande fournisseur
        foreach ($order->getTaOrderSupplierOrders() as $orderSupplierOrder) {
            // si on a la bon numéro de  commande fournisseur et id fournisseur
            if ($supplierOrder->getSupplierOrderId() === $orderSupplierOrder->getTSupplierOrder()->getSupplierOrderId()) {
                // on va remplacer la commande fournisseur
                $this->_replaceSupplierOrder($orderSupplierOrder);

                return;
            }
        }

        // pour chaque commande fournisseur
        foreach ($order->getTaOrderSupplierOrders() as $orderSupplierOrder) {
            // si on une  commande fournisseur en pré comande
            if (TSupplierOrderStatus::ID_STATUS_PRE_ORDER === $orderSupplierOrder->getTSupplierOrder()->getIdSupplierOrderStatus()) {
                // on va remplacer la commande fournisseur
                $this->_replaceSupplierOrder($orderSupplierOrder);

                return;
            }
        }
        // on va créé le lien avec la commande
        $newTAOrderSupplierOrder = new TAOrderSupplierOrder();
        $newTAOrderSupplierOrder->setTOrder($idOrder)
            ->setIdSupplierOrder($supplierOrder->getId())
            ->setDeliveryDate($this->getFirstOrderSupplierOrder($supplierOrder)->getDeliveryDate());

        $this->TAOrderSupplierOrderRepository->save($newTAOrderSupplierOrder);
        // TAOrderSupplierOrder::createNew($idOrder, $supplierOrder->getId(), $supplierOrder->getFirstOrderSupplierOrder()->getDeliveryDate());

        // on auitte la fonction
    }

    /**
     * Relie la commande fournisseur à toutes les commandes nécessaire.
     * @param int|int[] $idOrderMixed id de la commande ou des commandes si on a un array ou null si on n'a aucune info
     */
    public function linkWithAllOrder(TSupplierOrder $supplierOrder, array|int $idOrderMixed): bool
    {
        // si on n'a pas un tableau
        if (!is_array($idOrderMixed)) {
            // on le transforme en tableau
            $idOrderMixed = [$idOrderMixed];
        }

        // pour chaque numéro de commande à vérifier
        foreach ($idOrderMixed as $idOrder) {
            // on n'a pas encore trouvé cette commande
            $orderFound = false;

            // pour chaque commande lié à notre commande fournisseur
            foreach ($supplierOrder->getTaOrderSupplierOrders() as $orderSupplierOrder) {
                // si elle correspond à notre commande
                if ($orderSupplierOrder->getIdOrder() === $idOrder) {
                    // on a trouvé
                    $orderFound = true;
                    break;
                }
            }

            // si on a trouvé la commande
            if (true === $orderFound) {
                // on passe au suivante
                continue;
            }

            // on va créé le lien avec la commande
            $this->_createLinkWithOrder($idOrder);
        }

        return true;
    }

    /**
     * Récupére la commande Provider ou la créé si elle n'existe pas puis la met à jour.
     * @param  int                  $supplierOrderId          id de la commande chez le Provider
     * @param  int                  $idSupplierOrderStatus    [=TSupplierOrderStatus::ID_STATUS_PRODUCTION] statut de la commande Provider
     * @param  AchattodbEmail|null  $achattodbEmail           [=null] si on fournit un mail il sera mis à retraité en cas de probléme
     * @param  int|int[]|null       $idOrder                  [=null] id de la commande ou des commandes si on a un array (pour une eventuelle création)
     * @param  \DateTime|null       $deliveryDate             [=null] date de livraison de la commande Provider ou null pour ne pas la mettre à jour
     * @param  float|null           $ordSupOrdPriceWithoutTax [=null] prix d'achat HT de la commande Provider (pour une eventuelle création)
     * @param  string|null          $jobId                    [=null] id du job ou null si non applicable
     * @param  string               $additionnalComment       [=''] commentaire additionnel à ajouter dans l'historique de la commande
     * @param  int|null             $idOrderStatus            [=null] id du statut pour notre commande si on souhaite le changer. mettre null pour avoir un commentaire
     * @param  array|null           $aDeliveryInformation     [=null] tableau des informations colis pour le passage de commande en livraison ou null si non applicable
     * @return TSupplierOrder|false la commande Provider ou FALSE si rien ne correspond
     */
    public function updateOrderSupplier(Provider $provider, int $supplierOrderId, int $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRODUCTION, AchattodbEmail $achattodbEmail = null, array|int $idOrder = null, \DateTime $deliveryDate = null, float $ordSupOrdPriceWithoutTax = null, string $jobId = null, string $additionnalComment = '', int $idOrderStatus = null, array $aDeliveryInformation = null): TSupplierOrder|bool
    {
        // on recherche la commande Provider
        $supplierOrder = $this->orderSupplier($provider, $supplierOrderId, $achattodbEmail, $idOrder, $idSupplierOrderStatus, $deliveryDate, $ordSupOrdPriceWithoutTax, $jobId);

        // si on n'a pas trouvé la commande Provider
        if (false === $supplierOrder) {
            // on quitte la fonction
            return false;
        }

        // TODO add linkWithAllOrder in Service
        // relie la commande Provider à toutes les commandes nécessaire
        $this->linkWithAllOrder($idOrder);

        // récupération du statut de la commande
        $supplierOrderStatus = $this->supplierOrderStatusRepository->find($idSupplierOrderStatus);

        // si on a mis à jour le statut
        if ($supplierOrder->updateStatusIfAfterCurrent($idSupplierOrderStatus)) {
            // on ajoutera un commentaire dans l'historique pour la commande
            $comment = 'La commande '.$provider->getName().' "'.$supplierOrderId.'" est en "'.$supplierOrderStatus->getName().'".<br>';
        }
        // pas de maj du statut
        else {
            // pas de commentaire
            $comment = '';
        }

        // si on a un commentaire additionnel
        if ('' !== $additionnalComment) {
            // on l'ajoute
            $comment .= $additionnalComment.'<br>';
        }

        // on vériei si il y a bien des commande lié à notre commande Provider
        if (!$this->checkOrderLinkedToSupplierOrderWithJob($supplierOrder, $jobId, $achattodbEmail)) {
            // on quitte la fonction
            return false;
        }

        // pour chaque commande correspondant à notre job
        foreach ($supplierOrder->getAOrderSupplierOrder($jobId) as $orderSupplierOrder) {
            // si on a changé la date de livraison
            if (null !== $deliveryDate && $deliveryDate->format(DateHeure::DATEMYSQL) !== $orderSupplierOrder->getDeliveryDate()) {
                // on modifie la date de livraison
                $orderSupplierOrder->setOrdSupOrdDeliveryDate($deliveryDate->format(DateHeure::DATEMYSQL));
                $this->entityManager->persist($orderSupplierOrder);
                $this->entityManager->flush();

                // on ajoute un commentaire
                $comment .= 'Nouvelle date de livraison : '.$deliveryDate->format(DateHeure::DATEFR);
            }

            // si on passe la commande en expédié
            if (OrdersStatus::STATUS_EXPEDITION === $idOrderStatus) {
                // on passe la commande en livraison
                $orderSupplierOrder->getOrder()->setAsLivraison($provider->getName(), $aDeliveryInformation, $comment, $provider->getName());
            } elseif (OrdersStatus::STATUS_LIVRE === $idOrderStatus) {
                // on passe la commande en livre
                $orderSupplierOrder->getOrder()->setAsDelivered($comment, $provider->getName());
            }
            // si on doit changer la commande de statut
            elseif (null !== $idOrderStatus) {
                // mise à jour de la commande
                $orderSupplierOrder->getOrder()->updateStatus($idOrderStatus, $comment, OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI, '', '', $provider->getName());
            }
            // si on a un commentaire à ajouter
            elseif ('' !== trim($comment)) {
                // on ajoute un historique à la commande
                $orderSupplierOrder->getOrder()->addHistory($comment, 0, 0, '', '', $provider->getName());
            }
        }

        // passage du mail en traité
        $achattodbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED);
        $this->entityManager->persist($achattodbEmail);
        $this->entityManager->flush();

        // tout est bon
        return true;
    }

    /**
     * Vérifie qu'il y a bien des commandes lié à une commande fournisseur et un job.
     * @param  TSupplierOrder      $supplierOrder  la commande fournisseur
     * @param  string|null         $jobId          [=null] id du job
     * @param  AchattodbEmail|null $achattodbEmail [=null] si on fournit un mail il sera mis à retraité
     * @return bool                true si tout va bien et false si rien ne correspond
     */
    public function checkOrderLinkedToSupplierOrderWithJob(TSupplierOrder $supplierOrder, string $jobId = null, AchattodbEmail $achattodbEmail = null): bool
    {
        // rien ne correspond à notre job
        if (0 === count($supplierOrder->getAOrderSupplierOrder($jobId))) {
            // on envoi un log
            //            $log = TLog::initLog('commande fournisseur avec job introuvable');
            //            $log->Erreur($this->getNomFour());
            //            $log->Erreur(var_export($supplierOrder, true));
            //            $log->Erreur(var_export($jobId, true));

            // si on a un mail
            if (is_a($achattodbEmail, 'AchattodbEmail')) {
                // on va retraité le mail
                $this->achattodbEmailService->needReprocess();
            }

            // on quitte la fonction
            return false;
        }

        // tout est bon
        return true;
    }

    /**
     * Récupére la commande Provider ou la créé si elle n'existe pas.
     * @param  int                  $supplierOrderId          id de la commande chez le Provider
     * @param  AchattodbEmail|null  $achattodbEmail           [=null] si on fournit un mail il sera mis à retraité
     * @param  int|int[]|null       $idOrder                  [=null] id de la commande ou des commandes si on a un array (pour une eventuelle création)
     * @param  int                  $idSupplierOrderStatus    [=TSupplierOrderStatus::ID_STATUS_PRODUCTION] statut de la commande Provider (pour une eventuelle création)
     * @param  \DateTime|null       $deliveryDate             [=null] date de livraison de la commande Provider (pour une eventuelle création)
     * @param  float                $ordSupOrdPriceWithoutTax [=null] prix d'achat HT TOTAL de la commande Provider (pour une eventuelle création)
     * @param  type|null            $jobId                    [=null] id du job (pour une eventuelle création)
     * @return TSupplierOrder|false la commande Provider ou FALSE si rien ne correspond
     */
    public function orderSupplier(Provider $provider, int $supplierOrderId, AchattodbEmail $achattodbEmail = null, array|int $idOrder = null, int $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRODUCTION, \DateTime $deliveryDate = null, float $ordSupOrdPriceWithoutTax = null, type $jobId = null): ?TSupplierOrder
    {
        // si le numéro de commande n'est pas sous forme de tableau
        if (!is_array($idOrder)) {
            // on le transforme en array
            $aIdOrder = [$idOrder];
        }
        // on a déjà un tableau
        else {
            // on supprime les clefs
            $aIdOrder = array_values($idOrder);
        }

        // si on a un tableau vide
        if (!isset($aIdOrder[0])) {
            // on prendra null
            $aIdOrder[0] = null;
        }

        // on recherche la commande Provider
        $supplierOrder = $this->findBySupplierId((string) $supplierOrderId, $provider->getId(), $jobId, $idOrder);

        // si on a trouvé la commande Provider
        if (null !== $supplierOrder) {
            // tout est bon
            return $supplierOrder;
        }

        // on recupere la 1er commande
        $order = $this->orderRepository->findById($aIdOrder[0]);

        // si on n'a pas trouvé la commande
        if (!$order->exist()) {
            // on créé un log d'erreur
            //     		$log = TLog::initLog('commande Provider introuvable');
            //     		$log->Erreur($this->getNomFour());
            //     		$log->Erreur(var_export($supplierOrderId, TRUE));
            //     		$log->Erreur('Commande "' . $aIdOrder[0] . '" introuvable.');

            // si on a un mail
            if (is_a($achattodbEmail, 'AchattodbEmail')) {
                // on va retraité le mail
                $this->achattodbEmailService->needReprocess($achattodbEmail);
            }

            // on quitte la fonction
            return false;
        }

        // si on a un prix
        if (null !== $ordSupOrdPriceWithoutTax) {
            // on divise le prix par le nombre de commande
            $ordSupOrdPriceWithoutTax /= count($aIdOrder);
        }
        // on n'a pas de prix
        else {
            // on met le prix à 0.
            $ordSupOrdPriceWithoutTax = 0;
        }

        // on va créé la commande Provider ou récupérer une pré commande
        $newSupplierOrder = $this->updatePreOrderOrCreateNew($aIdOrder[0], $deliveryDate, $provider->getId(), $ordSupOrdPriceWithoutTax, (string) $supplierOrderId, $idSupplierOrderStatus, $jobId);

        // pour chaque commande
        foreach ($aIdOrder as $key => $idOrder) {
            // si on est sur la 1er commande
            if (0 === $key) {
                // on ne fait rien car elle est lié à notre commande Provide
                continue;
            }

            // on va lié la commande et la commande Provider
            $this->orderSupplierOrderService->createNew($idOrder, $newSupplierOrder->getIdSupplierOrder(), $deliveryDate, $ordSupOrdPriceWithoutTax, $jobId);
        }

        // tout est bon
        return $newSupplierOrder;
    }
}
