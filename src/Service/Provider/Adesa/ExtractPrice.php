<?php declare(strict_types=1);

namespace App\Service\Provider\Adesa;

use App\Entity\TOption;
use App\Entity\Provider;
use App\Entity\TProduct;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use App\Repository\TAOptionProviderRepository;

// oldName:extractPrix
class ExtractPrice extends BaseAdesa
{
    public function __construct(
        private TAOptionProviderRepository $optionProviderRepository,
        private Provider $provider,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Recupere les prix pour les afficher
     * Insere les options/optionsValues qui n'existent pas en base.
     * @param  array  $aIdOptionValue un tableau des options values
     * @param  string $idHost         Id du site
     * @return array  un tableau avec beaucoup de trucs dedans
     */
    public function price(TProduct $product, string $idHost, array $aIdOptionValue, $taOptionProvider, $optionValueProvider): bool|array
    {
        // initialisation des variables nécessaire
        $aSelection = [];
        $aSelectionText = [];
        $aParamRequest = ['qty_labels_per_roll' => null];
        $return = [];
        $nbModels = 1;
        $error = 0;

        // gestion des délai. On met un délai par défaut pour éviter tout probléme
        $return['tabDelay'][0]['fabrication'] = 4;
        $return['idDelaySelected'] = 0;

        // pas de gestion des prix dans les délai pour ce fournisseur
        $return['tabDelay'][0]['price'] = null;

        // gestion des quantités par défaut en cas de probléme
        $return['idQuantitySelected'] = 500;

        // quantité min et max par défaut en cas de bot
        $minQuantity = 50;
        $maxQuantity = 5000;

        // récupération des dépendance
        $return['dependance'] = $this->_dependencies($product, $idHost);

        // on regarde tous ce qu'on a recu en paramétre pour ne garder que la selection
        foreach ($aIdOptionValue as $idOption => $idOptionValue) {
            // si il ne s'agit pas d'une option
            if (!is_numeric($idOption)) {
                // on passe au suivant
                continue;
            }

            // on récupére l'optionFournisseur pour classé notre selection dans le bon sens
            $optionProvider = $this->optionProviderRepository->findByIdOptionProvider($this->provider->getId(), $idOption);

            // si cette option n'existe pas
            if (null === $optionProvider) {
                // on ajoute un log d'erreur
                // $this->monolog->channels(['Erreur de récupération de prix du fournisseur ' . $this->prov->getName()]);
                //				$this->logger->error('Erreur de récupération de prix du fournisseur ' . $this->provider->getName());
                //				$this->logger->error('L\'option ' . $idOption . ' ne semble pas éxisté chez le fournisseur ' . $this->provider->getName());
                //				$this->logger->error('Vérifier la configuration du produit ' . $product->getLibelle());

                // on tente de passer à l'option suivante
                continue;
            }

            // on ajoute l'id de l'option à notre selection
            $aSelection[] = $idOptionValue;

            // si on est sur l'option du format (pour les planches)
            if (TOption::SPECIAL_OPTION_FORMAT === $taOptionProvider->getOption()->getOptSpecialOption()) {
                // on récupére hauteur et largeur
                $aParamRequest['height'] = $optionValueProvider->getOptValIdSource();
                $aParamRequest['width'] = $optionValueProvider->getOptValProductAlias();

                // on passe à l'option suivante
                continue;
            }

            // si on est sur l'option qui contient le délai
            if (TOption::SPECIAL_OPTION_SUPPORT === $taOptionProvider->getOption()->getOptSpecialOption()) {
                // on récupére le délai correct
                $return['tabDelay'][0]['fabrication'] = $optionValueProvider->getOptValProductAlias();
            }

            // on ajoute les paramétre à la requête vers l'API
            $aParamRequest[$taOptionProvider->getOptIdSource()] = $optionValueProvider->getOptValFouIdSource();
        }

        // si on a la quantité par rouleau indéfinit
        if ('0' === $aParamRequest['qty_labels_per_roll']) {
            // on prend la quantité total
            $aParamRequest['qty_labels_per_roll'] = $return['idQuantitySelected'];
        }

        // si on a une commande urgente
        if (1 === $return['tabDelay'][0]['fabrication']) {
            // on modifie la quantité max
            $maxQuantity = BaseAdesa::MAX_QUANTITY_RUSH;

            // on ajoute une information
            $this->logger->info('La quantité maximum est limité à '.BaseAdesa::MAX_QUANTITY_RUSH.' exemplaires pour les commandes en urgence.');
        }

        // si on a un soucis avec la quantité séléctionné
        if (!$this->_checkSpecialQuantity($return['idQuantitySelected'], $nbModels, $minQuantity, $maxQuantity, $aParamRequest['qty_labels_per_roll'])) {
            // on indique une erreur
            ++$error;
        }

        // initialisation de la quantité
        $aParamRequest['qty_labels_per_serie'] = [];

        // pour chaque nombre de modéle
        for ($model = 1; $model <= $nbModels; ++$model) {
            // on rajoute une quantité
            $aParamRequest['qty_labels_per_serie'][] = ['quantity' => $return['idQuantitySelected']];
        }

        // si on a une erreur
        if ($error > 0) {
            // on quitte la fonction
            return false;
        }

        // initialisation du client HttpClient
        $client = HttpClient::create();

        // URL de l'API
        $url = $this->_apiUrl().'/quote';

        // paramètres de connexion à l'API de Smartlabel
        $options = [
            'headers' => [
                // Ajoutez vos headers de connexion ici
            ],
            'body' => json_encode($aParamRequest),
        ];

        // exécution de la requête
        // todo change to native curl
        $response = $client->request('POST', $url, $options);

        // récupération de la réponse
        $page = $response->getContent();

        // decodage du json
        $aResponse = json_decode($page, true);

        // si on n'arrive pas à décoder la réponse
        if (null === $aResponse) {
            //            $this->logger->error('Impossible de décoder la réponse de l\'API');
            //            $this->logger->error(var_export($page, true));

            // on quitte la fonction
            return false;
        }

        // si on a un message d'erreur
        if (isset($aResponse['message'])) {
            //            $this->logger->error('Erreur lors de l\'appel à l\'API');
            //            $this->logger->error(var_export($aResponse, true));

            // on quitte la fonction
            return false;
        }

        // si on n'arrive pas à récupérer le prix
        if (!isset($aResponse['price'])) {
            //            $this->logger->error('Erreur lors de l\'appel à l\'API');
            //            $this->logger->error('Prix introuvable');
            //            $this->logger->error(var_export($aResponse, true));

            // on quitte la fonction
            return false;
        }

        // ajout de la selection à notre valeur de retour
        $return['selection'] = implode('-', $aSelection);
        $return['selectionText'] = json_encode($aSelectionText);

        // ajout des prix à notre selection
        // todo : create entity prix
        //  $return['tabPrice'][$return['idQuantitySelected']] = array('prix' => new Prix($aResponse['price']), 'quantite' => $return['idQuantitySelected']);

        return $return;
    }
}
