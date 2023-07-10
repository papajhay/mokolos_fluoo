<?php declare(strict_types=1);

namespace App\Service\Provider\Adesa;

use App\Entity\TOption;
use Psr\Log\LoggerInterface;

class BaseAdesa
{
    /**
	 * adresse API pour la dev
	 */
	const API_URL_DEV = 'https://api-preprod.myadesa.fr';

	/**
	 * adresse API pour la prod
	 */
	const API_URL_PROD = 'https://api.myadesa.fr';

	/**
	 * clef utilisé pour accéder à l'API
	 */
	const API_BEARER_TOKEN_PROD = '721484fa56ff5f4bd06230d1cbaf916d2bec71fa';

	/**
	 * clef utilisé pour accéder à l'API
	 */
	const API_BEARER_TOKEN_DEV = '721484fa56ff5f4bd06230d1cbaf916d2bec71fa';

	/**
	 * paramétre pour l'appel à l'API PROD
	 */
	const API_TYPE_PROD = 1;

	/**
	 * paramétre pour l'appel à l'API DEV
	 */
	const API_TYPE_DEV = 0;

	/**
	 * Quantité maximal autorisé pour les commandes en urgence
	 */
	const MAX_QUANTITY_RUSH = 5000;

	public function __construct(
	  private LoggerInterface $logger
	)
	{
	}


	/**
	 * renvoi le type d'API séléctionné entre prod et dev
	 * @return type
	 */
	private function _apiTypeSelected()
	{
		return self::API_TYPE_PROD;
//		return self::API_TYPE_DEV;
	}


	/**
	 * renvoi l'adresse de l'api
	 * @return string
	 */
	protected function _apiUrl()
	{
		// si on est sur l'API PROD
		if($this->_apiTypeSelected() == self::API_TYPE_PROD)
		{
			// on renvoi l'url de l'API PROD
			return self::API_URL_PROD;
		}
		// si on est sur l'API DEV
		else
		{
			// on renvoi l'url de l'API DEV
			return self::API_URL_DEV;
		}
	}


	/**
	 * renvoi le token pour l'API
	 * @return string
	 */
	private function _apiBearerToken()
	{
		// si on est sur l'API PROD
		if($this->_apiTypeSelected() == self::API_TYPE_PROD)
		{
			// on renvoi le token de l'API PROD
			return self::API_BEARER_TOKEN_PROD;
		}
		// si on est sur l'API DEV
		else
		{
			// on renvoi le token de l'API DEV
			return self::API_BEARER_TOKEN_DEV;
		}
	}

    /**
	 * on surcharge la fonction getLog pour en créé un si il n'existe pas
	 * @return type
	 */
	// public function getLog()
	// {
		// si on n'a pas encore de log
		// if(parent::getLog() == null)
		// {
			// on le créé
		// 	$this->setLog(TLog::initLog('API ' . $this->getNomFour()));
		// }

		// on renvoi le log
	// 	return parent::getLog();
	// }

    /**
	 * renvoi le masque PCRE pour récupérer le corp du mail de confirmation de commande
	 * @return string
	 */
	private function _pcreMailOrderConfirmationBody()
	{
		$return	 = '#';
		$return	 .= 'Votre commande&nbsp;<strong>(\d+)</strong>.*\n';
		$return	 .= '.*Produit:</span><span[^>]*>&nbsp;</span><strong><span[^>]*>([\d/]+)</span';
		$return	 .= '#';

		return $return;
	}


	/**
	 * masque PCRE pour trouver les mails de BAT validé
	 * @return string
	 */
	private function _pcreMailProofOk()
	{
		return '#Bon à tirer à valider - commande \#(\d+)$#';
	}


	/**
	 * masque PCRE pour trouver les mails de BAT refisé
	 * @return string
	 */
	private function _pcreMailProofRefused()
	{
		return '#\#(\d+): le B.A.T. a été refusé#';
	}


	/**
	 * renvoi le masque PCRE pour récupérer le sujet du mail d'expédition
	 * @return string
	 */
	private function _pcreMailShippingSubject()
	{
		return '#Expédition de votre commande étiquettes adhésives#';
	}


