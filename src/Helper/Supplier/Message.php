<?php declare(strict_types=1);

namespace App\Helper\Supplier;

class Message
{
    /**
     * Tableaux des erreurs.
     * @var string[]
     */
    private static array $_aError = [];

    /**
     * Tableaux des informations.
     * @var string[]
     */
    private static array $_aInfo = [];

    /**
     * Tableaux des averissements.
     * @var string[]
     */
    private static array $_aWarning = [];

    /**
     * tableau contenant toutes les variables du fournisseur.
     */
    private static array $_aVariableValues = [];

    /**
     * getteur du Tableaux des erreurs.
     * @return string[]
     */
    public static function getAError(): array
    {
        return self::$_aError;
    }

    /**
     * getteur du Tableaux des informations.
     * @return string[]
     */
    public static function getAInfo(): array
    {
        return self::$_aInfo;
    }

    /**
     * getteur du Tableaux des averissements.
     * @return string[]
     */
    public static function getAWarning(): array
    {
        return self::$_aWarning;
    }

    /**
     * getteur du tableau contenant toutes les variables du fournisseur.
     */
    public static function getAVariableValues(): array
    {
        return self::$_aVariableValues;
    }

    /**
     * setteur du tableau contenant toutes les variables du fournisseur.
     */
    public static function setAVariableValues(array $aVariableValues): void
    {
        self::$_aVariableValues = $aVariableValues;
    }

    /**
     * Prépare un message avant de l'inséré dans notre objet.
     * @param string $originalMessage le message original
     */
    protected function _prepareMessage(string $originalMessage): string
    {
        // on remplace les variables du fournisseurs
        $msgWithVariable = str_replace(array_keys(self::getAVariableValues()), self::getAVariableValues(), $originalMessage);

        // on supprime les tage HTML à l'exception des tags autorisé
        $msgWithoutTag = strip_tags($msgWithVariable, '<img>');

        // on remplace les &amp;
        $msgWithoutAmp = str_replace('&amp;', '&', $msgWithoutTag);

        // on renvoi le message en transformant les retour à la ligne en <br>
        return nl2br(trim($msgWithoutAmp));
    }

    /**
     * ajoute une erreur.
     * @param int $idOption = 0 id de l'option à laquelle le message est lié ou 0 si non applicable
     */
    public function addError(string $errorMsg, int $idOption = 0)
    {
        // on prépare le message
        $message = $this->_prepareMessage($errorMsg);

        // si on a un message vide
        if ('' === $message) {
            // on ne fait rien
            return true;
        }

        // on ajoute l'erreur à notre tableau
        self::$_aError[$idOption][] = $message;
    }

    /**
     * ajoute une info.
     * @param string $infoMsg  le message d'information
     * @param int    $idOption = 0 id de l'option à laquelle le message est lié ou 0 si non applicable
     */
    public function addInfo(string $infoMsg, int $idOption = 0)
    {
        // on prépare le message
        $message = $this->_prepareMessage($infoMsg);

        // si on a un message vide
        if ('' === $message) {
            // on ne fait rien
            return true;
        }

        // on ajoute l'erreur à notre tableau
        self::$_aInfo[$idOption][] = $message;
    }

    /**
     * ajoute une alerte.
     * @param int $idOption = 0 id de l'option à laquelle le message est lié ou 0 si non applicable
     */
    public function addWarning(string $warningMsg, int $idOption = 0)
    {
        // on prépare le message
        $message = $this->_prepareMessage($warningMsg);

        // si on a un message vide
        if ('' === $message) {
            // on ne fait rien
            return true;
        }

        // on ajoute l'erreur à notre tableau
        self::$_aWarning[$idOption][] = $message;
    }

    /**
     * ajoute un tableau d'erreur.
     * @param string[] $aErrorMsg
     */
    public function addArrayError(array $aErrorMsg): void
    {
        // pour chaque message d'erreur
        foreach ($aErrorMsg as $idOption => $errorMsg) {
            // on ajoute l'erreur
            self::addError($errorMsg, $idOption);
        }
    }

    /**
     * ajoute un tableau d'info.
     * @param string[] $aInfoMsg
     */
    public function addArrayInfo(array $aInfoMsg): void
    {
        // pour chaque message d'info
        foreach ($aInfoMsg as $idOption => $infoMsg) {
            // on ajoute l'info
            self::addInfo($infoMsg, $idOption);
        }
    }

