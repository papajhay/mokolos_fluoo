<?php declare(strict_types=1);

namespace App\Helper;

use App\Repository\TMessageRepository;
use App\Service\TMessageService;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FlashMessages
{
    //    public const TYPE_SUCCES = 'Succes';
    //    public const TYPE_ERREUR = 'Erreur';
    //    public const TYPE_NOTICE = 'Notice';
    //    public const TYPE_ALERT = 'Alert';

    private FlashBagInterface $flashBag;

    public function __construct(
        FlashBagInterface $flashBag,
        private TMessageRepository $TMessageRepository,
        private TMessageService $messageService
    ) {
        $this->flashBag = $flashBag;
    }

    /** Ajoute un message ou plusieur message si on a un tableau
     * si le tableau est vide aucun message ne sera ajouté.
     * @param  string            $type Type de message ex : (FlashMessages::TYPE_*)
     * @return FlashBagInterface FALSE si le message existé déjà et n'est pas ajouté. retourne toujour TRUE si un array est envoyé en paramétre
     */
    public function add(string $type, string $message): FlashBagInterface
    {
        $this->flashBags->add($type, $message);

        return $this->flashBag;
    }

    /** Retourne tout les messages ajouter et les supprimes ensuite.
     */
    public function get(string $type): array
    {
        return $this->flashBag->get($type);
    }

    /** Transforme un array de TMessage ou un TMessage seul en FlashMessage.
     * @param  TMessage|TMessage[] $message objet de Message ou tableau d'objet de message
     * @param  string|null         $type    =NULL par défaut récupére le Type du message, on peux forcer le type de message en envoyant une constante de FlashMessage
     * @return bool                FALSE si le message existé déjà et n'est pas ajouté
     */
    public function addByMessage(array|TMessage $message, string $type = null): bool
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
                $typeMessage = $tMessage->getType();
            }
            // on a un type de message qui a était forcé
            else {
                // on le prend
                $typeMessage = $type;
            }

            // ajoute le message
            $this->add($typeMessage, $tMessage->replaceVariableErrText());
        }

        return true;
    }

    /** ajoute un message dans un flashMessage à partir d'un id de message.
     * Renvoi une erreur inconnu si l'erreur n'existe pas.
     * @param  int         $idMessage    id du message
     * @param  string|null $type         =NULL par défaut récupére le Type du message, on peux forcer le type de message en envoyant une constante de FlashMessage
     * @param  array       $paramMessage =array() paramétre pour les message
     * @return bool        FALSE si le message existé déjà et n'est pas ajouté
     */
    public function addByIdMessage(int $idMessage, string $type = null, array $paramMessage = []): bool
    {
        // on récupére l'objet d'erreur
        $message = $this->TMessageRepository->find($idMessage);

        // si on a pas récupéré d'erreur
        if (null === $message->getId()) {
            // on charge une erreur inconnu
            $message = $this->TMessageRepository->find(TMessage::ERR_INCONNUE);

            // on forcera le message en type erreur
            $type = 'Error';
        }

        // mise à jour des paramétres des messages
        $this->messageService->updateParamByArray($message, $paramMessage);

        // on ajoute l'erreur
        return $this->addByMessage($message, $type);
    }
    // TODO

    /*
     * renvoi true si il n'ya pas de message à afficher.
     */
    //    public static function checkEmpty(): bool
    //    {
    //        // si il y a des messages à afficher
    //        if (isset($_SESSION['flash_message'])) {
    //            return false;
    //        }
    //        // si il n'y a pas de messages à afficher
    //        else {
    //            return true;
    //        }
    //    }

    /*
     * Ajoute les flash messages relative a certaines periodes de l'annee.
     */
    //    public static function messagesAnnuels(): void
    //    {
    //        // si on est a la fin de l'annee
    //        if (12 === System::today()->getMois() && System::today()->getJour() >= 10 || 1 === System::today()->getMois() && System::today()->getJour() <= 3) {
    //            // ajout du message joyeux noel
    //            FlashMessages::addByIdMessage(TMessage::NOT_JOYEUX_NOEL);
    //        }
    //
    //        // si on est en ete
    //        if (!System::getCurrentHost()->isInFusion() && (7 === System::today()->getMois() || 8 === System::today()->getMois())) {
    //            // ajout du message joyeux noel
    //            FlashMessages::addByIdMessage(TMessage::NOT_SUMMER);
    //        }
    //
    //        // récupération du coefficient de reduction des marges lié aux dates
    //        $margeCoefficient = TMargeCoefficient::coefficientByHostAndTypeNow(null, true, false);
    //
    //        // si on a une reduction de marge temporaire
    //        if (!$margeCoefficient->isDefault()) {
    //            // on récupére le message
    //            $message = TMessage::findById(TMessage::NOT_REDUCTION_MARGE_DATE);
    //
    //            // on change les variables
    //            $message->setMesText(Template::replaceVariableFrom($message->getMesText(), ['dateMin' => $margeCoefficient->getMarCoeStartDateHeure()->format(DateHeure::DATEFR), 'dateMax' => $margeCoefficient->getMarCoeEndDateHeure()->format(DateHeure::DATEFR), 'description' => $margeCoefficient->getMarCoeDescription(), 'produits' => $margeCoefficient->productNameForDisplay()]));
    //
    //            // on affiche le message
    //            FlashMessages::addByMessage($message);
    //        }
    //    }
}
