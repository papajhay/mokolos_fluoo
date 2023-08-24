<?php declare(strict_types=1);

namespace App\Service\Provider\RealisaPrint;

use App\Entity\TProduct;
use App\Helper\Client;
use App\Helper\Curl;
use App\Helper\Supplier\Dependency;
use App\Service\Provider\BaseProvider;

class BaseRealisaPrint extends BaseProvider
{
    /**
     * nombre de jour à rajouter pour la livraison.
     */
    public const NB_JOUR_LIVRAISON = 2;

    /**
     * Option pour la gestion des pays.
     */
    public const CONFIGURATION_COUNTRY = 'country';

    /**
     * id du code pays par défaut.
     */
    public const COUNTRY_DEFAULT_ID = 'FR';

    /**
     * adresse du ftp pour l'envoi des fichier.
     */
    public const FTP_HOST = '212.83.133.189';

    /**
     * port du ftp pour l'envoi des fichier.
     */
    public const FTP_PORT = 21;

    /**
     * mode du ftp pour l'envoi des fichier.
     */
    // public const FTP_CONNEXION_MODE = Ftp::CONNEXION_MODE_FTP_UNSECURE;

    /**
     * login du ftp pour l'envoi des fichier.
     */
    public const FTP_LOGIN = 'fluoo_files';

    /**
     * mot de passe du ftp pour l'envoi des fichier.
     */
    public const FTP_PASS = 'fluoo_files75';

    /**
     * Code texte court du délai standard chez le fournisseur.
     */
    public const DELAY_TEXT_SHORT_STANDARD = 'std';

    /**
     * Code texte court du délai express chez le fournisseur.
     */
    public const DELAY_TEXT_SHORT_EXPRESS = 'xps';

    /**
     * Code texte court du délai urgence chez le fournisseur.
     */
    public const DELAY_TEXT_SHORT_EMERGENCY = 'urg';

    /**
     * Url pour récupéré les gabarit chez le fournisseur.
     */
    public const URL_TEMPLATE_DOWNLOAD = 'https://www.realisaprint.com/api/';

    /**
     * objet Curl qui va gérer tous les appels à l'API.
     */
    protected Curl $_curl;

    protected Client $_client;

    /**
     * sous objet gérant les dépendance.
     * @var \Supplier\Dependency
     */
    private $_dependency;

    /**
     * renvoi l'objet curl pour aller sur l'API.
     */
    public function getCurl(string $url,  array $param): Curl
    {
        // si on n'a pas initialisé le curl
//        if (null === $this->_curl) {
//            // on initialise le curl
//            $this->_initCurl();
//        }

         $this->_initCurl($url, $param);
         return $this->_curl;
    }

    public function getClient(string $url, array $param): Client
    {
        $this->_client= new Client($url, $param);
        return $this->_client;
    }

    /**
     * initialise la requête curl avec les bons paramétres.
     */
    protected function _initCurl(string $url, array $param): void
    {
        // initialisation de la requête curl
        $this->_curl = new Curl($url, $param);

        // gestion des cookie
        //$this->_curl->setOptCookieFile($this->curlCookiePath(rand(0, 10000)));
    }

    /**
     * renvoi le log.
     * @return TLog
     */
    //    public function getLog()
    //    {
    //        // si on n'a pas encore de log
    //        if (null === $this->log) {
    //            // on en créé un
    //            $this->log = TLog::initLog('Recherche de prix '.$this->getNomFour());
    //        }
    //
    //        return $this->log;
    //    }

    /**
     * renvoi l'objet gérant les dépendance.
     */
    protected function getDependency(): Dependency
    {
        // si on n'a pas initialisé notre objet
        if (null === $this->_dependency) {
            // on l'initialise
            $this->_dependency = new Dependency();
        }

        return $this->_dependency;
    }

