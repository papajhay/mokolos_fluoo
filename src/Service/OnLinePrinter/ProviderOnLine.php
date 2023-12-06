<?php

namespace App\Service\OnLinePrinter;

use App\Entity\AchattodbEmail;
use App\Entity\Hosts;
use App\Entity\Provider;
use App\Entity\TLockProcess;
use App\Entity\TOption;
use App\Entity\TOptionValue;
use App\Entity\TProduct;
use App\Entity\TSupplierOrder;
use App\Entity\TSupplierOrderStatus;
use App\Enum\RealisaPrint\SpecialOptionEnum;
use App\Enum\RealisaPrint\TypeOptionEnum;
use App\Helper\Curl;
use App\Repository\ProviderRepository;
use App\Service\Provider\BaseProvider;
use App\Service\TAProductOptionService;
use App\Service\TOptionService;

class ProviderOnLine extends BaseProvider
{
    /**
     * séparateur utilisé pour la selection de ce fournisseur
     */
    public const SEPARATEUR_SELECTION = '|';

    /**
     * Nom de l'option des délai chez le fournisseur
     */
    public const OPTION_ONLINE_DELAI = 'Délai de production';

    /**
     * Nom de l'option des quantité chez le fournisseur
     */
    public const OPTION_ONLINE_QUANTITE = 'Tirage';

    /**
     * Nom de l'option des quantité (alternatif) chez le fournisseur
     */
    public const OPTION_ONLINE_QUANTITE2 = 'Tirage/Nombre de blocs';

    /**
     * Nom de l'option des quantité (pour les textiles) chez le fournisseur
     */
    public const OPTION_ONLINE_QUANTITE3 = 'Quantité';

    /**
     * code HTML du fournisseur à chaque début délément de configuration du produit
     */
    public const PRODUCT_CONFIG_START_TAG = '<div class="productConfigItem opidProductConfigItem';

    /**
     * code HTML du fournisseur à chaque début délément de configuration du produit
     */
    public const PRODUCT_CONFIG_ITEM_START_TAG = 'opidProductConfigItemHeadTitleText ';

    /**
     * l'index du produit chez online. utilisé pour les régles de produit
     * @var string
     */
    protected string $_prodIndex = '';

    /**
     * id de l'option value du délai séléctionné
     * @var int
     */
    protected ?int $_idOptionValueDelaySelected = null;

    /**
     * tableau des option value de délai
     * @var array
     */
    protected array $_aIdOptionValueDelay ;

    /**
     * tableau de la selection pour online
     * @var array
     */
    protected array $tabSelectionOnline ;

    /**
     * tableau de la selection
     * @var array
     */
    protected array $tabSelection ;

    /**
     * tableau de la selection pour les élément de type texte
     * @var array
     */
    protected array $tabSelectionTxt = array();

    /**
     * tableau des id option value relatif au produit. obtenu sur les pages de type tableau/icone
     * @var int[]
     */
    protected $_aProductSelection = array();

    /**
     * Id du post de l'utilisateur
     * @var int[]
     */
    protected $_dataPost = array();

    /**
     * contient la liste des régles qui s'applique à ce produit
     * @var TOnlineProductRule[]
     */
    protected $_aProductRules = null;

    /**
     * objet Curl qui va gérer tous les appels au site online
     * @var Curl
     */
    protected Curl $_curl;
   

    /**
     * renvoi le log
     * @return TLog
     */
//    public function getLog()
//    {
        // si on n'a pas encore de log
//        if($this->log == NULL)
//        {
            // on en créé un
//            $this->log = TLog::initLog('Recherche de prix ' . $this->getNomFour());
//        }
//
//        return $this->log;
//    }


    /**
     * renvoi l'objet curl pour aller sur le site online
     * @return Curl
     */
    public function getCurl($url, $param)
    {
        // si on n'a pas initialisé le curl
//        if($this->_curl == null)
//        {
            // on initialise le curl
//            $this->initCurl();
//        }

        $this->initCurl($url, $param);

        return $this->_curl;
    }

    /**
     * initialise la requête curl avec les bon paramétre
     */
    public function initCurl($url, $param):void
    {
        // initialisation de la requête curl
        $this->_curl = new Curl($url, $param);

        // on utilisera un faux user agent
//        $this->_curl->setFakeAgent();

        // on active la directive follow redirection
//        $this->_curl->setOptFollowLocation(true);

        // gestion des cookie
//        $this->_curl->setOptCookieFile($this->curlCookiePath(rand(0, 10000)));

        // activation du mode utf8encode
//        $this->_curl->setUtf8Encode(true);
    }

    /**
     * renvoi le masque PCRE pour analyser la page avec les sous produit (ex : https://www.onlineprinters.fr/k/drapeaux )
     * @return string
     */
    private function _pcreSubProduct(): string
    {
        // initialisation du masque
        $masque = '#';

        $masque	 .= '<a href="[^"]*onlineprinters\.[a-z]+/([^"]+)"[^>]*class="mixedTileWrapper" el="cat_element">.*\n*';
        $masque	 .= '.*<h3[^>]*>([^<]+)</h3>';

        // fin du masque
        $masque .= '#';

        return $masque;
    }


    /**
     * renvoi le masque perl qui permet de récupéré une ligne des tableau online
     * @return string le masque
     */
    private function _masqueLigneTableau(): string
    {
        // initialisation du masque
        $masque	 = '#';
        $masque	 .= '<a href="[^"]*onlineprinters\.[a-z]+/([^"]*)"[^>]*>(?:<figure[^>]*><svg[^>]*><use[^>]*></use></svg><figcaption[^>]*>|)<h3[^>]*>(.*)</h3>';
        $masque	 .= '(?:<!--[^<]*-->|)';
        $masque	 .= '([^<]*)</(?:figcaption|a)>';
        $masque	 .= '#U';

        return $masque;
    }


    /**
     * renvoi le masque perl qui permet de récupéré un des tableau online dans le cas de plusieurs tableau
     * @return string le masque
     */
    private function _masqueMultiTableau(): string
    {
        // initialisation du masque
        $masque	 = '#';
        $masque	 .= '<section class="formatGroup"><h2 class="formatGroupTitle"[^>]*>([^<]*)</h2>(.*)</section>';
        $masque	 .= '#';

        return $masque;
    }


    /**
     * renvoi le masque perl qui permet de récupéré de la balise h1
     * @return string le masque
     */
    private function _pcreH1(): string
    {
        $masque	 = '#';
        $masque	 .= '<h1[^>]+>([^<]+)<';
        $masque	 .= '#';

        return $masque;
    }


    /**
     * renvoi le masque perl qui permet de récupéré le nom de l'option
     * @return string le masque
     */
    private function _pcreOptionTitle(): string
    {
        $masque	 = '#^';
        $masque	 .= '(?:.*\n|productConfigItemHeadTitleText">|opidNormalProductConfigItemHeadTitleText">)';
        $masque	 .= '([^<]+)</span>';
        $masque	 .= '#';

        return $masque;
    }


    /**
     * renvoi le masque perl qui permet de récupéré une option d'un des menu déroulant
     * attention ce masque est lié à masqueOptionMenuDeroulantShort ils douvent avoir le même nombre de parenthése
     * @return string le masque
     */
    protected function masqueOptionMenuDeroulant(): string
    {
        $masque = '#<input\s+type="checkbox"[^>]*name="([^"]+)"[^>]*value="([^"]+)"[^>]*data-prnumber="([^"]+)"[^>]*>.*</div>.*</div>#U';

        return $masque;
    }


    /**
     * renvoi le masque perl qui permet de récupéré une option d'un des menu déroulant quand cette option ne comprend pas de data right
     * attention ce masque est lié à masqueOptionMenuDeroulant ils douvent avoir le même nombre de parenthése
     * @return string le masque
     */
    protected function masqueOptionMenuDeroulantShort(): string
    {
        $masque = '#<input\s+type="checkbox"[^>]*name="([^"]+)"[^>]*value="([^"]+)"[^>]*data-prnumber="([^"]+)"[^>]*>#';

        return $masque;
    }


    /**
     * renvoi le masque perl qui permet de récupéré une option d'un des menu déroulant. Cas sépcifique quand il n'y a qu'une seul option dans une menu déroulant
     * attention ce masque est lié à masqueOptionMenuDeroulantShort ils douvent avoir le même nombre de parenthése
     * @return string le masque
     */
    private function _pcreSelectOptionUnique(): string
    {
        $pcre = '#<input\s+type="(?:checkbox|hidden)"[^>]*name="([^"]+)"[^>]*value="([^"]+)".*\n?.*data-prnumber="([^"]+)"#U';

        return $pcre;
    }


    /**
     * renvoi le masque perl qui permet de récupéré le prix dans la partie droite du menu déroulant
     * @return string le masque
     */
    protected function masqueOptionMenuDeroulantPrix(): string
    {
        $masque = '#&euro;\s*([\d,\.]+)[^\d,\.]#';

        return $masque;
    }


    /**
     * renvoi le masque perl qui permet de récupéré les options de type text
     * @return string le masque
     */
    private function _pcreOptionTypeText(): string
    {
        $masque = '#<input[^>]*type="text"[^>]*name="([^"]*)"[^>]*value="([^"]*)"[^>]*min="([^"]*)"[^>]*max="([^"]*)"[^>]*><label[^>]*>([^<]+)<#';

        return $masque;
    }


    /**
     * renvoi le masque perl qui permet de récupéré le formulaire dans lequel on récupereras les input hidden
     * @return string
     */
    private function _masqueFormProduct(): string
    {
        $masque = '#<form[^>]*action="([^"]*)"[^>]*id="productForm"[^>]*>.*</form>#sU';

        return $masque;
    }


    /**
     * renvoi le masque perl qui permet de récupérer les input hidden
     * @return string le masque
     */
    private function _masqueInputHidden(): string
    {
        $masque = '#<input\s+type="hidden"\s*[^>]*\s+name="([^"]+)"\s+value="([^"]*)">#';

        return $masque;
    }


    /**
     * renvoi le masque perl qui permet de récupéré le prix HT
     * @return string le masque
     */
    private function masquePrix(): string
    {
        $masque	 = '#';
        $masque	 .= 'productNetPrice\[0\]="([^"]+)"';
        $masque	 .= '#';

        return $masque;
    }


