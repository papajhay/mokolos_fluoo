<?php
declare(strict_types=1);
namespace App\Service\Provider;

use App\Entity\Provider;
use App\Entity\AchattodbEmai;
use App\Entity\TSupplierOrder;
use App\Entity\TSupplierOrderStatus;
use App\Repository\TSupplierOrderRepository;

class BaseProvider
{

    public function __construct(
        private TSupplierOrderRepository $TSupplierOrderRepository,
        private Provider $provider
    ){}

     /**
      * Récupére la commande Provider ou la créé si elle n'existe pas
      * @param string $supplierOrderId id de la commande chez le Provider
      * @param AchattodbEmail|null $achattodbEmail [=null] si on fournit un mail il sera mis à retraité
      * @param int|int[] $idOrder [=null] id de la commande ou des commandes si on a un array (pour une eventuelle création)
      * @param int $idSupplierOrderStatus [=TSupplierOrderStatus::ID_STATUS_PRODUCTION] statut de la commande Provider (pour une eventuelle création)
      * @param date $deliveryDate [=null] date de livraison de la commande Provider (pour une eventuelle création)
      * @param float $ordSupOrdPriceWithoutTax [=null] prix d'achat HT TOTAL de la commande Provider (pour une eventuelle création)
      * @param type $jobId [=null] id du job (pour une eventuelle création)
      * @return TSupplierOrder|false la commande Provider ou FALSE si rien ne correspond
      */
     public function orderSupplier($supplierOrderId, $achattodbEmail = null, $idOrder = null, $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRODUCTION, $deliveryDate = null, $ordSupOrdPriceWithoutTax = null, $jobId = null): TSupplierOrder
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
     	$supplierOrder = $this->TSupplierOrderRepository->findBySupplierId($supplierOrderId, $this->provider->getId(), $jobId, $idOrder);

     	// si on a trouvé la commande Provider
     	if($supplierOrder != null)
     	{
     		// tout est bon
     		return $supplierOrder;
     	}

     	// on recupere la 1er commande
     	$order = order::findById($aIdOrder[0]);

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
     			$achattodbEmail->needReprocess();
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
     	$newSupplierOrder = $this->TSupplierOrderRepository->updatePreOrderOrCreateNew($aIdOrder[0], $deliveryDate, $this->provider->getId(), $ordSupOrdPriceWithoutTax, $supplierOrderId, $idSupplierOrderStatus, $jobId);

     	// pour chaque commande
     	foreach($aIdOrder as $key => $idOrder)
     	{
     		// si on est sur la 1er commande
     		if($key == 0)
     		{
     			// on ne fait rien car elle est lié à notre commande Provider
     			continue;
     		}

     		// on va lié la commande et la commande Provider
     		//TAOrderSupplierOrder::createNew($idOrder, $newSupplierOrder->getIdSupplierOrder(), $deliveryDate, $ordSupOrdPriceWithoutTax, $jobId);
     	}

     	// tout est bon
     	return $newSupplierOrder;
     }

     /**
      * Récupére la commande Provider ou la créé si elle n'existe pas puis la met à jour
      * @param string $supplierOrderId id de la commande chez le Provider
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
     public function updateOrderSupplier($supplierOrderId, $idSupplierOrderStatus = TSupplierOrderStatus::ID_STATUS_PRODUCTION, $achattodbEmail = null, $idOrder = null, $deliveryDate = null, $ordSupOrdPriceWithoutTax = null, $jobId = null, $additionnalComment = '', $idOrderStatus = null, $aDeliveryInformation = null)
     {
     	// on recherche la commande Provider
     	$supplierOrder = $this->orderSupplier($supplierOrderId, $achattodbEmail, $idOrder, $idSupplierOrderStatus, $deliveryDate, $ordSupOrdPriceWithoutTax, $jobId);

     	// si on n'a pas trouvé la commande Provider
     	if($supplierOrder == false)
     	{
     		// on quitte la fonction
     		return false;
     	}

         //TODO add linkWithAllOrder in Service
     	// relie la commande Provider à toutes les commandes nécessaire
     	$supplierOrder->linkWithAllOrder($idOrder);

     	// récupération du statut de la commande
     	$supplierOrderStatus = TSupplierOrderStatus::findById(array($idSupplierOrderStatus));

     	// si on a mis à jour le statut
     	if($supplierOrder->updateStatusIfAfterCurrent($idSupplierOrderStatus))
     	{
     		// on ajoutera un commentaire dans l'historique pour la commande
     		$comment = 'La commande ' . $this->provider->getName() . ' "' . $supplierOrderId . '" est en "' . $supplierOrderStatus->getSupOrdStaName() . '".<br>';
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

         //TODO CheckOrderLinkedToSupplierOrderWithJob in service
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
     		if($deliveryDate != null && $deliveryDate->format(DateHeure::DATEMYSQL) != $orderSupplierOrder->getOrdSupOrdDeliveryDate())
     		{
     			// on modifie la date de livraison
     			$orderSupplierOrder->setOrdSupOrdDeliveryDate($deliveryDate->format(DateHeure::DATEMYSQL))
     					->save();

     			// on ajoute un commentaire
     			$comment .= 'Nouvelle date de livraison : ' . $deliveryDate->format(DateHeure::DATEFR);
     		}

     		// si on passe la commande en expédié
     		if($idOrderStatus == OrdersStatus::STATUS_EXPEDITION)
     		{
     			// on passe la commande en livraison
     			$orderSupplierOrder->getOrder()->setAsLivraison($this->provider->getName(), $aDeliveryInformation, $comment, $this->provider->getName());
     		}
     		elseif($idOrderStatus == OrdersStatus::STATUS_LIVRE)
     		{
     			// on passe la commande en livre
     			$orderSupplierOrder->getOrder()->setAsDelivered($comment, $this->provider->getName());
     		}
     		// si on doit changer la commande de statut
     		elseif($idOrderStatus != null)
     		{
     			// mise à jour de la commande
     			$orderSupplierOrder->getOrder()->updateStatus($idOrderStatus, $comment, OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI, '', '', $this->provider->getName());
     		}
     		// si on a un commentaire à ajouter
     		elseif(trim($comment) != '')
     		{
     			// on ajoute un historique à la commande
     			$orderSupplierOrder->getOrder()->addHistory($comment, 0, 0, '', '', $this->provider->getName());
     		}
     	}

     	// passage du mail en traité
     	$achattodbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED)
     			->save();

     	// tout est bon
     	return true;
     }

}