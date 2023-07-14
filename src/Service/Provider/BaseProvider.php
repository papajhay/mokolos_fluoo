<?php
declare(strict_types=1);
namespace App\Service\Provider;

use App\Entity\Provider;
use App\Entity\AchattodbEmai;
use App\Entity\TSupplierOrder;
use App\Entity\TSupplierOrderStatus;
use App\Repository\OrderRepository;
use App\Repository\ProviderRepository;
use App\Repository\TSupplierOrderRepository;
use App\Repository\TSupplierOrderStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class BaseProvider
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private OrderRepository $orderRepository,
        private TSupplierOrderRepository $TSupplierOrderRepository,
        private ProviderRepository $providerRepository,
        private TSupplierOrderStatusRepository $supplierOrderStatusRepository,
        private AchattodbEmailService $achattodbEmailService,
        private OrderSupplierOrderService $orderSupplierOrderService,
        private SupplierOrderService $supplierOrderService
    ){}

     /**
      * Récupére la commande Provider ou la créé si elle n'existe pas
      * @param int $supplierOrderId id de la commande chez le Provider
      * @param Provider $provider
      * @param AchattodbEmail|null $achattodbEmail [=null] si on fournit un mail il sera mis à retraité
      * @param int|int[] $idOrder [=null] id de la commande ou des commandes si on a un array (pour une eventuelle création)
      * @param int $idSupplierOrderStatus [=TSupplierOrderStatus::ID_STATUS_PRODUCTION] statut de la commande Provider (pour une eventuelle création)
      * @param date $deliveryDate [=null] date de livraison de la commande Provider (pour une eventuelle création)
      * @param float $ordSupOrdPriceWithoutTax [=null] prix d'achat HT TOTAL de la commande Provider (pour une eventuelle création)
      * @param type $jobId [=null] id du job (pour une eventuelle création)
      * @return TSupplierOrder|false la commande Provider ou FALSE si rien ne correspond
      */
     public function orderSupplier(Provider $provider,int $supplierOrderId, $achattodbEmail = null, $idOrder = null, $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRODUCTION, $deliveryDate = null, $ordSupOrdPriceWithoutTax = null, $jobId = null): ?TSupplierOrder
     {
     	// si le numéro de commande n'est pas sous forme de tableau
     	if(!is_array($idOrder))
     	{
     		// on le transforme en array
     		$aIdOrder = array($idOrder);
     	}
     	// on a déjà un tableau
     	else
     	{
     		// on supprime les clefs
     		$aIdOrder = array_values($idOrder);
     	}

     	// si on a un tableau vide
     	if(!isset($aIdOrder[0]))
     	{
     		// on prendra null
     		$aIdOrder[0] = null;
     	}

     	// on recherche la commande Provider
     	$supplierOrder = $this->TSupplierOrderRepository->findBySupplierId($supplierOrderId, $provider->getId(), $jobId, $idOrder);

     	// si on a trouvé la commande Provider
     	if($supplierOrder != null)
     	{
     		// tout est bon
     		return $supplierOrder;
     	}

     	// on recupere la 1er commande
     	$order = $this->orderRepository->findById($aIdOrder[0]);

     	// si on n'a pas trouvé la commande
     	if(!$order->exist())
     	{
     		// on créé un log d'erreur
//     		$log = TLog::initLog('commande Provider introuvable');
//     		$log->Erreur($this->getNomFour());
//     		$log->Erreur(var_export($supplierOrderId, TRUE));
//     		$log->Erreur('Commande "' . $aIdOrder[0] . '" introuvable.');

     		// si on a un mail
     		if(is_a($achattodbEmail, 'AchattodbEmail'))
     		{
     			// on va retraité le mail
     			$this->achattodbEmailService->needReprocess($achattodbEmail);
     		}

     		// on quitte la fonction
     		return false;
     	}

     	// si on a un prix
     	if($ordSupOrdPriceWithoutTax != null)
     	{
     		// on divise le prix par le nombre de commande
     		$ordSupOrdPriceWithoutTax = $ordSupOrdPriceWithoutTax / count($aIdOrder);
     	}
     	// on n'a pas de prix
     	else
     	{
     		// on met le prix à 0.
     		$ordSupOrdPriceWithoutTax = 0;
     	}

     	// on va créé la commande Provider ou récupérer une pré commande
     	$newSupplierOrder = $this->TSupplierOrderRepository->updatePreOrderOrCreateNew($aIdOrder[0], $deliveryDate, $provider->getId(), $ordSupOrdPriceWithoutTax, $supplierOrderId, $idSupplierOrderStatus, $jobId);

     	// pour chaque commande
     	foreach($aIdOrder as $key => $idOrder)
     	{
     		// si on est sur la 1er commande
     		if($key == 0)
     		{
     			//on ne fait rien car elle est lié à notre commande Provide
                continue;
     		}

     		// on va lié la commande et la commande Provider
     		$this->orderSupplierOrderService->createNew($idOrder, $newSupplierOrder->getIdSupplierOrder(), $deliveryDate, $ordSupOrdPriceWithoutTax, $jobId);
     	}

     	// tout est bon
     	return $newSupplierOrder;
     }


    /**
     * Vérifie qu'il y a bien des commandes lié à une commande fournisseur et un job
     * @param TSupplierOrder $supplierOrder la commande fournisseur
     * @param string $jobId [=null] id du job
     * @param AchattodbEmail|null $achattodbEmail [=null] si on fournit un mail il sera mis à retraité
     * @return boolean true si tout va bien et false si rien ne correspond
     */
    public function checkOrderLinkedToSupplierOrderWithJob(TSupplierOrder $supplierOrder, $jobId = null, $achattodbEmail = null)
    {
        // rien ne correspond à notre job
        if(count($supplierOrder->getAOrderSupplierOrder($jobId)) == 0)
        {
            // on envoi un log
//            $log = TLog::initLog('commande fournisseur avec job introuvable');
//            $log->Erreur($this->getNomFour());
//            $log->Erreur(var_export($supplierOrder, true));
//            $log->Erreur(var_export($jobId, true));

            // si on a un mail
            if(is_a($achattodbEmail, 'AchattodbEmail'))
            {
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
      * Récupére la commande Provider ou la créé si elle n'existe pas puis la met à jour
      * @param int $supplierOrderId id de la commande chez le Provider
      * @param int $idSupplierOrderStatus [=TSupplierOrderStatus::ID_STATUS_PRODUCTION] statut de la commande Provider
      * @param AchattodbEmail|null $achattodbEmail [=null] si on fournit un mail il sera mis à retraité en cas de probléme
      * @param int|int[] $idOrder [=null] id de la commande ou des commandes si on a un array (pour une eventuelle création)
      * @param date $deliveryDate [=null] date de livraison de la commande Provider ou null pour ne pas la mettre à jour
      * @param float $ordSupOrdPriceWithoutTax [=null] prix d'achat HT de la commande Provider (pour une eventuelle création)
      * @param string|null $jobId [=null] id du job ou null si non applicable
      * @param string $additionnalComment [=''] commentaire additionnel à ajouter dans l'historique de la commande
      * @param int|null $idOrderStatus [=null] id du statut pour notre commande si on souhaite le changer. mettre null pour avoir un commentaire
      * @param array|null $aDeliveryInformation [=null] tableau des informations colis pour le passage de commande en livraison ou null si non applicable
      * @return TSupplierOrder|false la commande Provider ou FALSE si rien ne correspond
      */
     public function updateOrderSupplier(Provider $provider,int $supplierOrderId, $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRODUCTION, $achattodbEmail = null, $idOrder = null, $deliveryDate = null, $ordSupOrdPriceWithoutTax = null, $jobId = null, $additionnalComment = '', $idOrderStatus = null, $aDeliveryInformation = null)
     {
     	// on recherche la commande Provider
     	$supplierOrder = $this->orderSupplier($provider,$supplierOrderId, $achattodbEmail, $idOrder, $idSupplierOrderStatus, $deliveryDate, $ordSupOrdPriceWithoutTax, $jobId);

     	// si on n'a pas trouvé la commande Provider
     	if($supplierOrder == false)
     	{
     		// on quitte la fonction
     		return false;
     	}

         //TODO add linkWithAllOrder in Service
     	// relie la commande Provider à toutes les commandes nécessaire
     	$this->supplierOrderService->linkWithAllOrder($idOrder);

     	// récupération du statut de la commande
     	$supplierOrderStatus = $this->supplierOrderStatusRepository->find($idSupplierOrderStatus);

     	// si on a mis à jour le statut
     	if($supplierOrder->updateStatusIfAfterCurrent($idSupplierOrderStatus))
     	{
     		// on ajoutera un commentaire dans l'historique pour la commande
     		$comment = 'La commande ' . $provider->getName() . ' "' . $supplierOrderId . '" est en "' . $supplierOrderStatus->getName() . '".<br>';
     	}
     	// pas de maj du statut
     	else
     	{
     		// pas de commentaire
     		$comment = '';
     	}

     	// si on a un commentaire additionnel
     	if($additionnalComment != '')
     	{
     		// on l'ajoute
     		$comment .= $additionnalComment . '<br>';
     	}

     	// on vériei si il y a bien des commande lié à notre commande Provider
     	if(!$this->checkOrderLinkedToSupplierOrderWithJob($supplierOrder, $jobId, $achattodbEmail))
     	{
     		// on quitte la fonction
     		return false;
     	}

     	// pour chaque commande correspondant à notre job
     	foreach($supplierOrder->getAOrderSupplierOrder($jobId) as $orderSupplierOrder)
     	{
     		// si on a changé la date de livraison
     		if($deliveryDate != null && $deliveryDate->format(DateHeure::DATEMYSQL) != $orderSupplierOrder->getDeliveryDate())
     		{
     			// on modifie la date de livraison
     			$orderSupplierOrder->setOrdSupOrdDeliveryDate($deliveryDate->format(DateHeure::DATEMYSQL));
                 $this->entityManager->persist($orderSupplierOrder);
                 $this->entityManager->flush();

     			// on ajoute un commentaire
     			$comment .= 'Nouvelle date de livraison : ' . $deliveryDate->format(DateHeure::DATEFR);
     		}

     		// si on passe la commande en expédié
     		if($idOrderStatus == OrdersStatus::STATUS_EXPEDITION)
     		{
     			// on passe la commande en livraison
     			$orderSupplierOrder->getOrder()->setAsLivraison($provider->getName(), $aDeliveryInformation, $comment, $provider->getName());
     		}
     		elseif($idOrderStatus == OrdersStatus::STATUS_LIVRE)
     		{
     			// on passe la commande en livre
     			$orderSupplierOrder->getOrder()->setAsDelivered($comment, $provider->getName());
     		}
     		// si on doit changer la commande de statut
     		elseif($idOrderStatus != null)
     		{
     			// mise à jour de la commande
     			$orderSupplierOrder->getOrder()->updateStatus($idOrderStatus, $comment, OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI, '', '', $provider->getName());
     		}
     		// si on a un commentaire à ajouter
     		elseif(trim($comment) != '')
     		{
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
      * masque pcre pour récupérer le nom du site dans une url
      * @param type $url
      * @return string
      */
     private static function _pcreSiteNameFromUrl(): string
     {
     	return '#https?://(?:www\.|)([-a-zA-Z]+)\.#';
     }

     /**
      * Recherche un id Provider par rapport a son nom
      * @param string $supplierInformation
      * @return int|false on renvoi l'id ou false si on ne trouve pas
      */
     public function supplierIdBySupplierInformation(string $supplierInformation): ?array
     {
     	$return	 = array();
     	$matches = array();

     	// si on a une url
     	if(preg_match($this->_pcreSiteNameFromUrl(), $supplierInformation, $matches))
     	{
     		// on ajoute le nom du Provider aux information pour le trouver aprés.
     		$supplierInformation .= "\n" . $matches[1];
     	}

     	// recherche de tous les fournisseurs on tri par nom inversé pour chercher "print 24 belgique" avant "print 24"
     	$allSupplier = $this->providerRepository->findAllByNameDesc();

     	// on sépare chaque ligne
     	$allSupplierInformationLineRaw = explode("\n", $supplierInformation);

     	// pour chaque ligne dans l'information
     	foreach($allSupplierInformationLineRaw as $idSupplierInformationLineRaw => $supplierInformationLineRaw)
     	{
     		// on met en minuscule
     		$supplierInformationLine = trim(mb_strtolower($supplierInformationLineRaw));

     		// si on n'a pas d'information
     		if($supplierInformationLine == '')
     		{
     			// on passe au suivant
     			continue;
     		}

     		// on recherche parmis les Provider supplémentaire
     		foreach(Provider::$_ADDITIONAL_SUPPLIER as $supplierName => $supplierId)
     		{
     			// si on n'a pas trouvé
     			if(mb_strtolower($supplierName) != $supplierInformationLine)
     			{
     				// on passe à la suivante
     				continue;
     			}

     			// on ajoute l'id du fournissur
     			$return['idSupplier'] = $supplierId;

     			// on supprime cette ligne de l'information
     			unset($allSupplierInformationLineRaw[$idSupplierInformationLineRaw]);

     			// on rajoute les informations
     			$return['supplierInformation'] = implode("\n", $allSupplierInformationLineRaw);

     			// on renvoi le résultat
     			return $return;
     		}

     		// pour chaque Provider
     		foreach($allSupplier as $supplier)
     		{
     			// si on n'a pas trouvé
     			if(mb_strtolower($supplier->getName()) != $supplierInformationLine)
     			{
     				// on passe à la suivante
     				continue;
     			}

     			// on ajoute l'id du fournissur
     			$return['idSupplier'] = $supplier->getId();

     			// on supprime cette ligne de l'information
     			unset($allSupplierInformationLineRaw[$idSupplierInformationLineRaw]);

     			// on rajoute les informations
     			$return['supplierInformation'] = implode("\n", $allSupplierInformationLineRaw);

     			// on renvoi le résultat
     			return $return;
     		}
     	}

     	// on recherche parmis les Provider supplémentaire
     	foreach(Provider::$_ADDITIONAL_SUPPLIER as $supplierName => $supplierId)
     	{
     		// si on n'a pas trouvé
     		if(!preg_match('#^' . preg_quote($supplierName) . '#i', $supplierInformation))
     		{

     			// on passe à la suivante
     			continue;
     		}

     		// on ajoute l'id du fournissur
     		$return['idSupplier'] = $supplierId;

     		// on renvoi les informations en supprimant le Provider
     		$return['supplierInformation'] = trim(str_replace(mb_strtolower($supplierName), '', mb_strtolower($supplierInformation)));

     		// on renvoi le résultat
     		return $return;
     	}

     	// on recherche parmis les Provider
     	foreach($allSupplier as $supplier)
     	{
     		// si on n'a pas trouvé
     		if(!preg_match('#^' . preg_quote($supplier->getName()) . '#i', $supplierInformation))
     		{

     			// on passe à la suivante
     			continue;
     		}

     		// on ajoute l'id du fournissur
     		$return['idSupplier'] = $supplier->getId();

     		// on renvoi les informations en supprimant le Provider
     		$return['supplierInformation'] = trim(str_replace(mb_strtolower($supplier->getName()), '', mb_strtolower($supplierInformation)));

     		// on renvoi le résultat
     		return $return;
     	}

     	// on n'a rien trouvé
     	return false;
     }



}