    /**
     * renvoi le masque pour savoir si l'option est séléctionné ou pas
     * @return string
     */
    protected function masqueSelected(): string
    {
        return '#checked#';
    }


    /**
     * renvoi le masque pour savoir si l'option est séléctionné ou pas
     * @return string
     */
    protected function masqueProduitIndisponible(): string
    {
        return '#plus\sdisponible#i';
    }


    /**
     * renvoi le masque pour trouver les supplément de jour ouvré
     * @return string
     */
    protected function masqueSupplementJourOuvre(): string
    {
        return '#<span class="ProductionTimeCalculation"[^>]*>Z(\d+)<#';
    }


    /**
     * masque pour récupéré les délai quand il ne sont pas dans un menu déroulant
     * @return string
     */
    private function _masqueDelaiHorsMenuDeroulant(): string
    {
        $masque	 = '#';
        $masque	 .= 'productPageDescriptionContentShipping.*\r?\n';
        $masque	 .= '(?:.*\r?\n){4,6}';
        $masque	 .= '.*\D(\d+\s*jours)\s+ouvr';
        $masque	 .= '#';

        return $masque;
    }


    /**
     * renvoi le masque qui récupére le délai max dans le délai online
     * @return string
     */
    private function _masqueDelaiMax(): string
    {
        return '#^(?:.*\D|)(\d+)\s*j#';
    }


    /**
     * masque pcre pour récupérer l'index du produit chez online. utilisé pour les régles de produit
     * @return string
     */
    private function _pcreProdIndex(): string
    {
        $pcre	 = '#';
        $pcre	 .= '\n';
        $pcre	 .= 'prodIndex\s*:\s*\'([^\']+)\'';
        $pcre	 .= '#';

        return $pcre;
    }


    /**
     * masque PCRE pour trouver le sujet du mail d'une nouvelle commande
     * @return string
     */
    private function _pcreMailNewOrderSubject(): string
    {
        return '#Votre commande numéro (\d+)$#';
    }


    /**
     * masque PCRE pour trouver le corp du mail d'une nouvelle commande
     * @return string
     */
    private function _pcreMailNewOrderBody(): string
    {
        return '#Votre texte de référence:\r*\n(\d+)\D#';
    }


    /**
     * masque PCRE pour trouver le sujet du mail d'erreur
     * @return string
     */
    private function _pcreMailErrorFileSubject(): string
    {
        return '#Demande de correction de vos données d’impression suite à votre commande (\d+)-(\d+)$#';
    }


    /**
     * masque PCRE pour trouver le sujet du mail de passage en production
     * @return string
     */
    private function _pcreMailProductionSubject(): string
    {
        return '#Information sur votre commande (\d+)-(\d+)$#';
    }


    /**
     * renvoi le masque PCRE pour récupérer le sujet du mail d'expédition d'une commande
     * @return string
     */
    private function _pcreMailDispatchedSubject(): string
    {
        $masque	 = '#';
        $masque	 .= 'Information sur l\'envoi de votre commande (\d+)-(\d+)';
        $masque	 .= '#';

        return $masque;
    }

    /**
     * renvoi le masque PCRE pour trouver les mails d'annulation de commande
     * @return string
     */
    private function _pcreMailOrderCanceledSubject(): string
    {
        return '#Annulation de votre commande (\d+)-(\d+)$#';
    }


    /**
     * renvoi le masque PCRE pour récupérer le sujet du mail de confirmation de commande
     * @return string
     */
    private function _pcreMailOrderConfirmationSubject(): string
    {
        return '#Merci pour votre commande:\s*(\d+)$#';
    }


    /**
     * renvoi le masque PCRE pour récupérer le corp du mail d'expédition d'une commande
     * @return string
     */
    private function _pcreMailDispatchedBody(): string
    {
        $masque	 = '#';
        $masque	 .= 'Numéro de colis\s*:*\s*<a href="([^"]*)"[^>]*><[^>]*>([^<]+)<';
        $masque	 .= '#';

        return $masque;
    }


    /**
     * renvoi le masque PCRE pour récupérer le sujet du mail de facture
     * @return string
     */
    private function _pcreMailInvoiceSubject(): string
    {
        $masque	 = '#';
        $masque	 .= 'Votre facture relative à la commande\s*-\s*(\d+)\s*$';
        $masque	 .= '#';

        return $masque;
    }


    /**
     * renvoi le masque PCRE pour récupérer le sujet du mail de réponse à une demande
     * @return string
     */
    private function _pcreMailResponseToARequestSubject(): string
    {
        return '#Votre demande auprès d.+Onlineprinters#';
    }


    /**
     * renvoi le masque PCRE pour récupérer le sujet du mail de structures non conformes
     * @return string
     */
    private function _pcreMailNonCompliantStructuresSubject(): string
    {
        return '#^[A-Z]+:[^\/]+\/\s(\d+)\s[A-Z]+\sTicket:\d+$#';
    }

    public function __construct(ProviderRepository $providerRepository, TOptionService $toptionService, TAProductOptionService $tAProductOptionService)
    {
        parent::__construct($providerRepository, $toptionService, $tAProductOptionService);
    }

    /**
     * créé ou met à jour toutes les options et option valu
     * @param string $sourceCode code source de la page fournisseur à analyser
     * @param Hosts $host id du site en cours
     * @param TProduct $product produiten cours
     * @return array un tableau avec les options et les options values
     */
    private function _createUpdateAllOptionAndOptionsValue(string $sourceCode, Hosts $host, TProduct $product): array
    {
        $return		 = [];
        $keyOption	 = 0;

        // on découpe tous les élément de configuration. On supprime le premier élément qui ne correspond à rien d'interessant.
        $allConfig = explode(ProviderOnLine::PRODUCT_CONFIG_START_TAG, $sourceCode);
        unset($allConfig[0]);

        // si on n'a pas récupéré les config
        if(count($allConfig) < 1)
        {
            $this->getLog()->Erreur('Impossible de récupérer les élément de cofiguration.');
            
            return false;
        }

        // pour chaque élément de configuration
        foreach($allConfig as $config)
        {
            // on récupére le code source correspondant à chaque option. On supprime le premier élément qui ne correspond à rien d'interessant.
            $allItemConfig = explode(ProviderOnline::PRODUCT_CONFIG_ITEM_START_TAG, $config);
            unset($allItemConfig[0]);

            // si on n'a pas récupéré le code source correspondant à chaque option
            if(count($allConfig) < 1)
            {
                $this->getLog()->Erreur('Impossible de récupérer les options.');
                return false;
            }

            // pour le code source correspondant à chaque option
            foreach($allItemConfig as $itemConfig)
            {
                // on va créé les options et option value
                $optionData = $this->_createUpdatOneOptionAndOptionsValue($itemConfig, $host, $product, $keyOption);

                // si tout c'est bien passé
                if($optionData != false)
                {
                    // on ajoute à notre tableau de retour
                    $return = array_merge($return, $optionData);
                }

                $keyOption++;
            }
        }

        // on renvoi tout ce qu'on a récupéré
        return $return;
    }

    /**
     * créé ou met à jour toutes les options et option valu
     * @param string $sourceCode code source de la page fournisseur à analyser
     * @param Hosts $host id du site en cours
     * @param TProduct $product produit en cours
     * @param int $keyOption id de l'option pour gérer l'ordre
     * @return array un tableau avec les options et les options values
     */
    private function _createUpdatOneOptionAndOptionsValue(string $sourceCode, Hosts $host, TProduct $product, int $keyOption)
    {
        $matchOptionName		 = [];
        $matchOptionValue		 = [];
        $matchOptionValuePrice	 = [];
        $keyOptionValue			 = 0;

        // si on n'arrive pas à récupérer le nom de l'option
        if(!preg_match($this->_pcreOptionTitle(), $sourceCode, $matchOptionName))
        {
            // on renvoi un log
            $this->getLog()->Erreur('Impossible de trouver le nom de l\'option.');
            $this->getLog()->Erreur(ToolsHTML::htmlentitiesEuro($sourceCode));

            // on quitte la fonction
            return false;
        }

        // si on arrive pas à récupéré les options dans le menu déroulant
        if(!preg_match_all($this->masqueOptionMenuDeroulant(), $sourceCode, $matchOptionValue, PREG_SET_ORDER) && !preg_match_all($this->masqueOptionMenuDeroulantShort(), $sourceCode, $matchOptionValue, PREG_SET_ORDER) && !preg_match_all($this->_pcreSelectOptionUnique(), $sourceCode, $matchOptionValue, PREG_SET_ORDER))
        {
            // c'est probablement pour une option de type texte
            return $this->_createUpdatOptionText($sourceCode, $host, $product, $keyOption);
        }

        // on récupére le nom de l'option (ex : Papier)
        $return['name'] = trim($matchOptionName[1]);

        // si il s'agit de l'option des quantitées
        if($return['name'] == self::OPTION_ONLINE_QUANTITE || $return['name'] == self::OPTION_ONLINE_QUANTITE2 || $return['name'] == self::OPTION_ONLINE_QUANTITE3)
        {
            // on indique qu'il s'agit de l'option des quantitées
//            $specialOption = TOption::SPECIAL_OPTION_QUANTITY;
            $specialOption = SpecialOptionEnum::SPECIAL_OPTION_QUANTITY;

            // on le met à la fin
            $order = 120;
        }
        // autre option
        else
        {
            // option standard
            $specialOption = SpecialOptionEnum::SPECIAL_OPTION_STANDARD;

            // ordre par défaut
            $order = 40 + $keyOption;
        }

        // on créé l'option et le produit option si elles n'existe pas
        $return['option'] = $this->toptionService->createIfNotExist($return['name'], Provider::class->getId(), $return['name'], $order, 0, TypeOptionEnum::TYPE_OPTION_SELECT, $specialOption);
        $this->tAProductOptionService->createIfNotExist($product, $return['option'], $host);

        // pour chaque ligne du menu déroulant
        foreach($matchOptionValue AS $OptionMenuDeroulant)
        {
            // si on est dans le cas de l'option permettant de faire des quantité personnalisé
            if($OptionMenuDeroulant[2] == 'Interpolation')
            {
                // on ne la gére pas car elle pose énormément de probléme
                continue;
            }

            // on ajoute l'id du menu déroulant (ex: input_var_PKTA644_1_1) qui correspond à la variable name du select
            $return['id'] = $OptionMenuDeroulant[1];

            // si cette option est séléctionné par défaut ou si on a une seul option (cas des input hidden)
            if(count($matchOptionValue) == 1 || preg_match($this->masqueSelected(), $OptionMenuDeroulant[0]))
            {
                // l'option séléctionné par défaut apparaitre avant les autres
                $ordre = 50;

                // on indique que cette optionValue est séléctionné par défaut
                $return['optionValue'][$keyOptionValue]['selected'] = true;

                // on ajoute au tableau des id option value fournisseur séléctionné pour les régle de produit
                $return['OptionValueFournisseurSelected'] = $OptionMenuDeroulant[3];
            }
            // option non selectionné par défaut
            else
            {
                $ordre = 100;

                // on indique que cette optionValue n'est pas séléctionné par défaut
                $return['optionValue'][$keyOptionValue]['selected'] = false;
            }

            // si cette option est l'option de quantité et qu'elle comprend un prix
            if($return['option']->isQuantity() && preg_match($this->masqueOptionMenuDeroulantPrix(), $OptionMenuDeroulant[0], $matchOptionValuePrice))
            {
                // on ajoute le prix récupéré dans notre tableau
                $return['optionValue'][$keyOptionValue]['prixOptionValue'] = $matchOptionValuePrice[1];

                // on ajoute exemplaire à la fin
//                $libelleOptionValue = $OptionMenuDeroulant[2] . ' ' . LocalisationConstant::traduire('exemplaires', DbTable::$_LANG_DEFAUT);
            }
            // option classique
            else
            {
                // on récupére le libellé de l'option
                $libelleOptionValue = $OptionMenuDeroulant[2];
            }

            // création de l'option value et produit option value si besoin
            $optionValue = $this->tOptionValueService->createIfNotExist($OptionMenuDeroulant[2], $this->getIdFour(), $return['option'], $libelleOptionValue);
            TAProductOptionValue::createIfNotExist($product->getIdProduit(), $optionValue, $idHost, $ordre);

            // on ajoute l'optionValue et l'id fournisseur dans le tableau de retour
            $return['optionValue'][$keyOptionValue]['optionValue']					 = $optionValue;
            $return['optionValue'][$keyOptionValue]['optionValueFournisseurName']	 = $OptionMenuDeroulant[2];
            $return['optionValue'][$keyOptionValue]['optionValueFournisseurId']		 = $OptionMenuDeroulant[3];

            $keyOptionValue++;
        }

        // on renvoi dans un tableau car on peux avoir plusieur option en même temps avec les options de type texte
        return array($return);
    }

