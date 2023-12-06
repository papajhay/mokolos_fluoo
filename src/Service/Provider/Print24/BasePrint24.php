<?php declare(strict_types=1);

namespace App\Service\Provider\Print24;

use App\Entity\AchattodbEmail;
use App\Entity\Provider;
use App\Entity\TLockProcess;
use App\Entity\TMessage;
use App\Entity\TSupplierOrderStatus;
use App\Helper\Curl;
use App\Service\Provider\BaseProvider;

class BasePrint24 extends BaseProvider
{
    /**
     * séparateur utilisé pour la selection p24
     */
    public const SEPARATEUR_SELECTION = '|';

    /**
     *  fichier utilisé par les requête curl pour les commande auto
     */
    public const CURL_COOKIE_COMMANDE_AUTO = '/tmp/curl_cookie/p24CommandeAuto.txt';

    /**
     * constante utilisé pour définir une adresse de livraison
     */
    public const ADDRESSE_LIVRAISON = 1;

    /**
     * constante utilisé pour définir une adresse dexpédition
     */
    public const ADDRESSE_EXPEDITION = 2;

    /**
     * Id du fournisseur pour la France
     */
    public const ID_FOUR_FR = 12;

    /**
     * Id du fournisseur pour la Belgique
     */
    public const ID_FOUR_BE = 58;

    /**
     * Id du fournisseur pour la Suisse
     */
    public const ID_FOUR_CH = 59;

    /**
     * Id du fournisseur pour le Luxembourg
     */
    public const ID_FOUR_LU = 60;

    /**
     * Id du fournisseur pour la France avec livraison à l'étranger
     */
    public const ID_FOUR_FR_HT = 92;

    /**
     * id du délai standard chez p24
     */
    public const DELAY_ID_STANDARD = 3764;

    /**
     * id du délai standard chez p24
     */
    public const DELAY_ID_FAST = 3762;

    /**
     * id du délai standard chez p24
     */
    public const DELAY_ID_FASTER = 3763;

    /**
     * id du délai standard chez p24
     */
    public const DELAY_ID_ECO = 4562;

    /**
     * id p24 de l'option value des quantité utilisé par défaut dans la récupération des prix
     * @var int
     */
    public const QUANTITY_DEFAULT_ID_FOURNISSEUR = 340;

    /**
     * id l'option value des quantité utilisé par défaut dans la récupération des prix
     * @var int
     */
    public const QUANTITY_DEFAULT_ID_OPTION_VALUE = 5417;

    /**
     * id p24 de l'option value des délai utilisé par défaut dans la récupération des prix
     * @var int
     */
    public const DELAY_DEFAULT_ID = self::DELAY_ID_STANDARD;

    /**
     * id p24 de l'option des délai utilisé par défaut dans la récupération des prix
     * @var int
     */
    protected int $idP24Defaultshipping = self::DELAY_ID_STANDARD;

    /**
     * objet curl utilisé lors de la passation de commande auto
     * @var Curl
     */
    private ?Curl $curl = NULL;

    /**
     * id de session utilisé  lors de la passation de commande auto
     * @var string
     */
    private string $sid;

    /**
     * tableau avec tous les id option value fournisseur des délai de p24
     * @var type
     */
    protected static array|type $_aShippingIdOptionValueFournisseur = [self::DELAY_ID_STANDARD, self::DELAY_ID_FAST, self::DELAY_ID_FASTER, self::DELAY_ID_ECO];

    /**
     * talbeau des paramétre de la commande
     * @var array
     */
    private $_aParamCommande = [];

    /**
     * getteur de l'objet curl utilisé lors de la passation de commande auto
     * @return Curl
     */
    public function getCurl()
    {
        // si on n'a pas encore de requête curl
        if($this->curl == NULL)
        {
            // on créé la nouvelle requête
            $this->curl = new Curl();
        }

        return $this->curl;
    }

    public function getSid(): string
    {
        return $this->sid;
    }

    public function setSid($sid): static
    {
        $this->sid = $sid;

        return $this;
    }

    public function getParamCommande(): array
    {
        return $this->_aParamCommande;
    }

    public function setParamCommande($paramCommande): static
    {
        $this->_aParamCommande = $paramCommande;

        return $this;
    }

    /**
     * masque utilisé pour récupéré les délais dans le menu déroulant
     * @return string
     */
    protected function _masqueDelai(): string
    {
        return '#(\d\d\.\d\d\.)#';
    }