    /**
     * ajoute un tableau d'alerte.
     * @param string[] $aWarningMsg
     */
    public function addArrayWarning(array $aWarningMsg): void
    {
        // pour chaque message d'alerte
        foreach ($aWarningMsg as $idOption => $warningMsg) {
            // on ajoute l'alerte
            self::addWarning($warningMsg, $idOption);
        }
    }

    /**
     * indique si on a des erreurs.
     * @return bool TRUE si on a des erreurs et FALSE sinon
     */
    public function haveError(): bool
    {
        // si on a au moins une erreur
        if (count(self::getAError()) > 0) {
            return true;
        }

        return false;
    }

    /**
     * indique si on a des infos.
     * @return bool TRUE si on a des infos et FALSE sinon
     */
    public function haveInfo(): bool
    {
        // si on a au moins une info
        if (count(self::getAInfo()) > 0) {
            return true;
        }

        return false;
    }

    /**
     * indique si on a des alertes.
     * @return bool TRUE si on a des alertes et FALSE sinon
     */
    public function haveWarning(): bool
    {
        // si on a au moins une alerte
        if (count(self::getAWarning()) > 0) {
            return true;
        }

        return false;
    }

    /**
     * indique si on a au moins un message quelque soit son type.
     * @return bool TRUE si on a au mois un message et FALSE sinon
     */
    public function haveMessageAnyType(): bool
    {
        // si on a des erreurs
        if (self::haveError()) {
            // on a des messages
            return true;
        }

        // si on a des informations
        if (self::haveInfo()) {
            // on a des messages
            return true;
        }

        // si on a des alertes
        if (self::haveWarning()) {
            // on a des messages
            return true;
        }

        // aucun message
        return false;
    }

    /**
     * Renvoi tous les message aux format HTML dans un tableau.
     * @return string[]
     */
    public function htmlAllMessages(): array
    {
        $return = [];

        // pour chaque option avec des erreurs
        foreach (self::getAError() as $IdOption => $aMessageForOption) {
            // si on n'a pas de message pour cette option
            if (!isset($return[$IdOption])) {
                // on initialise
                $return[$IdOption] = '';
            }

            // pour chaque message
            foreach ($aMessageForOption as $message) {
                // on ajotue le message d'erreur
                $tpl = new \Template();
                $tpl->assign('flashMessages', [\FlashMessages::TYPE_ERREUR => [$message]]);
                $return[$IdOption] .= $tpl->fetch('layout/_flash-message.tpl', null, null, null, false, true, false, true);
            }
        }

        // pour chaque option avec des avertissement
        foreach (self::getAWarning() as $IdOption => $aMessageForOption) {
            // si on n'a pas de message pour cette option
            if (!isset($return[$IdOption])) {
                // on initialise
                $return[$IdOption] = '';
            }

            // pour chaque message
            foreach ($aMessageForOption as $message) {
                // on ajotue le message d'erreur
                $tpl = new \Template();
                $tpl->assign('flashMessages', [\FlashMessages::TYPE_ALERT => [$message]]);
                $return[$IdOption] .= $tpl->fetch('layout/_flash-message.tpl', null, null, null, false, true, false, true);
            }
        }

        // pour chaque option avec des infos
        foreach (self::getAInfo() as $IdOption => $aMessageForOption) {
            // si on n'a pas de message pour cette option
            if (!isset($return[$IdOption])) {
                // on initialise
                $return[$IdOption] = '';
            }

            // pour chaque message
            foreach ($aMessageForOption as $message) {
                // on ajotue le message d'erreur
                $tpl = new \Template();
                $tpl->assign('flashMessages', [\FlashMessages::TYPE_NOTICE => [$message]]);
                $return[$IdOption] .= $tpl->fetch('layout/_flash-message.tpl', null, null, null, false, true, false, true);
            }
        }

        // on renvoi notre tableau encodé en json
        return $return;
    }

    /**
     * Renvoi tous les message aux format HTML en JSON.
     * @return string le json
     */
    public function jsonHtmlAllMessages(): string
    {
        // on renvoi notre tableau encodé en json
        return json_encode(self::htmlAllMessages());
    }
}