    /**
     * créé ou met à jour les options de type texte
     * @param string $sourceCode code source de la page fournisseur à analyser
     * @param Hosts $host id du site en cours
     * @param TProduct $product produit en cours
     * @param int $keyOption id de l'option pour gérer l'ordre
     * @return array un tableau avec les options et les options values
     */
    private function _createUpdatOptionText(string $sourceCode, Hosts $host, TProduct $product, int $keyOption)
    {
        $matchOptionText = [];
        $return			 = [];

        // si on arrive pas à récupéré les options dans le menu déroulant
//        if(!preg_match_all($this->_pcreOptionTypeText(), $sourceCode, $matchOptionText, PREG_SET_ORDER))
//        {
            // si on n'a pas non plus trouvé d'option de type text
//            $this->getLog()->Erreur('Aucune option trouvé dans le menu déroulant.');
//            $this->getLog()->Erreur('Si il s\'agit de l\'option des "dimensions" pour les vétements, elle est a implémenter.');
//            $this->getLog()->addLogContent(ToolsHTML::htmlentitiesEuro($sourceCode));
//
//            return false;
//        }

        // pour chaque option de type texte
        foreach($matchOptionText as $dataOptionText)
        {
            // on récupére le nom de l'option (ex : Papier)  et l'id
            $option			 = ['id' => $dataOptionText[1]];
            $option['name']	 = $dataOptionText[5];

            // si on a une valeur
            if($dataOptionText[2] != '')
            {
                // on récupére la valeur envoyé
                $option['value'] = $dataOptionText[2];
            }
            // pas de valeur
            else
            {
                // on récupére la valeur min
                $option['value'] = $dataOptionText[3];
            }

            // on créé l'option de type text et le produit option si elle n'existe pas
            $option['option'] = $this->toptionService->createIfNotExist($option['id'], $this->getIdFour(), $option['name'], 40 + $keyOption, 0, TOption::TYPE_OPTION_TEXT);
            $this->tAProductOptionService->createIfNotExist($product->getIdProduit(), $option['option'], $host, $option['value']);

            // on ajoute au tableau de retour
            $return[] = $option;

            // on récupére le produit option
            $productOption = TAProduitOption::findById(array($product->getIdProduit(), $option['option']->getIdOption(), $idHost));

            // on met à jour les valeurs mini et maxi si besoin
            $productOption->updateMinMaxIfNeeded($dataOptionText[3], $dataOptionText[4]);
        }

        return $return;
    }

    /**
     * reconstruit la selection a partir des données extraites de toutes les options
     * @param array $dataOptionAndOptionValue les données des options et option value
     * @return array
     */
    private function _buildSelectionFromDataOptionAndOptionValue($dataOptionAndOptionValue)
    {
        $return = [];
        $aSelectionText = [];

        // on commence par récupéré les éléments qui proviennent des pages de type tableau ou produit
        $aSelection = $this->_getAProductSelection();

        // pour chaque menu déroulant
        foreach ($dataOptionAndOptionValue as $dataOneOptionAndOptionValue) {
            // si on a le menu déroulant des délai
            if (trim($dataOneOptionAndOptionValue['name']) == self::OPTION_ONLINE_DELAI) {
                // on passe au menu déroulant suivant
                continue;
            }

            // on récupére les donné de selection pour cette option
            $dataSelection = $this->_selectionDataFromOneOptionAndOptionValue($dataOneOptionAndOptionValue);

            // si on a récupéré l'id de quantité
            if (isset($dataSelection['idQuantitySelected'])) {
                // on sauvegarde la quantité séléctionné
                $return['idQuantitySelected'] = $dataSelection['idQuantitySelected'];
            }

            // on ajoute les élément de selection à la selection actuel
            $aSelection = array_merge($aSelection, $dataSelection['selection']);

            // pour chaque élément de type texte qu'on a récupérer
            foreach ($dataSelection['selectionText'] as $idOption => $value) {
                $aSelectionText[$idOption] = $value;
            }
        }

        // on met la selection sous forme de chaine
        $return['selection'] = implode('-', $aSelection);
        $return['selectionText'] = json_encode($aSelectionText);

        return $return;

    }

    /**
     * renvoi les information de la selection a partir des données extraites d'une seul option
     * @param array $dataOneOptionAndOptionValue les données d'une option et des éventuelles option value
     * @return array|array[]
     */
    private function _selectionDataFromOneOptionAndOptionValue(array $dataOneOptionAndOptionValue)
    {
        $return = ['selection' => [], 'selectionText' => []];

        // si on a une option de type text
        if($dataOneOptionAndOptionValue['option']->getOptTypeOption() == TOption::TYPE_OPTION_TEXT)
        {
            // on renvoi la valeur de l'option text
            $return['selectionText'][$dataOneOptionAndOptionValue['option']->getIdOption()] = $dataOneOptionAndOptionValue['value'];
            return $return;
        }

        // pour chaque option value
        foreach($dataOneOptionAndOptionValue['optionValue'] AS $optionValue)
        {
            // si cette option value n'est pas séléctionné
            if($optionValue['selected'] != true)
            {
                // on passe au suivant
                continue;
            }

            // si on est dans le cas des quantité
            if($dataOneOptionAndOptionValue['option']->isQuantity())
            {
                // on sauvegarde la quantité séléctionné
                $return['idQuantitySelected'] = $optionValue['optionValue']->getIdOptionValue();

                // on quitte la boucle tout de suite pour ne pas ajouter a la selection
                break;
            }

            // on l'ajoute dans notre tableau de selection
            $return['selection'][] = $optionValue['optionValue']->getIdOptionValue();

            // on a trouvé l option value selectionné donc inutile de continuer
            break;
        }

        return $return;
    }

    /**
     * calcul notre prix d'achat en fonction du prix d'origine du site du fournisseur
     * @param string $prixOrigine prix d'origine du site du fournisseur
     * @return float le prix d'achat pour nous
     */
    public function calculPrixAchat(string $prixOrigine)
    {
        // si on a un soucis avec un prix trop faible
        if($prixOrigine < 1)
        {
            // on ajoute un log
            $this->getLog()->Erreur('prix incorrect détécté');
            $this->getLog()->Erreur(var_export($prixOrigine, true));
        }

        // création de notre objet prix
        $prix = new Prix($prixOrigine, 2, System::getTauxTva(), Prix::PRIXHT, true);

        // on applique la remise du fournisseur
        $prix->updateMontant((100 - $this->getPourcentageRemise()) / 100);

        // on retourne le prix
        return $prix;
    }