    /**
     * renvoi le masque PCRE pour extraire le session ID sur le site de p24
     * @return string
     */
    protected function masqueSid(): string
    {
        return '#\?sid=([^&"]+)[&"]#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le mail de confirmation de commande
     * @return string
     */
    protected function _pcreMailOrderConfirmationSubject(): string
    {
        return '#(?:Informations concernant la commande print24 –|Confirmation de commande.*commande)\s*(\d+)$#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le sujet des mails de fichier impossible à télécharger
     * @return string
     */
    protected function _pcreMailFileErrorDownloading(): string
    {
        return '#INFORMATIONS IMPORTANTES CONCERNANT VOTRE COMMANDE (\d+) – TÉLÉCHARGEMENT DES DONNÉES D\s*\'IMPRESSION IMPOSSIBLE#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le mail de fichier validé
     * @return string
     */
    protected function _pcreMailFileValidate(): string
    {
        return '#Confirmation de contrôle des données.*numéro.*\D(\d+)-(\d+)\s*$#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le sujet d'un mail d'état de la commande
     * @return string
     */
    protected function _pcreMailOrderStateSubject(): string
    {
        return '#Etat (?:de la commande|du travail).*de\s+commande.*\D(\d+)-(\d+)\s*$#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le mail de fichier recu
     * @return string
     */
    protected function _pcreMailFileReceived(): string
    {
        return '#Confirmation d\'\s*entrée de données .*numéro.*\D(\d+)-(\d+)\s*$#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le mail de passage en fabrication
     * @return string
     */
    protected function _pcreMailProduction(): string
    {
        return '#Votre commande se trouve actuellement (?:dans le domaine|au Service de) fa\S+onnage et \S+impression#i';
    }

    /**
     * renvoi le masque PCRE pour récupérer le mail d'annulation de commande
     * @return string
     */
    protected function _pcreMailOrderCanceledBody(): string
    {
        return '#Votre (?:commande|ordre) \S+ a été annulé#i';
    }

    /**
     * renvoi le masque PCRE pour récupérer le mail de crédit d'impression
     * @return string
     */
    protected function _pcreMailPrintCredit(): string
    {
        return '#Votre crédit d\'impression#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le mail concernant un délai
     * @return string
     */
    protected function _pcreMailDelaySubject(): string
    {
        return '#Information sur le délai.*(?:commande|référence)\s*(\d+)-(\d+)\s*$#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le mail concernant un délai
     * @return string
     */
    protected function _pcreMailDelayBody(): string
    {
        $masque	 = '#';
        $masque	 .= '(?:';
        // une seul nouvelle date de livraison
        $masque	 .= 'prévu[a-zA-Z\s,]+le \d{2}\.\d{2}\.\d{4}\D[a-zA-Z\s,]+le ';
        $masque	 .= '|';
        // 2 nouvelles dates de livraison
        $masque	 .= 'livrée le \d{2}\.\d{2}\.\d{4}\D.+ici le ';
        $masque	 .= ')';
        // récupération de la date (la premiére si il y en a 2)
        $masque	 .= '(\d{2}\.\d{2}\.\d{4})\D';
        // récupération de la 2e date si on en a 2
        $masque	 .= '(?:.*le (\d{2}\.\d{2}\.\d{4})\D|)';
        $masque	 .= '#';

        return $masque;
    }

    /**
     * renvoi le masque PCRE pour récupérer le mail de facture
     * @return string
     */
    protected function _pcreMailInvoice(): string
    {
        return '#^Facture \d.* commande (\d+)$#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le mail de facture
     * @return string
     */
    protected function _pcreMailCancelInvoice(): string
    {
        return '#^Facture d’annulation#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le mail de reimpression avec retard
     * @return string
     */
    protected function _pcreMailErrorFileSubject(): string
    {
        return '#INFORMATION IMPORTANTE .* CONTR.*COMMANDE N\S* (\d+)-(\d+) -#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le sujet du mail d'expédition
     * @return string
     */
    protected function _pcreMailDispatchedSubject(): string
    {
        return '#Confirmation d\’expédition#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le numéro de commande p24 quand on n'a la position
     * @return string
     */
    protected function _pcreMailDispatchedBody(): string
    {
        return '#Position de la commande\s*:\s*(\d+)-(\d+)\D#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le sujet du mail d'evaluation (p24 suisse)
     * @return string
     */
    protected function _pcreMailEvaluationSubject(): string
    {
        return '#Venez évaluer nos services#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le sujet des mails sur l'état du travail
     * @return string
     */
    protected function _pcreMailWorkingStateSubject(): string
    {
        return '#Etat du travail.*référence\s*\(\s*(\d+)-(\d+)\s*\)#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le mail d'erreur impossible à corriger par p24
     * @return string
     */
    protected function _pcreMailErrorNeedNewFileBody(): string
    {
        return '#malheureusement pas traiter au moins un défaut décelé sur vos données#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le corp du mail sur les fichiers manquants
     * @return string
     */
    protected function _pcreMailMissingFileBody(): string
    {
        return '#limite d\’entrée des données avait dépassé le délai#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le sujet du mail d'offre de sauvegarde des données
     * @return string
     */
    protected function _pcreMailDataSaveSubject(): string
    {
        return '#Offre de sauvegarde de données#';
    }

    /**
     * renvoi le masque PCRE pour récupérer le sujet du mail d'avoir
     * @return string
     */
    protected function _pcreMailCreditNoteSubject(): string
    {
        return '#Numéro de référence (\d+) - avoir#';
    }

    /**
     * renvoi le masque PCRE pour récupérer les URL DPD
     * @return string
     */
    protected function _pcreUrlDPD(): string
    {
        return '#(?:verknr1|pknr)=([^&]+)&#';
    }

    /**
     * renvoi le masque PCRE pour récupérer les numéro de colis DPD
     * @return string
     */
    protected function _pcreParcelDPD(): string
    {
        return '#de suivi de colis: <[^>]+>([\d]+)\D#';
    }

    /**
     * renvoi le masque PCRE pour récupérer les URL DHL
     * @return string
     */
    protected function _pcreUrlDHL(): string
    {
        return '#dhl\.com.*tracking-id=\s*([a-zA-Z\d]+)$#';
    }


    /**
     * ajoute des information sur l'erreur dans le message
     * @param string $message le message auquel on doit ajouter des informations
     * @return string
     */
    public function addErrorTypeToMessage(string $message): string
    {
        // gestion des differents codes d'erreurs
        if($this->getErrorCode() == TMessage::ERR_ZIP_CORRUPTED)
        {
            $message .= "\n" . 'le fichier zip est corrompu';
        }

        return $message;
    }

    /**
     * calcul un délai en jour ouvré par rapport à une date brut de p24
     * @param string $dateP24Row
     * @return type int
     */
    protected function delaiFromDate($dateP24Row): type
    {
        // création d'un objet date correspondant à la date actuelle
        $aujourdhui = new DateHeure();

        // création d'un nouvel objet date
        $dateLivraison = new DateHeure($dateP24Row);

        // calcul du délai entre les 2 date
        return Duree::nbJourOuvreEntreDate($aujourdhui, $dateLivraison);
    }

    /**
     * On surcharge la fonction pour renvoyé toujours TRUE pour ne pas avoir de problème quand cathy fait des choses qui ne devrait pas
     * @param int $idOrder id de la commande
     * @return boolean TRUE
     */
    public function fichierDisponible($idOrder): bool
    {
        // on unset la variable pour ne pas avoir d'erreur dans netbeans
        unset($idOrder);

        return TRUE;
    }

    /**
     * découpe la rue pour en extraire le numero
     * @param string $rueComplete le nom complet de la rue
     * @return array un tableau constitué du numéro et du reste de l'adresse
     */
    public function decoupeRue($rueComplete): array
    {
        // on commence par retirer les accents et tous les trucs qui pourrait nous embéter
        $rueCompleteSansAccent = ToolsHTML::retireAccents(trim($rueComplete));

        $resultat = array();
        // on verifie si on a un numéro de rue
        if(preg_match('#^(\d+)(\D.+)$#', $rueCompleteSansAccent, $resultat))
        {
            // dans ce cas tous va bien
            return array(
                'numero' => trim($resultat[1]),
                'rue'	 => trim($resultat[2]));
        }
        // autre cas
        else
        {
            // on renvoi tout dans le nom de la rue
            return array(
                'numero' => '-',
                'rue'	 => trim($rueComplete));
        }
    }

    /**
     * pour un produit sur ligbe le fournisseur qui est chargé est p24FR
     * cette méthode permet de corriger ce problème
     */
    public function correctThis(): void
    {
        // cette méthode est toujours appelé avec p24 fr, on regarde si jamais on aurait du l'appelé pour un autre p24
        if(System::getCurrentHost()->getIdFourP24() <> $this->getIdFour() && !System::isAdminContext())
        {
            // dans ce cas on change l'id de fournisseur pour avoir le bon et on recharge notre objet
            $this->setIdFour(System::getCurrentHost()->getIdFourP24());
            $this->reloadPrimaryValue();
            $this->load();
        }
    }

    /**
     * fonction permettant de se logger sur le site de p24
     * @return boolean TRUE en cas de succés et FALSE en cas d'echec
     */
    protected function _loginSite(): bool
    {
        $resultat = array();

        // on définit un timeout de 5min pour les cas ou le site de p24 tourne en boucle sans répondre
        $this->getCurl()->setOptAllTimeout(300);

        // variable post nécessaire pour ce logger sur le site de p24
        $post = array(
            'email'		 => $this->getFouSiteLogin(),
            'pwd'		 => $this->getFouSitePass(),
            'pg'		 => 'track',
            'cmd'		 => 'detail',
            'fromlogin'	 => '1',
            'usrcmd'	 => 'login_send');

        // passage des bons paramétres à curl
        $this->getCurl()->setOptUrl($this->getFouSiteAdresse() . '/');
        $this->getCurl()->setOptPost(1, $post);
        $this->getCurl()->setOptCookieFile(self::CURL_COOKIE_COMMANDE_AUTO);

        // execution de la requête curl
        $page = $this->getCurl()->exec();

        // si on arrive à récupéré le sid sur la page de p24
        if(preg_match($this->masqueSid(), $page, $resultat))
        {
            $this->setSid($resultat[1]);
            return TRUE;
        }
        // un problème à eu lieu
        else
        {
            return FALSE;
        }
    }

    /**
     * Renvoi le contenu de la page du site p24 d'une commande. Nécessite que l'objet curl soit configuré et qu'on ai un sid
     * @param int $IdOrderP24 id de la commande p24
     * @return string contenu de la page
     */
    public function sitePageOrder($IdOrderP24): string
    {
        // configuration du post
        $post = array(
            'sid'			 => $this->getSid(),
            'pg'			 => 'track',
            'cmd'			 => 'detail',
            'ordernumber'	 => $IdOrderP24);

        // on execute la requête curl
        $this->getCurl()->setOptUrl($this->getFouSiteAdresse() . '/?pg=track&cmd=detail&ordernumber=' . $IdOrderP24 . '&sid=' . $this->getSid());
        $this->getCurl()->setOptPost(1, $post);
        return $this->getCurl()->exec();
    }

    /**
     * Renvoi la liste des noms de fichiers normalement présent pour une selection
     * @param SelectionFournisseur $selection
     * @param int $idOrder id de la commande
     * @return array une liste de nom de fichier ou FALSE si on n'a pas d'information
     */
    public function filesListFromSelection(SelectionFournisseur $selection, $idOrder): array
    {
        // selection non utilisé pour ce fournisseur
        unset($selection);

        // par défaut fichier pdf
        return array($idOrder . '.zip');
    }

    /**
     * Copie la facture d'un webmail
     * @param AchattodbEmail $achatToDbEmail
     * @return boolean true en cas de succés et false en cas de probléme
     */
    protected function _copyInvoice(AchattodbEmail $achatToDbEmail): bool
    {
        // pour chaque fichier joint à la commande
        foreach($achatToDbEmail->getAAttach() as $attachment)
        {
            // si on est sur le fichier cms
            if($attachment->getFile()->getExtention() == 'cms')
            {
                // on passe au fichier suivant
                continue;
            }

            // si le fichier n'existe pas
            if(!$attachment->getFile()->exist())
            {
                $this->getLog()->Error('Le fichier de facture "' . $attachment->getFile()->getCheminComplet() . '" n\'existe pas.');

                // on va retraité le mail
                $achatToDbEmail->needReprocess();

                // on quitte la fonction
                return false;
            }

            // nom complet du fichier de destination
            $destinationFullPatch = $this->getDirFactures() . $achatToDbEmail->getDateHeureSend()->format(DateHeure::MOISPOURREPERTOIRE) . '/' . $attachment->getFileNameOrg();

            // si on n'arrive pas à copier le fichier
            if(!$attachment->getFile()->copy($destinationFullPatch))
            {
                $this->getLog()->Error('Impossible de copier la facture "' . $attachment->getFile()->getCheminComplet() . '" vers "' . $destinationFullPatch . '".');

                // on va retraité le mail
                $achatToDbEmail->needReprocess();

                // on quitte la fonction
                return false;
            }
        }

        return true;
    }
}