	/**
	 * renvoi le masque PCRE pour récupérer le sujet du mail de facture
	 * @return string
	 */
	private function _pcreMailInvoiceSubject()
	{
		return '#^Facture de votre commande (\d+)$#';
	}


	/**
	 * renvoi le masque PCRE pour récupérer le contenu du mail d'expédition
	 * @return string
	 */
	private function _pcreMailShippingBody()
	{
		$pcre	 = '#';
		$pcre	 .= '\D(\d+)\s+d\'étiquettes adhésives';
		$pcre	 .= '#';

		return $pcre;
	}


	/**
	 * renvoi le masque PCRE pour récupérer le détail des informations de colis
	 * @return string
	 */
	private function _pcreMailShippingDetail()
	{
		$pcre	 = '#';
		$pcre	 .= '.*de colis :\s*(\S.+\S)\s*\n';
		$pcre	 .= '(?:\s*\n)*';
		$pcre	 .= '<br />(.*)\n';
		$pcre	 .= '(?:\s*\n)*';
		$pcre	 .= '.*Transporteur :\s*([^<]*)<';
		$pcre	 .= '#';

		return $pcre;
	}


	/**
	 * renvoi le masque PCRE pour trouver lee mails de devis
	 * @return string
	 */
	private function _pcreMailQuote()
	{
		$masque	 = '#';
		$masque	 .= 'demande de devis';
		$masque	 .= '#';

		return $masque;
	}


	/**
	 * renvoi le masque PCRE pour trouver lee mails de montant de devis
	 * @return string
	 */
	private function _pcreMailQuoteAmount()
	{
		$masque	 = '#';
		$masque	 .= 'montant de votre devis';
		$masque	 .= '#';

		return $masque;
	}


	/**
	 * renvoi le masque PCRE pour récupérer l'url dans un lien href
	 * @return string
	 */
	private function _pcreUrlFromHref()
	{
		$pcre	 = '#';
		$pcre	 .= 'href="([^"]+)"';
		$pcre	 .= '#';

		return $pcre;
	}

    /**
	 * renvoi les dépendances
	 * @param TProduct $produit le produit
	 * @param string $idHost le site
	 * @return string les dépendance en string
	 */
	 protected function _dependencies($produit, $idHost)
	{
		$aDependance = [];

		// on récupére toutes les options values de ce produit
		$allOptionsData = $produit->getOptionsAndValues($idHost);

		// pour chaque option
		foreach($allOptionsData AS $optionData)
		{
			// si on n'est pas sur une option de type select
			if($optionData['opt_type_option'] != TOption::TYPE_OPTION_SELECT)
			{
				// on passe à l'option suivante
				continue;
			}

			// pour chaque option value
			foreach($optionData['paramProduitOptionValue'] AS $optionValue)
			{
				// on ajoute cette option value à notre tableau des dépendance
				$aDependance[] = $optionValue->getIdOptionValue();
			}
		}

		// on renvoi nos dépendance concaténer
		return implode('-', $aDependance);
	}

    /**
	 * Vérifie la quantité spécial pour savoir si il est dans les limittes du fournisseur
	 * @param TProduitHost|TProduit $produit	L'objet de produit
	 * @param int $quantityByModel						la quantité par modéle
	 * @param int $nbModels					le nombre de modéle différent
	 * @param int $minQuantity				la quantité mini
	 * @param int $maxQuantity				la quantité maxi
	 * @param int $quantityByRoll	[=null] le nombre d'étiquette par rouleau si applicable)
	 * @return bool true en cas de succés et false en cas de probléme
	 */
	 protected function _checkSpecialQuantity($quantityByModel, $nbModels, $minQuantity, $maxQuantity, $quantityByRoll = null)
	 {
		// calcul de la quantité total
		 $quantity = $quantityByModel * $nbModels;

		// si on a un soucis avec la quantité
		if($quantity < $minQuantity || $quantity > $maxQuantity)
		{
			// on ajoute un message d'erreur
			// channel supplier
			 $this->logger->error('La quantité totale doit être comprise ente ' . $minQuantity . ' exemplaires et ' . $maxQuantity . ' exemplaires');

			// et on ne va pas plus loin
			return false;
		}

		// si on n'a pas de quantité par rouleaux
		if($quantityByRoll == null)
		{
			// tout est bon
			return true;
		}

		// si on a un soucis avec la quantité
		if($quantityByRoll < $minQuantity || $quantityByRoll > $quantityByModel)
		{
			// on ajoute un message d'erreur
			$this->logger->error('La quantité doit être comprise ente ' . $minQuantity . ' exemplaires et le nombre d\'exemplaires par modèle');

			// et on ne va pas plus loin
			return false;
		}

		// tout est bon
		return true;
	}