    /**
     * verifie que l'option value fait partie des dépendance. Si ce n'est pas le cas recherche une option value correspondant à l'option dans les dépendance
     * @param TOptionValue $optionValue l'option value
     * @param array $tabDependance le tableau des id d'option value des dépendance
     * @param array $tabIdOptionValueValide le tableau des id option value valide au cas ou ne trouve rien
     * @return TOptionValue l'option value d'origine ou la bonne faisant partie des dépendance
     */
    public function checkOptionValueValide(TOptionValue $optionValue, array $tabDependance, array $tabIdOptionValueValide)
    {
        // si notre option value ne fait pas partie des dépendance et n'est donc pas dans les options disponible
        if(!in_array($optionValue->getIdOptionValue(), $tabDependance))
        {
            // initialisation de la variable qui va nous permettre de savoir si on n'a pas réussi à trouver d'option value
            $aucuneOptionValueTrouve = true;

            // on va tester chaque option value de la dépendance
            foreach($tabDependance AS $idOptionValueATester)
            {
                // on récupére l'option value
                $optionValueATester = TOptionValue::findById($idOptionValueATester);

                // si elle correspond à la même option que notre option value
                if($optionValueATester->getIdOption() == $optionValue->getIdOption())
                {
                    // on prendra celle la
                    $optionValue = $optionValueATester;

                    // on a trouvé notre option value
                    $aucuneOptionValueTrouve = false;

                    // on quitte la boucle
                    break;
                }
            }

            // si on a pas trouvé d'option value
            if($aucuneOptionValueTrouve)
            {
                $optionValue = TOptionValue::findById($tabIdOptionValueValide[0]);
            }
        }

        return $optionValue;
    }

    /**
     * Recupere sur le site online les informations de categories et les insere en base
     * @param TProduct $product				Objet produit
     * @param int $idOptionValueOnline		Id online de l'option value séléctionné. Peux également correspondre à un id de produit
     * @param Hosts $host				Id du site
     * @param array &$tabDependance			Un tableau des id option value des dépendance. Variable passé par référence
     * @param int|null $idOptionOnline			"Level" online des liens à récupéré correspondant à une option chez nous. non utilisé dans le cas des tableaux
     * @return array un tableau des id option value de cette catégorie
     */
    private function recuperationCategorie(TProduct $product, int $idOptionValueOnline, Hosts $host, array &$tabDependance, int $idOptionOnline = null)
    {
        // initialisation d'un tableau qui va contenur toutes les id option value de cette catégorie
        $tabIdOptionValue	 = [];
        $resultat			 = [];
        $matchTitle			 = [];

        // on modifie l'url pour la requête curl pour récupéré les catégories
        $url = 'https://www.onlineprinters.fr/' . $idOptionValueOnline;
        $this->getCurl()->setOptUrl($url);

        // pas de post
        $this->getCurl()->setOptPost(false);

        // récupération de la page
        $page = $this->getCurl()->exec();

        // si on a une page de format avec plusieur sous catégorie
        if(preg_match_all($this->_masqueMultiTableau(), $page, $resultat, PREG_SET_ORDER) > 0)
        {
            // pour chaque tableau
            foreach($resultat AS $resultatTableau)
            {
                // récupération des dépendance à partir de l'analyse du tableau online
                $this->analyseTableauOnline($resultatTableau[2], $resultatTableau[1], $product, $host, $tabDependance, $tabIdOptionValue);
            }
        }
        // si on a une page de format sans sous catégorie
        elseif(preg_match_all($this->_masqueLigneTableau(true), $page, $resultat) > 0 && preg_match($this->_pcreH1(), $page, $matchTitle))
        {
            // on recréé les codes de tous les tableaux ensemble
            $htmlTableOnline = implode("\n", $resultat[0]);

            // récupération des dépendance à partir de l'analyse du tableau online
            $this->analyseTableauOnline($htmlTableOnline, $matchTitle[1], $product, $host, $tabDependance, $tabIdOptionValue);
        }
        // page des différents sous produit
        elseif(preg_match_all($this->_pcreSubProduct(), $page, $resultat, PREG_SET_ORDER) > 0)
        {
            // on créé l'option et le produit option si elles n'existe pas
            $option = TOption::createIfNotExist($idOptionOnline, $this->getIdFour(), $idOptionOnline, $idOptionOnline);
            TAProduitOption::createIfNotExist($product->getIdProduit(), $option, $host);

            // pour chaque catégorie
            foreach($resultat as $cat)
            {
                // création de l'option value et produit option value si besoin
                $optionValue = TOptionValue::createIfNotExist($cat[1], $this->getIdFour(), $option, $cat[2]);
                TAProduitOptionValue::createIfNotExist($product->getIdProduit(), $optionValue, $host);

                // on ajoute l'id option value au tableau des dépendance et des id option value de la catégorie
                $tabDependance[]	 = $optionValue->getIdOptionValue();
                $tabIdOptionValue[]	 = $optionValue->getIdOptionValue();
            }
        }
        // il s'agit de la page des produits
        elseif(preg_match($this->masqueOptionMenuDeroulant(), $page) || preg_match($this->masqueOptionMenuDeroulantShort(), $page))
        {
            return array();
        }
        // le produit n'est plus disponible
        elseif(preg_match($this->masqueProduitIndisponible(), $page))
        {
            $this->getLog()->Erreur('le produit n\'est plus disponible');
            $this->getLog()->Erreur('Il faut probablement mettre à jour le pro_fou_id_source de la table ta_produit_fournisseur pour le produit ' . $product->getIdProduit());
        }
        // page inconnu
        else
        {
            // on envoi un log
            $this->getLog()->Erreur('aucun masque ne fonctionne, le site a probablement changé');
            $this->getLog()->Erreur('url : ' . $url);
        }

        return $tabIdOptionValue;
    }

    /**
     * Recupere sur le site online les informations de produit et les insere en base
     * @param TProduct $product					Objet produit
     * @param int $idOptionValueOnline			Id online pour accéder à la page du produit détaillé
     * @param Hosts $host					Id du site
     * @param array &$tabDependance				Un tableau des id option value des dépendance. Variable passé par référence
     * @param array $tabSelectionUtilisateur	Un tableau des id option value de la selection faite par l'utilisateur
     * @return array un tableau avec toutes les données des produits
     */
    public function recuperationProduit(TProduct $product, int $idOptionValueOnline, Hosts $host, array &$tabDependance, array $tabSelectionUtilisateur)
    {
        $matchProdIndex		 = [];
        $matchForm			 = [];
        $matchInputHidden	 = [];
        $matchDelay			 = [];

        // on modifie l'url pour la requête curl pour récupéré les catégories
        $this->getCurl()->setOptUrl('https://www.onlineprinters.fr/' . $idOptionValueOnline);

        // on reset le post si on s'est connécté avant
        $this->getCurl()->setOptPost(false);

        // récupération de la page
        $page = $this->getCurl()->exec();

        // si on a réussi récupérer le prod index
        if(preg_match($this->_pcreProdIndex(), $page, $matchProdIndex))
        {
            // on met à jour le prod index
            $this->setProdIndex($matchProdIndex[1]);
        }
        // impossible de trouver le prod index
        else
        {
            $this->getLog()->Erreur('ProdIndex introuvable. Les régles de produit ne seront pas appliqués.');
        }

        // si on a pas récupéré de délai c'est qu'on doit avoir un produit sans menu déroulant de délai
        if(preg_match($this->_masqueDelaiHorsMenuDeroulant(), $page, $matchDelay))
        {
            $delaiHorsMenuDeroulant = $matchDelay[1];
        }
        // pas de délai
        else
        {
            $delaiHorsMenuDeroulant = null;
        }

        // récupération de tous les menus déroulant
        $dataMenuDeroulant = $this->_createUpdateAllOptionAndOptionsValue($page, $host, $product);

        // si un problème à eu lieu pendant la récupération des donnée du menu déroulant
        if($dataMenuDeroulant === false)
        {
            return false;
        }

        // pour chaque élément de la selection Utilisateur
        foreach($tabSelectionUtilisateur AS $idOption => $idOptionValueUtilisateur)
        {
            // si on est sur la l'option des délai ou des quantité
            if($idOption == 'idQuantity' || $idOption == 'idDelay')
            {
                // on recupere l'option value
                $optionValueUtilisateur = TOptionValue::findById($idOptionValueUtilisateur);

                // on le met dans la table des option value
                $tabOptionValueUtilisateur[$optionValueUtilisateur->getIdOption()] = $optionValueUtilisateur;

                // on passe à la suivante
                continue;
            }

            // on récupére l'option
            $option = TOption::findById($idOption);

            // si on n'a pas d'option (cas de idQuantity et idDelay)
            if($option->getIdOption() == null)
            {
                // on passe à la suivante
                continue;
            }

            // si on a une option de type texte
            if($option->getOptTypeOption() == TOption::TYPE_OPTION_TEXT)
            {
                // on met directement la valeur de l'option
                $tabOptionValueUtilisateur[$idOption] = $idOptionValueUtilisateur;

                // on passe à l'option suivante
                continue;
            }

            // on recupere l'option value
            $optionValueUtilisateur = TOptionValue::findById($idOptionValueUtilisateur);

            // on le met dans la table des option value
            $tabOptionValueUtilisateur[$idOption] = $optionValueUtilisateur;
        }

        // pour chaque menu déroulant
        foreach($dataMenuDeroulant AS $ligneMenuDeroulant)
        {
            // on traite les menu déroulant pour mettre à jour la selection online
            $this->traitementMenuDeroulant($ligneMenuDeroulant, $tabOptionValueUtilisateur);
        }

        // une erreur à eu lieu dans la récupération du formulaire
        if(!preg_match($this->_masqueFormProduct(), $page, $matchForm))
        {
            $log = TLog::initLog('Erreur récupération online');
            $log->Erreur('problème dans le formulaire des produits');

            return false;
        }

        // une erreur à eu lieu dans la récupération des input hidden
        if(!preg_match_all($this->_masqueInputHidden(), $matchForm[0], $matchInputHidden, PREG_SET_ORDER))
        {
            $log = TLog::initLog('Erreur récupération online');
            $log->Erreur('Input hidden introuvable');

            return false;
        }

        // on ajoute les prix et quantités dans la variable de retour
        return $this->_recuperationProduitToutesQuantites($matchInputHidden, $product, $host, $tabDependance, $tabOptionValueUtilisateur, $delaiHorsMenuDeroulant);
    }