    /**
     * renvoi le masque PCRE quand la commande passe en impression.
     */
    protected function _pcreOrderInProduction(): string
    {
        $masque = '#';
        $masque .= 'n°(\d+)(?:_\d+|)\s*:\s*\[\D*(\d+)\D*(?:/\d+|)\].*conforme.*actuellement.*impression.*\n';
        $masque .= '.*Expédition prévue\s*(?:au plus tard|)\s*le : (\d\d/\d\d/\d\d\d\d)';
        $masque .= '#i';

        return $masque;
    }

    /**
     * renvoi le masque PCRE quand le fournisseur à reçu les fichiers.
     */
    protected function _pcreMailFileReceivedSubject(): string
    {
        return '#ception fichier.*n°(\d+)(?:_\d+|)\s*:\s*\[\D*(\d+)\D*(?:/\d+|)\]#i';
    }

    /**
     * renvoi le masque PCRE quand la commande passe en expedition.
     */
    protected function _pcreMailDispatchedSubject(): string
    {
        return '#°(\d+)(?:_\d+|)\s*:\s*\[\D*(\d+)\D*(?:/\d+|)\] est en expédition#i';
    }

    /**
     * renvoi le masque PCRE quand la commande passe en expedition.
     */
    protected function _pcreMailDispatchedReprintSubject(): string
    {
        return '#°(\d+)(?:_\d+|)\s*:\s*\[REIMPRESSION CDE[^\]]*\] est en expédition#i';
    }

    /**
     * renvoi le masque PCRE quand la commande passe en expedition.
     */
    protected function _pcreMailErrorSubject(): string
    {
        return '#Problème bloquant.*n°(\d+)\s*:\s*\[\D*(\d+)\D*\]#i';
    }

    /**
     * renvoi le masque PCRE quand la commande passe en expedition.
     */
    protected function _pcreMailErrorSubject2(): string
    {
        return '#n°(\d+)\s*:\s*\[\D*(\d+)\D*\] bloquée lors#i';
    }

    /**
     * renvoi le masque PCRE quand la commande passe en expedition.
     */
    protected function _pcreMailErrorSubject3(): string
    {
        return '#n°(\d+)\s*bloquée à.*:\s*\[\D*(\d+)\D*\]#i';
    }

    /**
     * renvoi le masque PCRE pour les mails de réclamation.
     */
    protected function _pcreMailReclamationSubject(): string
    {
        return '#Réclamation.*\D(\d+)\s*:\s*\[\D*(\d+)\D#i';
    }

    /**
     * renvoi le masque PCRE pour les mails de réclamation.
     */
    protected function _pcreMailReclamationInProgressBody(): string
    {
        return '#Votre réclamation n°\d+ pour le commande n°(\d+) est actuellement en cours d\'analyse#i';
    }

    /**
     * renvoi le masque PCRE pour les mails de suivi.
     */
    protected function _pcreMailFollowingSubject(): string
    {
        return '#Suivi.*\D(\d+)\s*:\s*\[\D*(\d+)\D#i';
    }

    /**
     * renvoi le masque PCRE pour le sujet du mail de réimpression.
     */
    protected function _pcreMailReprintSubject(): string
    {
        return '#Votre commande n°(\d+)\s*:\s*\[REIMPRESSION CDE\s*:\s*(\d+)\] est en préparation#i';
    }

    /**
     * renvoi le masque PCRE pour le corp du mail de réimpression.
     */
    protected function _pcreMailReprintBody(): string
    {
        return '#Expédition prévue au plus tard le\s*:\s*(\d+/\d+/\d+)\D#i';
    }

    /**
     * renvoi le masque PCRE pour le sujet du mail de réimpression (autre mail).
     */
    protected function _pcreMailReprint2Subject(): string
    {
        return '#Commande n°(\d+)\s*:\s*\[REIMPRESSION CDE\s*:\s*(\d+)\] générée avec succès#i';
    }

    /**
     * renvoi le masque PCRE pour le corp du mail de réimpression.
     */
    protected function _pcreMailReprint2Body(): string
    {
        return '#Montant TTC\s*:\s*(\d+)€#i';
    }

