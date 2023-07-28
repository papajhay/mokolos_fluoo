<?php declare(strict_types=1);

namespace App\Helper;

class FlashMessages
{
    public const TYPE_SUCCES = 'Succes';
    public const TYPE_ERREUR = 'Erreur';
    public const TYPE_NOTICE = 'Notice';
    public const TYPE_ALERT = 'Alert';

    /**
     * Ajoute un message ou plusieur message si on a un tableau
     * si le tableau est vide aucun message ne sera ajouté.
     *
     * @param string         $type    Type de message ex : (FlashMessages::TYPE_*)
     * @param string|array[] $content Contenu du message ou tableau des différents contenu de message
     *
     * @return bool FALSE si le message existé déjà et n'est pas ajouté. retourne toujour TRUE si un array est envoyé en paramétre
     */
    public static function add(string $type, array|string $content): bool
    {
        // si on a un tableau
        if (is_array($content)) {
            // pour chaque message du tableau
            foreach ($content as $contentElement) {
                // on ajoute le message
                self::add($type, $contentElement);
            }

            return true;
        }

        // si on a pas encore de flash message en session
        if (!isset($_SESSION['flash_message'])) {
            // on initialise la variable
            $_SESSION['flash_message'] = [];
        }

        // ajout du flash message en session
        if (!isset($_SESSION['flash_message'][$type])) {
            $_SESSION['flash_message'][$type] = [];
        }

        // pour chaque message en session
        foreach ($_SESSION['flash_message'][$type] as $value) {
            // si notre message est déjà en session
            if ($value === $content) {
                // on quitte la fonction
                return false;
            }
        }

        $_SESSION['flash_message'][$type][] = $content;

        return true;
    }

    /**
     * Retourne tout les messages ajouter et les supprimes ensuite.
     */
    public static function get(): array
    {
        if (!isset($_SESSION['flash_message'])) {
            return [];
        } else {
            Tools::deepKsort($_SESSION['flash_message']);
            $m = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);

            return $m;
        }
    }

    /**
     * Transforme un array de TMessage ou un TMessage seul en FlashMessage.
     * @param  TMessage|TMessage[] $message objet de Message ou tableau d'objet de message
     * @param  string|null         $type    =NULL par défaut récupére le Type du message, on peux forcer le type de message en envoyant une constante de FlashMessage
     * @return bool                FALSE si le message existé déjà et n'est pas ajouté
     */
    public static function addByMessage(array|TMessage $message, string $type = null): bool
    {
        // si on a pas un tableau
        if (!is_array($message)) {
            // ajoute notre erreur dans un tableau
            $aTMessage = [];
            $aTMessage[] = $message;
        }
        // on a bien un tableau
        else {
            $aTMessage = $message;
        }

        // pour chaque message
        foreach ($aTMessage as $tMessage) {
            // si on a pas de type de message
            if (null === $type) {
                // on prend le type du message
                $typeMessage = $tMessage->getMesType();
            }
            // on a un type de message qui a était forcé
            else {
                // on le prend
                $typeMessage = $type;
            }

            // ajoute le message
            self::add($typeMessage, $tMessage->replaceVariableErrText());
        }

        return true;
    }

    /**
     * ajoute un message dans un flashMessage à partir d'un id de message.
     * Renvoi une erreur inconnu si l'erreur n'existe pas.
     * @param  int         $idMessage    id du message
     * @param  string|null $type         =NULL par défaut récupére le Type du message, on peux forcer le type de message en envoyant une constante de FlashMessage
     * @param  array       $paramMessage =array() paramétre pour les message
     * @return bool        FALSE si le message existé déjà et n'est pas ajouté
     */
    public static function addByIdMessage(int $idMessage, string $type = null, array $paramMessage = []): bool
    {
        // on récupére l'objet d'erreur
        $message = TMessage::findById($idMessage);

        // si on a pas récupéré d'erreur
        if (null === $message->getIdMessage()) {
            // on charge une erreur inconnu
            $message = TMessage::findById(TMessage::ERR_INCONNUE);

            // on forcera le message en type erreur
            $type = self::TYPE_ERREUR;
        }

        // mise à jour des paramétres des messages
        $message->updateParamByArray($paramMessage);

        // on ajoute l'erreur
        return self::addByMessage($message, $type);
    }

    /**
     * renvoi true si il n'ya pas de message à afficher.
     * @return bool
     */
    public static function checkEmpty(): bool
    {
        // si il y a des messages à afficher
        if (isset($_SESSION['flash_message'])) {
            return false;
        }
        // si il n'y a pas de messages à afficher
        else {
            return true;
        }
    }

    /**
     * Ajoute les flash messages relative a certaines periodes de l'annee.
     */
    public static function messagesAnnuels(): void
    {
        // si on est a la fin de l'annee
        if (12 === System::today()->getMois() && System::today()->getJour() >= 10 || 1 === System::today()->getMois() && System::today()->getJour() <= 3) {
            // ajout du message joyeux noel
            FlashMessages::addByIdMessage(TMessage::NOT_JOYEUX_NOEL);
        }

        // si on est en ete
        if (!System::getCurrentHost()->isInFusion() && (7 === System::today()->getMois() || 8 === System::today()->getMois())) {
            // ajout du message joyeux noel
            FlashMessages::addByIdMessage(TMessage::NOT_SUMMER);
        }

        // récupération du coefficient de reduction des marges lié aux dates
        $margeCoefficient = TMargeCoefficient::coefficientByHostAndTypeNow(null, true, false);

        // si on a une reduction de marge temporaire
        if (!$margeCoefficient->isDefault()) {
            // on récupére le message
            $message = TMessage::findById(TMessage::NOT_REDUCTION_MARGE_DATE);

            // on change les variables
            $message->setMesText(Template::replaceVariableFrom($message->getMesText(), ['dateMin' => $margeCoefficient->getMarCoeStartDateHeure()->format(DateHeure::DATEFR), 'dateMax' => $margeCoefficient->getMarCoeEndDateHeure()->format(DateHeure::DATEFR), 'description' => $margeCoefficient->getMarCoeDescription(), 'produits' => $margeCoefficient->productNameForDisplay()]));

            // on affiche le message
            FlashMessages::addByMessage($message);
        }
    }
}