    /**
     * traite un menu déroulant online
     * @param array $dataOptionAndOptionValue donnée des options et option value online une fois extraite
     * @param TOptionValue[] $tabOptionValueUtilisateur tableau des options value de la selection utilisateur avec l'idOption en clef
     */
    public function traitementMenuDeroulant(array $dataOptionAndOptionValue, array $tabOptionValueUtilisateur)
    {
        // si il s'agit de l'option des délais
        if(trim($dataOptionAndOptionValue['name']) == self::OPTION_ONLINE_DELAI)
        {
            // on reinitialise le tableau des délai
            $this->_initAIdOptionValueDelay();

            // compteur pour les délai
            $iDelai = 1;

            // pour chaque optionValue du menu déroulant
            foreach($dataOptionAndOptionValue['optionValue'] AS $OptionValueMenuDeroulant)
            {
                // on ajoute la quantités dans le tableau
                $this->_addAIdOptionValueDelay($OptionValueMenuDeroulant['optionValue']->getIdOptionValue(), $OptionValueMenuDeroulant['optionValueFournisseurName']);

                // si On a le premier délai ou si notre option est séléctionné
                if($iDelai == 1 || $OptionValueMenuDeroulant['selected'] == true)
                {
                    // on définit l'id du délai séléctionné
                    $this->_setIdOptionValueDelaySelected($OptionValueMenuDeroulant['optionValue']->getIdOptionValue());
                }

                $iDelai++;
            }
        }

        // si il s'agit d'un élément de type text
        if($dataOptionAndOptionValue['option']->getOptTypeOption() == TOption::TYPE_OPTION_TEXT)
        {
            // si cette option est dans la séléction fournisseur
            if(isset($tabOptionValueUtilisateur[$dataOptionAndOptionValue['option']->getIdOption()]))
            {
                // on met à jour notre séléction online
                $this->tabSelectionOnline[$dataOptionAndOptionValue['id']] = $tabOptionValueUtilisateur[$dataOptionAndOptionValue['option']->getIdOption()];

                // on met à jour notre séléction
                $this->tabSelectionTxt[$dataOptionAndOptionValue['option']->getIdOption()] = $tabOptionValueUtilisateur[$dataOptionAndOptionValue['option']->getIdOption()];

                // on return pour sortir de la fonction
                return true;
            }

            // on met à jour notre séléction online avec la valeur online par défaut
            $this->tabSelectionOnline[$dataOptionAndOptionValue['id']] = $dataOptionAndOptionValue['value'];

            // on met à jour notre séléction
            $this->tabSelectionTxt[$dataOptionAndOptionValue['option']->getIdOption()] = $dataOptionAndOptionValue['value'];

            // on return pour sortir de la fonction
            return true;
        }

        // si cet option correspond à un élément de la séléction de l'utilisateur
        if(isset($tabOptionValueUtilisateur[$dataOptionAndOptionValue['option']->getIdOption()]))
        {
            // on met à jour notre séléction online
            $this->tabSelectionOnline[$dataOptionAndOptionValue['id']] = $tabOptionValueUtilisateur[$dataOptionAndOptionValue['option']->getIdOption()]->getIdOptionValueSrc($this->getIdFour());

            // si il ne s'agit pas du menu déroulant des quantités
            if(!$dataOptionAndOptionValue['option']->isQuantity())
            {
                // on met à jour notre séléction
                $this->tabSelection[] = $tabOptionValueUtilisateur[$dataOptionAndOptionValue['option']->getIdOption()]->getIdOptionValue();
            }

            // on return pour sortir de la fonction
            return true;
        }

        // ce menu déroulant n'était pas dans la selection de l'utilisateur
        // pour chaque ligne du menu déroulant
        foreach($dataOptionAndOptionValue['optionValue'] AS $key => $OptionValueMenuDeroulant)
        {
            // si cette option n'est pas séléctionné par défaut et qu'il ne s'agit pas de la premiere de la liste
            if($key != 0 && $OptionValueMenuDeroulant['selected'] != true)
            {
                // on passe à la suivante
                continue;
            }

            // on met à jour notre séléction online
            $this->tabSelectionOnline[$dataOptionAndOptionValue['id']] = $OptionValueMenuDeroulant['optionValueFournisseurName'];

            // si il ne s'agit pas du menu déroulant des quantités
            if(!$dataOptionAndOptionValue['option']->isQuantity())
            {
                // on met à jour notre séléction
                $this->tabSelection[] = $OptionValueMenuDeroulant['optionValue']->getIdOptionValue();
            }

            if($OptionValueMenuDeroulant['selected'] == true)
            {
                // on sort de la boucle
                break;
            }
        }

        return true;
    }

    /**
     * récupére toutes les quantités d'un produit pour extraire les données de prix, ...
     * @param array $dataInputHidden tableau des données input hidden provenant de masqueInputHidden
     * @param TProduct $product le produit
     * @param Hosts $host id du site
     * @param array $tabDependance un tableau des id option value des dépendance. utilisé uniquement pour les quantités
     * @param int|null $delaiHorsMenuDeroulant nombre de jour de fabrication quand on a un nombre de jour en dehors du menu déroulant
     * @return string les données du post pour online
     */
    private function _recuperationProduitToutesQuantites(array $dataInputHidden, TProduct $product, Hosts $host, array $tabDependance, $tabOptionValueUtilisateur, ?int $delaiHorsMenuDeroulant)
    {
        $resultatPrixHorsTaxe	 = array();
        $matchDelay				 = array();

        // récupération de la page chez online
        $pageDetailProduct = $this->curlAjaxProductDetail($dataInputHidden);

        // si il y a eu une erreur dans la récupération de la page online
        if($pageDetailProduct == false)
        {
            // on renvoi un array vide comme ca on affichera une erreur
            return array();
        }

        // récupération de toutes les options et option value
        $dataOptionAndOptionValueWithoutSpecialFormat = $this->_createUpdateAllOptionAndOptionsValue($pageDetailProduct['productConfig'], $host, $product);

        // si un problème à eu lieu pendant la récupération des donnée du menu déroulant
        if($dataOptionAndOptionValueWithoutSpecialFormat === false)
        {
            return array();
        }

        // gestion des formats personnalisé  qui sont spécifique dans le cas du retour ajax
        $dataOptionAndOptionValue = $this->_manageSpecialFormat($dataOptionAndOptionValueWithoutSpecialFormat, $pageDetailProduct['specialFormat'], $host, $product);

        // pour chaque menu déroulant
        foreach($dataOptionAndOptionValue AS $ligneMenuDeroulant)
        {
            // on traite les menu déroulant pour mettre à jour la selection online
            $this->traitementMenuDeroulant($ligneMenuDeroulant, $tabOptionValueUtilisateur);
        }

        // si on a un probléme avec la selection Online
        if(!$this->_verificationMenuDeroulantOnline($dataOptionAndOptionValue))
        {
            /**
             * On fait un 2e tour !
             */
            // récupération de la page chez online
            $pageDetailProduct = $this->curlAjaxProductDetail($dataInputHidden);

            // si il y a eu une erreur dans la récupération de la page online
            if($pageDetailProduct == false)
            {
                // on renvoi un array vide comme ca on affichera une erreur
                return array();
            }

            // récupération de tous les menus déroulant
            $dataOptionAndOptionValueWithoutSpecialFormat = $this->_createUpdateAllOptionAndOptionsValue($pageDetailProduct['productConfig'], $host, $product);

            // si un problème à eu lieu pendant la récupération des donnée du menu déroulant
            if($dataOptionAndOptionValueWithoutSpecialFormat === false)
            {
                return array();
            }

            // gestion des formats personnalisé  qui sont spécifique dans le cas du retour ajax
            $dataOptionAndOptionValue = $this->_manageSpecialFormat($dataOptionAndOptionValueWithoutSpecialFormat, $pageDetailProduct['specialFormat'], $host, $product);

            // pour chaque menu déroulant
            foreach($dataOptionAndOptionValue AS $ligneMenuDeroulant)
            {
                // on traite les menu déroulant pour mettre à jour la selection online
                $this->traitementMenuDeroulant($ligneMenuDeroulant, $tabOptionValueUtilisateur);
            }

            // si on a toujours pas une selection correct
            if(!$this->_verificationMenuDeroulantOnline($dataOptionAndOptionValue, true))
            {
                // on envoi un log
                $log = TLog::initLog('Erreur récupération online');
                $log->Erreur('Séléction incorrect 2 fois de suite.');
                $log->Erreur('Vérifier que la configuration par défaut du produit est correct.');

                return array();
            }
        }

        // on récupére la selection chez online (pour le cas ou on ai des options qui ne sont pas celle qu'on a demandé) ainsi que la quantité séléctionné
        $return = $this->_buildSelectionFromDataOptionAndOptionValue($dataOptionAndOptionValue);

        // récupération des product rules applicable à nos options
        $aProductRules = $this->_productRuleApplicableByOption($dataOptionAndOptionValue);

        // pour chaque menu déroulant
        foreach($dataOptionAndOptionValue AS $dataOneOptionAndOptionValue)
        {
            // on gére les dépendnce et les quantité
            $this->_manageDependencyAndPrice($dataOneOptionAndOptionValue, $tabDependance, $return, $aProductRules);
        }

        // on récupére le prix hors taxe sur la page
        if(!preg_match($this->masquePrix(), $pageDetailProduct['price'], $resultatPrixHorsTaxe))
        {
            $this->getLog()->Erreur('Impossible de trouver le prix pour vérifier avec celui du menu déroulant');
            $this->getLog()->addLogContent(ToolsHTML::htmlentitiesEuro($pageDetailProduct['price']));

            // on renvoi un array vide comme ca on affichera une erreur
            return array();
        }

        /**
         * Dans le cas ou on a bien récupéré un prix (ce n'est pas le cas pour les formats personnalisé invalide)
         * on va vérifier si le prix récupéré dans le menu déroulant différent du prix hors taxe, ca sera le cas si il y a des option complémentaire payante
         */
        if(isset($return['tabPrice']) && isset($return['priceToCheck']) && $resultatPrixHorsTaxe[1] <> $return['priceToCheck'])
        {
            // pour chaque prix présent dans le tableau des prix
            foreach($return['tabPrice'] AS $key => $price)
            {
                // si le prix correspond à la page en cours
                if($return['priceToCheckQuantity'] == $price['quantite'])
                {
                    // on met à jour le prix
                    $return['tabPrice'][$key]['prix'] = $this->calculPrixAchat($resultatPrixHorsTaxe[1]);
                }
                // autre quantité
                else
                {
                    // on supprime le prix
                    $return['tabPrice'][$key]['prix'] = null;
                }
            }
        }

        // ajout des dépendance dans la variable de retour
        $return['dependance'] = implode('-', $tabDependance);

        // on ordonne la séléction
        TCombinaison::ordonneSelection($return['selection']);

        // si on a pas récupéré de délai et qu'on a un délai hors menu déroulant
        if(empty($this->_getAIdOptionValueDelay()) && $delaiHorsMenuDeroulant != null)
        {
            // on va utilisé ce délai
            $this->_addAIdOptionValueDelay(0, $delaiHorsMenuDeroulant);
            $this->_setIdOptionValueDelaySelected(0);
        }

        // par défaut délai stanndard pour les produits
        $idDelayProduct = Products::ID_DELAI_STANDARD;

        // ==================================================
        // on ajoute le délai au tableau des délai
        foreach($this->_getAIdOptionValueDelay() as $idOptionValue => $delaiTxt)
        {
            // on recherche le nombre de jour maximum
            if(!preg_match($this->_masqueDelaiMax(), $delaiTxt, $matchDelay))
            {
                $log = TLog::initLog('Erreur récupération online');
                $log->Erreur('Délai max non trouvé');
                $log->Erreur($delaiTxt);

                // on passe à l'option suivante
                continue;
            }

            /// le délai de fabrication online est le délai max + jour supplémentaite dû aux options + 3 jours ouvrés
            $return['tabDelay'][$idOptionValue]['fabrication'] = $matchDelay[1] + $this->_supplementJourOuvre($pageDetailProduct['productConfig']) + 3;

            // on ajoute l'id de délai du produit
            $return['tabDelay'][$idOptionValue]['idDelayProduct'] = $idDelayProduct;

            // pas de gestion des prix dans les délai pour ce fournisseur
            $return['tabDelay'][$idOptionValue]['price'] = null;

            // on passe au délai suivant pour le produit
            $idDelayProduct++;
        }

        // ==================================================
        // on ajoute le délai au tableau des délai
        $return['idDelaySelected'] = $this->_getIdOptionValueDelaySelected();

        return $return;
    }

