<?php declare(strict_types=1);

namespace App\Service\Provider\Adesa;

use App\Entity\TOption;
use App\Service\Provider\BaseProvider;

class BaseAdesa extends BaseProvider
{
    /**
     * address API pour la dev.
     */
    public const API_URL_DEV = 'https://api-preprod.myadesa.fr';

    /**
     * adresse API pour la prod.
     */
    public const API_URL_PROD = 'https://api.myadesa.fr';

    /**
     * clef utilisé pour accéder à l'API.
     */
    public const API_BEARER_TOKEN_PROD = '721484fa56ff5f4bd06230d1cbaf916d2bec71fa';

    /**
     * clef utilisé pour accéder à l'API.
     */
    public const API_BEARER_TOKEN_DEV = '721484fa56ff5f4bd06230d1cbaf916d2bec71fa';

    /**
     * paramétre pour l'appel à l'API PROD.
     */
    public const API_TYPE_PROD = 1;

    /**
     * paramétre pour l'appel à l'API DEV.
     */
    public const API_TYPE_DEV = 0;

    /**
     * Quantité maximal autorisé pour les commandes en urgence.
     */
    public const MAX_QUANTITY_RUSH = 5000;

    /**
     * renvoi le type d'API séléctionné entre prod et dev.
     * @return type
     */
    private function _apiTypeSelected(): int|type
    {
        return self::API_TYPE_PROD;
        //		return self::API_TYPE_DEV;
    }

    /**
     * renvoi l'adresse de l'api.
     */
    protected function _apiUrl(): string
    {
        // si on est sur l'API PROD
        if (self::API_TYPE_PROD === $this->_apiTypeSelected()) {
            // on renvoi l'url de l'API PROD
            return self::API_URL_PROD;
        }
        // si on est sur l'API DEV
        else {
            // on renvoi l'url de l'API DEV
            return self::API_URL_DEV;
        }
    }

    /**
     * renvoi le token pour l'API.
     */
    protected function _apiBearerToken(): string
    {
        // si on est sur l'API PROD
        if (self::API_TYPE_PROD === $this->_apiTypeSelected()) {
            // on renvoi le token de l'API PROD
            return self::API_BEARER_TOKEN_PROD;
        }
        // si on est sur l'API DEV
        else {
            // on renvoi le token de l'API DEV
            return self::API_BEARER_TOKEN_DEV;
        }
    }

    /**
     * on surcharge la fonction getLog pour en créé un si il n'existe pas.
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
     * renvoi le masque PCRE pour récupérer le corp du mail de confirmation de commande.
     */
    protected function _pcreMailOrderConfirmationBody(): string
    {
        $return = '#';
        $return .= 'Votre commande&nbsp;<strong>(\d+)</strong>.*\n';
        $return .= '.*Produit:</span><span[^>]*>&nbsp;</span><strong><span[^>]*>([\d/]+)</span';
        $return .= '#';

        return $return;
    }

    /**
     * masque PCRE pour trouver les mails de BAT validé.
     */
    protected function _pcreMailProofOk(): string
    {
        return '#Bon à tirer à valider - commande \#(\d+)$#';
    }