    /**
     * renvoi le masque PCRE quand la commande passe en impression.
     */
    protected function _pcreMailReprintinProductionBody(): string
    {
        $masque = '#';
        $masque .= 'n°(\d+)(?:_\d+|)\s*:\s*\[REIMPRESSION CDE\s*:\s*(\d+)_\d*\s*\].*conforme.*actuellement.*impression.*\n';
        $masque .= '.*Expédition prévue\s*(?:au plus tard|)\s*le : (\d\d/\d\d/\d\d\d\d)';
        $masque .= '#i';

        return $masque;
    }

    /**
     * renvoi le masque PCRE quand la commande est non livrée suite à l'absence du destinataire.
     */
    protected function _pcreMailOrderNotDeliveredBody(): string
    {
        $masque = '#';
        $masque .= 'Commande\s*n°\s*(\d+)\s*:\s*\[(\d+)\]';
        $masque .= '#';

        return $masque;
    }

    /**
     * renvoi le masque PCRE quand la commande est bloquée.
     */
    protected function _pcreMailOrderBlockedSubject(): string
    {
        $masque = '#';
        $masque .= '^Votre commande\s*n°\s*(\d+)\s*:\s*\[(\d+)\]\s*est toujours bloquée$';
        $masque .= '#';

        return $masque;
    }

    /**
     * renvoi le pasque PCRE pour récupérer un élément javascript window.location.
     */
    private function _pcreWindowLocation(): string
    {
        return '#window\.location=\'(http[^\']+)\';#';
    }

    /**
     * renvoi le pasque PCRE pour récupérer le code du gabarit dans l'url du gabarit.
     */
    protected static function _pcreTemplateCodeFromeUrl(): string
    {
        return '#https://www\.realisaprint\.com/api/([^/\.]+)\.php#';
    }

    /**
     * Renvoi un nom d'option value e modifiant les '------' du fournisseur.
     * @param  string $name le nom du fournisseur
     * @return string le nom pour nous
     */
    public static function optionValueNameFromSupplier(string $name): string
    {
        // si on a le nom par défaut
        if ('-----' === $name) {
            // on renverra un nom plus approrié
            return 'Non';
        }

        // on renvoi le nom du fournisseur
        return $name;
    }

    /**
     * Transforme la selection fournisseur pour l'API.
     * @param  array       $aSelectionSource selection fournisseur
     * @return array|false le tableau pour l'API ou false en cas de probléme
     */
    private function _parametersForApi(array $aSelectionSource): bool|array
    {
        // paramétre pour l'API
        $return = [];

        // on supprime la variable de pays
        unset($aSelectionSource[$this->CONFIGURATION_COUNTRY]);

        // on ajoute les variables
        $return['variables'] = $aSelectionSource;

        return $return;
    }

    /**
     * appel de l'API qui affiche les variables a utiliser.
     * @param  TProduit   $product          L'objet de produit
     * @param  array      $aSelectionSource paramétre pour l'API
     * @return json|false la réponse JSON ou false en cas de gros soucis
     */
    protected function _apiShowVariables(TProduit $product, array $aSelectionSource): bool|json
    {
        // paramétre pour l'API
        $aParameters = $this->_parametersForApi($aSelectionSource);

        // si on a un soucis dans les paramétre
        if (false === $aParameters) {
            // on quitte la fonction
            return false;
        }

        // ajout du produit dans les paramétre de l'API
        $aParameters['product'] = $product->getIdProduitSrc();
        $aParameters['stock'] = $product->getIdProduitSrcGroup();

        // envoi une requête à l'API show variables
        return $this->_apiRequest('show_variables', $aParameters);
    }