    /**
     * vérifie les menu déroulant Online pour être sur que tous ce qu'on avait séléctionné chez eux est bien séléctionné.
     * Ce n'est pas le cas pour certaines options type pelliculage qui change d'id option quand on change le papier par exemple
     * @param array $dataMenuDeroulant les donées des menus déroulant
     * @param bool $secondCheck = false mettre true dans le cas de la 2e verification pour ne pas vérifié que la selection soit exact pour les selection non realisable
     * @return boolean true si tout va bien et false en cas de probléme
     */
    private function _verificationMenuDeroulantOnline(array $dataMenuDeroulant, bool $secondCheck = false)
    {
        // pour chaque menu déroulant
        foreach($dataMenuDeroulant AS $ligneMenuDeroulant)
        {
            // si on a pas cette id d'option online dans la selection online
            if(!isset($this->tabSelectionOnline[$ligneMenuDeroulant['id']]))
            {
                // c'est qu'on a un probléme de selection
                return false;
            }

            // si on est sur la 2e verification
            if($secondCheck)
            {
                // on passe à l'option suivante
                continue;
            }

            // si on a une option de type text
            if($ligneMenuDeroulant['option']->getOptTypeOption() == TOption::TYPE_OPTION_TEXT)
            {
                // on passe à l'option suivante
                continue;
            }

            // pour chaque option value
            foreach($ligneMenuDeroulant['optionValue'] AS $optionValueData)
            {
                // si l'option value séléctionné ne correspond pas a celle qui devrait être séléctionné
                if($optionValueData['selected'] && $optionValueData['optionValueFournisseurName'] != $this->tabSelectionOnline[$ligneMenuDeroulant['id']])
                {
                    // c'est qu'on a un probléme de selection
                    return false;
                }
            }
        }

        // aucun probléme
        return true;

    }

    /**
     * gére les option value par rapport à la récupération des quantités et des dépendance
     * @param array $aDataOptionValue tableaux contenant les donnés des options value récupéré du fournisseur
     * @param array $tabDependance PASSE PAR REFERENCE un tableau des id option value des dépendance. utilisé uniquement pour les quantités
     * @param array $return PASSE PAR REFERENCE un tableau qui va contenir toutes les données dont on aura besoin
     * @param TOnlineProductRule[] $aProductRules [=array()] tableua des product rule à appliqué
     * @return boolean retourne false en cas d'erreur
     */
    private function _manageDependencyAndPrice(array $aDataOptionValue, array &$tabDependance, array &$return, array $aProductRules = array())
    {
        // si on a une option de type text
        if($aDataOptionValue['option']->getOptTypeOption() == TOption::TYPE_OPTION_TEXT)
        {
            // on ne gére pas les dépendance pour les options de type texte
            return true;
        }

        // pour chaque ligne du menu déroulant
        foreach($aDataOptionValue['optionValue'] AS $dataOptionValue)
        {
            // si cette option comprend un prix (cas des quantité)
            if(isset($dataOptionValue['prixOptionValue']))
            {
                // on ajoute l'id d'online des quantité qui servira pour le cas des suppléments payant
                $return['idOptionQtyOnline'] = $aDataOptionValue['id'];

                // on gére les prix
                $this->gestionPrixMenuDeroulant($dataOptionValue, $return);

                // on passe à l'option suivante
                continue;
            }

            // si il agit du menu déroulant des délais
            if(trim($aDataOptionValue['name']) == self::OPTION_ONLINE_DELAI)
            {
                // on passe àl'option suivante
                continue;
            }

            // pour chaque régle de produit
            foreach($aProductRules as $productRule)
            {
                // si on doit cacher cette option
                if($productRule->isHiddedRow($dataOptionValue['optionValueFournisseurId']))
                {
                    // on passe à l'option value suivante sans rien faire
                    continue 2;
                }
            }

            // ajout de l'id de l'option value dans les dépendance
            $tabDependance[$dataOptionValue['optionValue']->getIdOptionValue()] = $dataOptionValue['optionValue']->getIdOptionValue();
        }

        return true;
    }

    /**
     * Gére les formats personnalisé dans le retour ajax
     * @param array $dataOptionAndOptionValueWithoutSpecialFormat toutes les options et option value déjà récupéré
     * @param string $sourceCode code source de la page fournisseur à analyser
     * @param Hosts $host id du site en cours
     * @param TProduct $product produit en cours
     * @return array les options et option value avec celle des format personnalisé si il y en a
     */
    private function _manageSpecialFormat(array $dataOptionAndOptionValueWithoutSpecialFormat, string $sourceCode, Hosts $host, TProduct $product)
    {
        // si on n'a pas de format personnalisé
        if($sourceCode == '')
        {
            // rien à ajouter
            return $dataOptionAndOptionValueWithoutSpecialFormat;
        }

        // récupération des option de format personnalisé
        $specialFormatOption = $this->_createUpdatOptionText($sourceCode, $host, $product, -1);

        // si on a un soucis
        if($specialFormatOption == false)
        {
            // rien à ajouter
            return $dataOptionAndOptionValueWithoutSpecialFormat;
        }

        // on ajoute nos nouvelles options aux anciennes
        return array_merge($dataOptionAndOptionValueWithoutSpecialFormat, $specialFormatOption);
    }

    /**
     * ajoute les prix et autre donnée de la ligne du menu déroulant dans le tableau de retour
     * @param array $OptionMenuDeroulant tableau contenant toutes les donnée de la ligne d'option du menu déroulant
     * @param array $return PASSE PAR REFERENCE un tableau qui va contenir toutes les données dont on aura besoin
     * @return bool false dans le cas d'un prix à 0 et true si tout va bien
     */
    private function gestionPrixMenuDeroulant(array $OptionMenuDeroulant, array &$return)
    {
        // si on a un prix à 0, ce qui arrive avec les formats personnalisé non réalisable
        if($OptionMenuDeroulant['prixOptionValue'] == 0)
        {
            // on quitte directement la fonction
            return false;
        }

        // création du prix
        $prix = $this->calculPrixAchat($OptionMenuDeroulant['prixOptionValue']);

        // on ajotue une ligne dans tabPrice
        $return['tabPrice'][$OptionMenuDeroulant['optionValue']->getIdOptionValue()]['prix']	 = $prix;
        $return['tabPrice'][$OptionMenuDeroulant['optionValue']->getIdOptionValue()]['quantite'] = $OptionMenuDeroulant['optionValueFournisseurName'];

        // on ajoute une ligne dans le tableau qui va contenir toutes les quantité, ce tableau sert dans le cas des suppléments payant
        $return['tabQuantity'][$OptionMenuDeroulant['optionValue']->getIdOptionValue()] = $OptionMenuDeroulant['optionValueFournisseurName'];

        // si cette option est séléctionné par défaut
        if($OptionMenuDeroulant['selected'])
        {
            // on ajoute le prix et la quantité correspondante dans return pour vérifier qu'il est correct, pas d'option complémentaire
            $return['priceToCheck']			 = $OptionMenuDeroulant['prixOptionValue'];
            $return['priceToCheckQuantity']	 = $OptionMenuDeroulant['optionValueFournisseurName'];
        }

        // tout est bon
        return true;
    }

    /**
     * renvoi le nombre de jour ouvre supplémentaire dû au options choisi
     * @param string $pageProduitDetail le code source de la page online
     * @return int le nombre de jour
     */
//    private function _supplementJourOuvre(string $pageProduitDetail)
//    {
//        $resultat = [];

        // nombre de jour supplementaire
//        $jourSupplementaire = 0;

        // on recherche tous les suppléments de jour ouvré
//        preg_match_all($this->masqueSupplementJourOuvre(), $pageProduitDetail, $resultat);

        // pour chaque supplément de jour ouvré
//        foreach($resultat[1] AS $nbJourOuvre)
//        {
            // on ajoute ce nombre de jour ouvre
//            $jourSupplementaire += $nbJourOuvre;
//        }
//
//        return $jourSupplementaire;
//    }

