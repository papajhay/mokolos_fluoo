<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TMessage;

class TMessageService
{
    /*
    * Remplace les variables du texte venant de la base
    * @return type
    */
    public function replaceVariableErrText(TMessage $TMessage)
    {
        // TODO create class Template
        return Template::replaceVariableFrom($TMessage->getText(), ['tError' => $TMessage]);
    }

    /*
     * met à jout les paramétres du message
     * @param type $aParam tableau des paramétre avec en clef l'id correspondant au type de champ et en paramétre la valeur
     */
    public function updateParamByArray(TMessage $message, array $aParam): void
    {
        // pour chaque param
        foreach ($aParam as $idParam => $param) {
            // suivant le type de paramétre on met à jour le champ qui correspond
            switch ($idParam) {
                case TMessage::PARAM_ERR_FIELD:
                    $message->setErrField($param);
                    break;
                case TMessage::PARAM_ERR_MIN:
                    $message->setErrCharMin($param);
                    break;
                case TMessage::PARAM_ERR_MAX:
                    $message->setErrCharMax($param);
                    break;

                default:
                    break;
            }
        }
    }
}