    /**
	 * ajout des paramétre pour se loger sur l'API de Smartlabel
	 * @param Curl $curl
	 */
	protected function _curlParameterForLogin($curl)
	{
		// ajout du bearer token et des paramétre de version d'API
		$curl->setOpt(CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->_apiBearerToken(), 'accept: application/json;version=1.0'));
	}


	/**
	 * Traitement du mail de confirmation de commande
	 * @param AchattodbEmail $achattodbEmail	Objet AchattodbEmail
	 * @param string $supplierOrderId numéro de la commande chez le fournisseur
	 * @param int $orderIdRaw numéro de la commande ou des commandes
	 * @return bool true si tout se passe bien et false en cas de probléme
	 */
	// private function _manageMailOrderConfirmation(AchattodbEmail $achattodbEmail, $supplierOrderId, $orderIdRaw)
	// {
		// on recherche la commande fournisseur ou la créé au besoin
	// 	return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_PRODUCTION, $achattodbEmail, $this->multipleOrderNumberStringToArray($orderIdRaw));
	// }


	/**
	 * Traitement du mail de BAT validé
	 * @param AchattodbEmail $achattodbEmail	Objet AchattodbEmail
	 * @param string $supplierOrderId numéro de la commande chez le fournisseur
	 * @return bool true si tout se passe bien et false en cas de probléme
	 */
	// private function _manageMailProofOk(AchattodbEmail $achattodbEmail, $supplierOrderId)
	// {
		// on recherche la commande fournisseur ou la créé au besoin
	// 	return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_PRODUCTION, $achattodbEmail, null, null, null, null, 'BAT Validé');
	// }


	/**
	 * Traitement du mail de BAT refusé
	 * @param AchattodbEmail $achattodbEmail	Objet AchattodbEmail
	 * @param string $supplierOrderId numéro de la commande chez le fournisseur
	 * @return bool true si tout se passe bien et false en cas de probléme
	 */
	// private function _manageMailProofRefused(AchattodbEmail $achattodbEmail, $supplierOrderId)
	// {
		// on recherche la commande fournisseur ou la créé au besoin
	// 	return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_ERROR, $achattodbEmail, null, null, null, null, 'BAT Refusé par '. $this->getNomFour(), OrdersStatus::STATUS_DEPART_FAB_RETOUR);
	// }

    /**
	 * gestion des mail de facture
	 * @param AchattodbEmail $achattodbEmail le mail
	 * @param string $supplierOrderId numéro de la commande chez le fournisseur
	 * @return bool true en cas de succés et false en cas de probléme
	 */
	// protected function _manageMailInvoice(AchattodbEmail $achattodbEmail, $supplierOrderId)
	// {
		// on met à jour la commande fournisseur ou la créé au besoin. On passe la commande en exépdié au cas ou elle n'y soit pas déjà
	// 	return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_DISPATCHED, $achattodbEmail, null, null, null, null, $this->getNomFour() . ' a généré une facture.');
	// }

    /**
	 * traite un email d'expédition
	 * @param AchattodbEmail $achattodbEmail le mail
	 * @param int $supplierOrderId numéro de la commande chez le fournisseur
	 * @return boolean true si on a réussi à récupérer les infos et false si on a un probléme
	 */
	// private function _manageMailExpedition(AchattodbEmail $achattodbEmail, $supplierOrderId)
	// {
	// 	$matches	 = array();
	// 	$matchesUrl	 = array();

		//Notre tableau qui contient type du colis, les numero command et l'url
		// $atexteColis = array();

		// on recherche la commande fournisseur
		// $supplierOrder = TSupplierOrder::findBySupplierId($supplierOrderId, $this->getIdFour());

		// si on n'a pas trouvé la commande fournisseur
		// if($supplierOrder == null)
		// {
			// on ajoute un message d'erreur
		// 	$this->getLog()->Erreur('Commande founrisseur ' . $this->getNomFour() . ' "' . $supplierOrderId . '" non trouvée.');

		// 	return false;
		// }

		// mise à jour du statut si besoin
		// $supplierOrder->updateStatusIfAfterCurrent(TSupplierOrderStatus::ID_STATUS_DISPATCHED);

		// on récupére les commandes qui sont lié à notre commande fournisseur
		// $aOrderSupplierOrder = $supplierOrder->getAOrderSupplierOrder();

		// rien ne correspond
		// if(count($aOrderSupplierOrder) == 0)
		// {
			// on ajoute un message d'erreur
			// $this->getLog()->Erreur('Aucune commande lié à la commande fournisseur.');
			// $this->getLog()->Erreur(var_export($supplierOrder, true));

			// on quitte la fonction
		// 	return false;
		// }

		// Si on ne trouve pas les infos de colis dans le mail
		// if(!preg_match($this->_pcreMailShippingDetail(), $achattodbEmail->getMessage(), $matches))
		// {
			// on renvoi une erreur
		// 	$this->getLog()->Erreur('Info d\'expédition non trouvé.');
		// 	return false;
		// }

		// si on n'arrive pas à extraire les url
		// if(!preg_match_all($this->_pcreUrlFromHref(), $matches[2], $matchesUrl))
		// {
			// on renvoi une erreur
		// 	$this->getLog()->Erreur('Url de tracking non trouvé.');
		// 	return false;
		// }

		// création du texte du colis :
		// $atexteColis['numColis'] = explode(', ', $matches[1]);
		// $atexteColis['urlColis'] = $matchesUrl[1];

		// foreach(explode(', ', $matches[3]) as $carrierName)
		// {
			// suivant le transporteur
			// switch(trim($carrierName))
			// {
			// 	case 'Chronopost':
			// 		$idTransporteur = TTransporteur::ID_TRANSPORTEUR_CHRONOPOST;
			// 		break;

			// 	case 'La Poste':
			// 		$idTransporteur = TTransporteur::ID_CARRIER_COLISSIMO;
			// 		break;

			// 	case 'TNT':
			// 		$idTransporteur = TTransporteur::ID_TRANSPORTEUR_TNT;
			// 		break;

			// 	default:
					// on renvoi une erreur
			// 		$this->getLog()->Erreur('Transporteur inconnu "' . $matches[3] . '".');
			// 		return false;
			// }

			// récupération du transporteur
			// $carrier = TTransporteur::findByIdWithChildObject($idTransporteur);

			// ajout d'info au log
			// $this->getLog()->addLogContent('Livré par ' . $carrier->getTraNomComplet());

			// création du texte du colis :
		// 	$atexteColis['idTransporteur']	 = $carrier->getIdTransporteur();
		// 	$atexteColis['transporteur']	 = $carrier->getTraNomComplet();
		// }

		// si il manque un élément de tracking
		// if(count($atexteColis['numColis']) != count($atexteColis['urlColis']))
		// {
			// on renvoi une erreur
			// $this->getLog()->Erreur('Probléme avec le tracking du colis.');
			// $this->getLog()->Erreur(var_export($atexteColis, true));

			// dans ce cas on supprime toutes les info de colis
		// 	$atexteColis = array();
		// }

		// pour chaque commande correspondant à notre job
		// foreach($aOrderSupplierOrder as $orderSupplierOrder)
		// {
			// on change le statut de la commande et on envoi l'email
		// 	$orderSupplierOrder->getOrder()->setAsLivraison($this->getNomFour(), $atexteColis);
		// }

		// passage du mail en traité
	// 	$achattodbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED)
	// 			->save();

	// 	return true;
	// }
}