    /**
     * récupére le code source de la page du fournisseur ou apparaisse les quantité et les prix
     * @param array $dataInputHidden tableau des données input hidden provenant de masqueInputHidden
     * @param TOption|null $option id de l'option online qui correspond au tableau à récupéré
     * @param array|null $idOptionValueOnline
     * @return string les données du post pour online
     */
//    private function curlAjaxProductDetail(array $dataInputHidden, TOption $option  , array $idOptionValueOnline )
//    {
//        $return = array();

        // paramétrage du post de la requête
//        $this->getCurl()->setOptPost(true, $this->generatePostDataOnline($dataInputHidden, $option, $idOptionValueOnline));

        // modification des headers trés imprtante pour que cela fonctionne ! Récupéré dans Firefox
//        $this->getCurl()->setOptHttpHeader(array('Accept: application/json, text/javascript, */*; q=0.01', 'Accept-Language: fr,fr-FR;q=0.8,en-US;q=0.5,en;q=0.3', 'Accept-Encoding: gzip, deflate, br', 'Content-Type: application/WS-targetISO-8859-1xWS-target'));

        // execution de la requête
//        $page = $this->getCurl()->exec();

        // on decode la page qui était encoder en json
//        $pageDecode = json_decode($page, true);

        // si il y a eu une erreur dans la récupération de la page online
//        if($pageDecode == false)
//        {
//            $this->getLog()->Erreur('Impossible de décoder le json du retour Ajax.');
//            $this->getLog()->addLogContent($page);

            // on renvoi une chaine vide
//            return false;
//        }

        // si il manque un élément
//        if(!isset($pageDecode['WS-Ajax-productConfigHeadAjax1']) || !isset($pageDecode['WS-Ajax-productConfigContentAjax1']) || !isset($pageDecode['WS-Ajax-productConfigHeadAjax2']) || !isset($pageDecode['WS-Ajax-productConfigContentAjax2']) || !isset($pageDecode['WS-Ajax-additionalOptionAjax']) || !isset($pageDecode['WS-Ajax-productConfigTotalPriceAjax']) || !isset($pageDecode['WS-Ajax-startProductFormAjax']))
//        {
//            $this->getLog()->Erreur('Elements manquant dans le retour Ajax.');
//            $this->getLog()->addLogContent(var_export(array_keys($pageDecode), true));

            // on renvoi une chaine vide
//            return false;
//        }

        // on renvoi toute la partie sur la config des produit. On ajoute un truc au début qui manque dans le retour ajax.
//        $return['productConfig'] = FournisseurOnline::PRODUCT_CONFIG_START_TAG . utf8_decode($pageDecode['WS-Ajax-productConfigHeadAjax1'] . "\n" . $pageDecode['WS-Ajax-productConfigContentAjax1'] . "\n" . $pageDecode['WS-Ajax-productConfigHeadAjax2'] . "\n" . $pageDecode['WS-Ajax-productConfigContentAjax2'] . "\n");

        // si on a une partie 3
//        if(isset($pageDecode['WS-Ajax-productConfigHeadAjax3']) && isset($pageDecode['WS-Ajax-productConfigContentAjax3']))
//        {
            // ajout de la partie 3
//            $return['productConfig'] .= utf8_decode($pageDecode['WS-Ajax-productConfigHeadAjax3'] . "\n" . $pageDecode['WS-Ajax-productConfigContentAjax3'] . "\n");
//        }

        // ajout des options additionnelles
//        $return['productConfig'] .= utf8_decode($pageDecode['WS-Ajax-additionalOptionAjax'] . "\n" . '</html>');

        // on renvoi la partie des input hidden
//        $return['inputHidden'] = utf8_decode($pageDecode['WS-Ajax-startProductFormAjax']);

        // on renvoi la partie du prix
//        $return['price'] = utf8_decode($pageDecode['WS-Ajax-productConfigTotalPriceAjax']);

        // Dans le cas des format perso
//        if(isset($pageDecode['WS-Ajax-productConfigContentAreaProductAjax']))
//        {
            //  on a besoin de récupérer un élément supplémentaire
//            $return['specialFormat'] = $pageDecode['WS-Ajax-productConfigContentAreaProductAjax'];
//        }
        // format standard
//        else
//        {
            //  on a besoin de rien
//            $return['specialFormat'] = '';
//        }
//
//        return $return;
//    }

    /**
     * Analyse un tableau online et cree les option/optionValue si necessaire
     * @param string $codeSourceTableau		Code html du tableau
     * @param string $nomOption				Nom de l'option chez online
     * @param string $idOptionOnline		Id de l'option chez online
     * @param TProduct $product				Objet produit
     * @param Hosts $host				Id du site
     * @param array &$tabDependance			Un tableau des id option value des dépendance. Variable passé par référence
     * @param array &$tabIdOptionValue		Un tableau des id option value de ce tableau. Variable passé par référence
     * @param string $prefixOptionValue		Prefixe de l'option value (titre du tableau dans les pages multitableaux)
     * @return array un tableau des id option value des dépendance
     */
//    private function analyseTableauOnline(string $codeSourceTableau, string $nomOption, TProduct $product, Hosts $host, array &$tabDependance, array &$tabIdOptionValue)
//    {
//        $resultatLigneTableau = array();

        // on créé l'option et le produit option si elles n'existe pas
//        $option = TOption::createIfNotExist('tableau', $this->getIdFour(), 'Format', 100);
//        TAProduitOption::createIfNotExist($product->getIdProduit(), $option, $host);

        // on commence par récupéré chaque ligne
//        preg_match_all($this->_masqueLigneTableau(), $codeSourceTableau, $resultatLigneTableau, PREG_SET_ORDER);

        // pour chaque ligne de tableau
//        foreach($resultatLigneTableau AS $ligneTableau)
//        {
            // si on a le titre du tableau identique à la ligne du tableau
//            if(trim(strtolower($ligneTableau[2])) == trim(strtolower($nomOption)))
//            {
                // on prend uniquement le nom de l'option avec le format
//                $nomOptionValue = trim(ucfirst(strtolower($ligneTableau[2])));
//            }
            // le titre du tableau est différent de l'option
//            else
//            {
                // on ajoute tous dans le nom de l'option
//                $nomOptionValue = trim(ucfirst(strtolower($nomOption))) . ' ' . trim(ucfirst(strtolower($ligneTableau[2])));
//            }

            // si on a un détail de l'option (exemple la taille) et qu'il est différent des autres éléments
//            if(trim($ligneTableau[3]) != '' && trim(strtolower($ligneTableau[3])) != trim(strtolower($nomOption)) && trim(strtolower($ligneTableau[3])) != trim($ligneTableau[2]))
//            {
                // on le rajoute au nom de l'option
//                $nomOptionValue .= ' ' . trim(ucfirst(strtolower($ligneTableau[3])));
//            }

            // création de l'option value et produit option value si besoin
//            $optionValue = TOptionValue::createIfNotExist($ligneTableau[1], $this->getIdFour(), $option, $nomOptionValue);
//            TAProduitOptionValue::createIfNotExist($product->getIdProduit(), $optionValue, $host);

            // on ajoute l'id option value au tableau des dépendance
//            $tabDependance[$optionValue->getIdOptionValue()] = $optionValue->getIdOptionValue();
//            $tabIdOptionValue[]								 = $optionValue->getIdOptionValue();
//        }
//    }

    /**
     * génére les donnée du post à partir de la séléction et des donnée input hidden d'online
     * @param array $dataInputHidden tableau des données input hidden provenant de masqueInputHidden
     * @param string|null $idOptionExemplaires id de l'option online des quantités si on doit changer les exemplaires ou null pour garder ce qu'il y a dans data
     * @param string|null $idOptionValueExemplaires id de l'option value online des quantités si on doit changer les exemplaires ou null pour garder ce qu'il y a dans data
     * @return string les données du post pour online
     */
//    private function generatePostDataOnline(array $dataInputHidden, string $idOptionExemplaires = null, string $idOptionValueExemplaires = null)
//    {
        // pour chaque élément du input hidden
//        foreach($dataInputHidden AS $inputHidden)
//        {
            // on l'ajoute dans le tableau de selection
//            $this->tabSelectionOnline[$inputHidden[1]] = $inputHidden[2];
//        }

        // si on doit changer l'option du nombre d'exemplaire
//        if($idOptionExemplaires <> null)
//        {
            // on la change
//            $this->tabSelectionOnline[$idOptionExemplaires] = $idOptionValueExemplaires;
//        }

        // modification d'un paramétre
//        $this->tabSelectionOnline['js_dep_var'] = 'ajax';

        // encodage des caractére et fabrication de la chaine à envoyer en post
//        $return = http_build_query($this->tabSelectionOnline, '', '&', PHP_QUERY_RFC3986);

        // on renvoi la selection
//        return $return;
//    }

    /**
     * Renvoi la liste des product rules applicable en fonction des option et option value séléctionné
     * @param array $dataOptionAndOptionValue
     * @return TOnlineProductRule[]
     */
//    private function _productRuleApplicableByOption(array $dataOptionAndOptionValue)
//    {
//        $return				 = [];
//        $aOptionValueOnline	 = [];

        // pour chaque option
//        foreach($dataOptionAndOptionValue as $dataOneOptionAndOptionValue)
//        {
            // si on a une option de type text
//            if($dataOneOptionAndOptionValue['option']->getOptTypeOption() == TOption::TYPE_OPTION_TEXT)
//            {
                // on ne gére pas les régle de produit pour les options de type texte
//                continue;
//            }

            // on ajoute la valeur d'option value chez le fournisseur dans notre tableau
//            $aOptionValueOnline[] = $dataOneOptionAndOptionValue['OptionValueFournisseurSelected'];
//        }

        // Pour chaque productRule actuellement applicable au produit
//        foreach($this->getAProductRules() as $productRule)
//        {
            // si cette régle est applicable au option
//            if($productRule->isApplicableToOptionsValues($aOptionValueOnline))
//            {
                // on l'ajoute à notre tableau de retour
//                $return[] = $productRule;
//            }
//        }

//        return $return;
//    }