    /**
     * masque PCRE pour trouver les mails de BAT refisé.
     */
    protected function _pcreMailProofRefused(): string
    {
        return '#\#(\d+): le B.A.T. a été refusé#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le sujet du mail d'expédition.
     */
    protected function _pcreMailShippingSubject(): string
    {
        return '#Expédition de votre commande étiquettes adhésives#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le sujet du mail de facture.
     */
    protected function _pcreMailInvoiceSubject(): string
    {
        return '#^Facture de votre commande (\d+)$#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le contenu du mail d'expédition.
     */
    protected function _pcreMailShippingBody(): string
    {
        $pcre = '#';
        $pcre .= '\D(\d+)\s+d\'étiquettes adhésives';
        $pcre .= '#';

        return $pcre;
    }

    /**
     * renvoi le masque PCRE pour récupérer le détail des informations de colis.
     */
    protected function _pcreMailShippingDetail(): string
    {
        $pcre = '#';
        $pcre .= '.*de colis :\s*(\S.+\S)\s*\n';
        $pcre .= '(?:\s*\n)*';
        $pcre .= '<br />(.*)\n';
        $pcre .= '(?:\s*\n)*';
        $pcre .= '.*Transporteur :\s*([^<]*)<';
        $pcre .= '#';

        return $pcre;
    }

    /**
     * renvoi le masque PCRE pour trouver lee mails de devis.
     */
    protected function _pcreMailQuote(): string
    {
        $masque = '#';
        $masque .= 'demande de devis';
        $masque .= '#';

        return $masque;
    }

    /**
     * renvoi le masque PCRE pour trouver lee mails de montant de devis.
     */
    protected function _pcreMailQuoteAmount(): string
    {
        $masque = '#';
        $masque .= 'montant de votre devis';
        $masque .= '#';

        return $masque;
    }

    /**
     * renvoi le masque PCRE pour récupérer l'url dans un lien href.
     */
    protected function _pcreUrlFromHref(): string
    {
        $pcre = '#';
        $pcre .= 'href="([^"]+)"';
        $pcre .= '#';

        return $pcre;
    }

    /**
     * renvoi les dépendances.
     * @param  TProduit $produit le produit
     * @param  string   $idHost  le site
     * @return string   les dépendance en string
     */
    protected function _dependencies(TProduit $produit, string $idHost): string
    {
        $aDependance = [];

        // on récupére toutes les options values de ce produit
        $allOptionsData = $produit->getOptionsAndValues($idHost);

        // pour chaque option
        foreach ($allOptionsData as $optionData) {
            // si on n'est pas sur une option de type select
            if (TOption::TYPE_OPTION_SELECT !== $optionData['opt_type_option']) {
                // on passe à l'option suivante
                continue;
            }

            // pour chaque option value
            foreach ($optionData['paramProduitOptionValue'] as $optionValue) {
                // on ajoute cette option value à notre tableau des dépendance
                $aDependance[] = $optionValue->getIdOptionValue();
            }
        }

        // on renvoi nos dépendance concaténer
        return implode('-', $aDependance);
    }

    /**
     * Vérifie la quantité spécial pour savoir si il est dans les limittes du fournisseur.
     * @return bool true en cas de succés et false en cas de probléme
     */
    // private function _checkSpecialQuantity($quantityByModel, $nbModels, $minQuantity, $maxQuantity, $quantityByRoll = null)
    // {
    // calcul de la quantité total
    // $quantity = $quantityByModel * $nbModels;

    // si on a un soucis avec la quantité
    // if($quantity < $minQuantity || $quantity > $maxQuantity)
    // {
    // on ajoute un message d'erreur
    // \Supplier\Message::addError('La quantité totale doit être comprise ente ' . $minQuantity . ' exemplaires et ' . $maxQuantity . ' exemplaires');

    // et on ne va pas plus loin
    // 	return false;
    // }

    // si on n'a pas de quantité par rouleaux
    // if($quantityByRoll == null)
    // {
    // tout est bon
    // 	return true;
    // }

    // si on a un soucis avec la quantité
    // if($quantityByRoll < $minQuantity || $quantityByRoll > $quantityByModel)
    // {
    // on ajoute un message d'erreur
    // \Supplier\Message::addError('La quantité doit être comprise ente ' . $minQuantity . ' exemplaires et le nombre d\'exemplaires par modèle');

    // et on ne va pas plus loin
    // 	return false;
    // }

    // tout est bon
    // 	return true;
    // }

    /**
     * ajout des paramétre pour se loger sur l'API de Smartlabel.
     * @param Curl $curl
     */
    protected function _curlParameterForLogin($curl): void
    {
        // ajout du bearer token et des paramétre de version d'API
        $curl->setOpt(CURLOPT_HTTPHEADER, ['Authorization: Bearer '.$this->_apiBearerToken(), 'accept: application/json;version=1.0']);
    }
}