    /**
     * appel de l'API save_configuration.
     * @param  TProduct   $product          L'objet de produit
     * @param  array      $aSelectionSource paramétre pour l'API
     * @return json|false la réponse JSON ou false en cas de gros soucis
     */
    protected function _apiSaveConfiguration(TProduct $product, array $aSelectionSource): bool|json
    {
        // paramétre pour l'API
        $aParameters = $this->_parametersForApi($aSelectionSource);

        // si on a un soucis dans les paramétre
        if (false === $aParameters) {
            // on quitte la fonction
            return false;
        }

        // ajout du produit dans les paramétre de l'API
        $aParameters['product'] = $product->getIdProduitSrc();
        $aParameters['stock'] = $product->getIdProduitSrcGroup();

        // envoi une requête à l'API configuurations
        return $this->_apiRequest('save_configuration', $aParameters);
    }

    /**
     * Envoi une requête à l'API du fournisseur.
     * @param string $url url de l'API
     * @param array $aParameters paramétres de l'API
     * @return array un tableau avec la réponse de l'API ou false en cas de probléme
     */
    protected function _apiRequest(string $url,?array $aParameters = []): array
    {

        $aParameters += [
            'shop_id' => 75,
            'api_key' => 'f9f9030e6e2b7975770fe02f175a5627'
        ];

        $finalUrl='https://www.realisaprint.com/api/'.$url;


        // on récupére la réponse
        $client = $this->getClient($finalUrl, $aParameters);

        $page = $client->execute();

        // décodage du json
        $json = json_decode($page, true);

        // si on n'a pas réussi à décoer le json
        if (false === $json) {
            // on ajoute une erreur au log
            //            $this->getLog()->Erreur('Impossible de décoder le JSON de la réponse de l\'API ' . $this->getNomFour());
            //            $this->getLog()->Erreur('Réponse  : ' . var_export($page, true));
            //            $this->getLog()->addLogContent('Url : ' . $url);
            //            $this->getLog()->addLogContent('Paramétres  : ' . var_export($aParameters, true));

            // on quitte la fonction
            return false;
        }

        // si on a une erreur
        if (isset($json['error'])) {
            // on ajoute l'erreur dans le log
            //            $this->getLog()->Erreur($json['error']);
            //            $this->getLog()->addLogContent('Url : ' . $url);
            //            $this->getLog()->addLogContent('Paramétres  : ' . var_export($aParameters, true));
            //            $this->getLog()->Erreur('Réponse  : ' . var_export($json, true));
        }

        // on renvoi le json
        return $json;
    }

    /**
     * appel de l'API get_price.
     * @param  int             $productCode Le code du produit chez le fournisseur provenant de l'API
     * @param  int             $quantity    La quantité de produit désiré
     * @param  string          $countryCode [=FournisseurRealisaprint::COUNTRY_DEFAULT_ID] le code ISO pour la livraison
     * @return bool|array|json la réponse JSON ou false en cas de gros soucis
     */
    public function _apiGetPrice(int $productCode, int $quantity, string $countryCode = BaseRealisaPrint::COUNTRY_DEFAULT_ID): bool|array|json
    {

        // ajout du produit dans les paramétre de l'API
        $aParameters = [
            'code' => $productCode,
            'quantity' => $quantity,
            'country' => $countryCode
    ];

        // envoi une requête à l'API configuurations
        return $this->_apiRequest('get_price', $aParameters);
    }

    /**
     * appel de l'API product
     * @return array|json la réponse JSON ou false en cas de gros soucis
     */
    public function _apiProduct(): json|array
    {
        // envoi une requête à l'API products
        return $this->_apiRequest("products");
    }

    /**
     * appel de l'API configurationsr
     * @param string|TProduct $product L'objet de produit ou le code de l'API
     * @return bool|array|json la réponse JSON ou false en cas de gros soucis
     */
    public function _apiConfigurations(TProduct|string $product): bool|array|json
    {
        // si on a un objet
        if(is_object($product))
        {
            // on envoi l'id source du produit dans l'API
            $aParameters = [
                'product' => $product->getTAProductProvider()->getIdSource(),
            ];
        }
        else
        {
            // on renvoi directement le code dans l'API
            $aParameters = [
                'product' => $product,
            ];
        }

        // envoi une requête à l'API configuurations
        return $this->_apiRequest('configurations', $aParameters);
    }

}