    /**
     * Cette fonction traite les mails qui arrive sur la boite achat
     * @param AchattodbEmail $achattodbEmail objet de notre mail à traiter
     * @param TLockProcess $lockProcess
     */
    public function manageMail(AchattodbEmail $achattodbEmail, TLockProcess $lockProcess)
    {
        $matchesSubject	 = [];
        $matchesBody	 = [];

        // on récupére le log du lockprocess
        $this->setLog($lockProcess->getLog());

        // traitement mail de nouvelle commande
        if(preg_match($this->_pcreMailNewOrderSubject(), $achattodbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achattodbEmail->getId() . ' de nouvelle commande ' . $this->getNomFour() . ' ' . $matchesSubject[1]);

            // on traite le mail
            return $this->_manageMailNewOrder($achattodbEmail, $matchesSubject[1]);
        }

        // si il s'agit d'un mail d'une commande en erreur
        if(preg_match($this->_pcreMailErrorFileSubject(), $achattodbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achattodbEmail->getId() . ' de commande en erreur ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // on traite le mail d'erreur
            return $this->_manageMailFileError($achattodbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // si il s'agit d'un mail d'annulation de commande
        if(preg_match($this->_pcreMailOrderCanceledSubject(), $achattodbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achattodbEmail->getId() . ' d\'annulation de commande ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // on traite le mail d'annulation de commande
            return $this->_manageMailOrderCanceled($achattodbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // traitement mail de passage en production
        if(preg_match($this->_pcreMailProductionSubject(), $achattodbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achattodbEmail->getId() . ' de passage en production de la commande ' . $this->getNomFour() . ' ' . $matchesSubject[1]);

            // on traite le mail
            return $this->_manageMailProduction($achattodbEmail, $matchesSubject[1], $matchesSubject[2]);
        }

        // traitement mail d'expédition de commande
        if(preg_match($this->_pcreMailDispatchedSubject(), $achattodbEmail->getSubject(), $matchesSubject) && preg_match($this->_pcreMailDispatchedBody(), $achattodbEmail->getMessageHtml(), $matchesBody))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achattodbEmail->getId() . ' d\'expedition de ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // on traite le mail
            return $this->_manageMailDispatched($achattodbEmail, $matchesSubject[1], $matchesSubject[2], $matchesBody[1], $matchesBody[2]);
        }

        // traitement email de confirmation de commande
        if(preg_match($this->_pcreMailOrderConfirmationSubject(), $achattodbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achattodbEmail->getId() . ' de confirmation de commande ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            return $this->_manageMailOrderConfirmation($achattodbEmail, $matchesSubject[1]);
        }

        // traitement mail de facture
        if(preg_match($this->_pcreMailInvoiceSubject(), $achattodbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achattodbEmail->getId() . ' de facture ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // on traite le mail
            return $this->_manageMailInvoice($achattodbEmail, $matchesSubject[1]);
        }

        // si il s'agit d'un mail de réponse à une demande
        if(preg_match($this->_pcreMailResponseToARequestSubject(), $achattodbEmail->getSubject()))
        {
            $this->getLog()->addLogContent('Email de réponse à une demande ' . $this->getNomFour());

            // on passe juste le mail en traité car il n'a aucun intéret
            $achattodbEmail->setStatus(AchattodbEmail::STATUS_PROCESSED)
                ->save();

            // tout est bon
            return true;
        }

        // si il s'agit d'un mail de structures non conformes
        if(preg_match($this->_pcreMailNonCompliantStructuresSubject(), $achattodbEmail->getSubject(), $matchesSubject))
        {
            $lockProcess->updateStage('Traitement de l\'email ' . $achattodbEmail->getId() . ' de commande en erreur à cause de structures non conformes ' . $this->getNomFour() . ' pour la commande fournisseur ' . $matchesSubject[1]);

            // on traite comme un mail d'erreur
            return $this->_manageMailFileError($achattodbEmail, $matchesSubject[1], null);
        }

        // autre mail
        $lockProcess->getLog()->Erreur('mail ' . $this->getNomFour() . ' de type inconnu.');
        $lockProcess->getLog()->Erreur('id : ' . $achattodbEmail->getId());

        return false;
    }

    /**
     * traite un email d'expédition
     * @param AchattodbEmail $achattodbEmail le mail
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @return boolean true si on a réussi à récupérer les infos et false si on a un probléme
     */
    private function _manageMailNewOrder(AchattodbEmail $achattodbEmail, string $supplierOrderId)
    {
        $matches = array();

        // si on trouve notre numéro de commande
        if(preg_match($this->_pcreMailNewOrderBody(), $achattodbEmail->getMessage(), $matches))
        {
            // on récupére l'id de commande
            $orderId = $matches[1];
        }
        // pas de numéro de commande
        else
        {
            $orderId = null;
        }

        // on met à jour la commande fournisseur ou la créé au besoin. On passe la commande en production car on n'a pas d'autre mail de saxo
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_FILE_RECEIVED, $achattodbEmail, $orderId);
    }

    /**
     * traite un email d'erreur de fichier
     * @param AchattodbEmail $achattodbEmail le mail
     * @param int $supplierOrderId l'id de la commande chez le fournisseur
     * @param string $jobId numéro du job
     * @return boolean TRUE si on a réussi à récupérer les infos et FALSE si on a un probléme
     */
    private function _manageMailFileError(AchattodbEmail $achattodbEmail, int $supplierOrderId, string $jobId)
    {
        // on met à jour la commande fournisseur
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_ERROR, $achattodbEmail, null, null, null, $jobId, $achattodbEmail->getMessage(), OrdersStatus::STATUS_DEPART_FAB_RETOUR);
    }

    /**
     * Traitement du mail de passage en fabrication
     * @param AchattodbEmail $achattodbEmail	Objet AchattodbEmail
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @param string $jobId numéro du job
     * @return bool true si tout se passe bien et false en cas de probléme
     */
    protected function _manageMailProduction(AchattodbEmail $achattodbEmail, string $supplierOrderId, string $jobId)
    {
        // on met à jour la commande fournisseur
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_PRODUCTION, $achattodbEmail, null, null, null, $jobId);
    }

    /**
     * traite un email d'expédition
     * @param AchattodbEmail $achattodbEmail le mail
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @param string $jobId numéro du job de la commande chez le fournisseur
     * @param string $trackingUrl url du colis
     * @param string $trackingNumber numéro du colis
     * @return boolean true si on a réussi à récupérer les infos et false si on a un probléme
     */
    private function _manageMailDispatched(AchattodbEmail $achattodbEmail, string $supplierOrderId, string $jobId, string $trackingUrl, string $trackingNumber)
    {
        // Notre tableau qui contient type du colis, les numero command et l'url
        $aDeliveryInformation = array();

        // Récupération du transporteur par rapport à l'url de tracking
        $supplier = TTransporteur::findByTrackingUrl($trackingUrl);

        // Si on n'a pas d'url de tracking ce qui arrive souvent chez online
        if($trackingUrl == '')
        {
            // pas d'information de colis
            $aDeliveryInformation = null;
        }
        // si on n'a pas trouvé le transporteur
        elseif($supplier == false)
        {
            // on ajoute des infos dans le log
            $this->getLog()->Erreur('Transporteur inconnu pour le tracking');
            $this->getLog()->Erreur('url = ' . $trackingUrl);

            // pas d'information de colis
            $aDeliveryInformation = null;
        }
        // on connait le transporteur
        else
        {
            // création du texte du colis :
            $aDeliveryInformation['idTransporteur']	 = $supplier->getIdTransporteur();
            $aDeliveryInformation['transporteur']	 = $supplier->getTraNomComplet();
            $aDeliveryInformation['numColis']		 = $trackingNumber;

            // on remplace la local pour avoir un mail en francais et on supprime l'encodge des &amp; qui plante les url
            $aDeliveryInformation['urlColis'] = str_replace(array('=en_GB'), array('=fr_FR'), $trackingUrl);
        }

        // on recherche la commande fournisseur ou la créé et la met à jour au besoin
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_DISPATCHED, $achattodbEmail, null, null, null, $jobId, null, OrdersStatus::STATUS_EXPEDITION, $aDeliveryInformation);
    }

    /**
     * gestion des mail de facture
     * @param AchattodbEmail $achattodbEmail le mail
     * @param string $supplierOrderId numéro de la commande chez le fournisseur
     * @return bool true en cas de succés et false en cas de probléme
     */
    protected function _manageMailInvoice(AchattodbEmail $achattodbEmail, string $supplierOrderId)
    {
        // on met à jour la commande fournisseur ou la créé au besoin. On passe la commande en exépdié au cas ou elle n'y soit pas déjà
        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_DISPATCHED, $achattodbEmail, null, null, null, null, $this->getNomFour() . ' a généré une facture.');
    }

    /**
     * Envoi d'une commande fournisseur en depart fab chez ce fournisseur
     * On créé une fonction pour utilisé le module online dans la maj du catalogue lgi
     * @param TSupplierOrder $supplierOrder Commande que l'on envoi
     * @param TLockProcess $lockProcess objet lockProcess pour mettre à jour l'étape (facultatif)
     * @param bool $autoLaunch [=true] true pour les lancement auto et false pour les lancement manuel. Inutilisié pour ce fournisseur
     * @return bool false car non géré pour ce fournisseur
     */
//    public function supplierOrderAutoLaunch(TSupplierOrder $supplierOrder, TLockProcess $lockProcess, bool $autoLaunch = true)
//    {
//        unset($supplierOrder);
//        unset($lockProcess);
//        unset($autoLaunch);
//
//        return false;
//    }

    /**
     * traite un email d'annulation de commande
     * @param AchattodbEmail $achattodbEmail le mail
     * @param int $supplierOrderId l'id de la commande chez le fournisseur
     * @return boolean TRUE si on a réussi à récupérer les infos et false si on a un probléme
     */
//    private function _manageMailOrderCanceled(AchattodbEmail $achattodbEmail, int $supplierOrderId, $jobId)
//    {
        // on met à jour la commande fournisseur
//        return $this->updateOrderSupplier($supplierOrderId, TSupplierOrderStatus::ID_STATUS_CANCELED, $achattodbEmail, null, null, null, $jobId, 'Commande ' . $supplierOrderId . ' annulée par ' . $this->getNomFour() . '.', OrdersStatus::STATUS_DEPART_FAB_RETOUR);
//    }
}