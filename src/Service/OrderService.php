<?php

namespace App\Service;

use App\Entity\Order;
use App\Helper\FlashMessages;
use App\Service\Provider\BaseProvider;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BaseProvider $baseProvider
    ) {
    }

    /**
     * récupére le login admin si on en a pas
     * @param string $loginUser le login ou une chaine vide si il faut le récupéré
     * @return string le login
     */
    private function _recupAdminLogin(string $loginUser): string
    {
        // si aucun uilisateur n'a ete specifie et qu'on a un utilisateur connecté
        if($loginUser == '' && System::getUserConnected())
        {
            // on utilise l'utilisateur qui est connecte
            $loginUser = System::getUserConnected()->getUsLogin();
        }

        return $loginUser;
    }
    /**
     * objet Prix du montant restant à payer pôur cette commande
     * @param bool $notNull =FALSE mettre TRUE transformera tous les prix inférieur ou égale à 1 centimes (donc tous les prix négatif) en un prix à 0
     * @return Prix
     */
    public function prixRestantAPayer(Order $order,$notNull = FALSE)
    {
        // on commence par récupérer le prix de notre commande (FC déduit)
        $prixRestantAPayer = clone $order->getPrix();

        // pour chaque réglement de la commande
        //TODO create entity ReglementClient
        foreach($order->getReglementClient() AS $reglement)
        {
            // on récupére le prix du réglement
            $prixReglement = $reglement->prixPourCommande($this);

            // on retire le prix du reglement à la commande
            $prixRestantAPayer->soustractPrix($prixReglement);
        }

        // si on ne veux pas de prix null et que notre prix est inférieur ou égale à 1 centimes
        if($notNull && $prixRestantAPayer->getMontant() <= 0.01)
        {
            // on met le prix à 0
            $prixRestantAPayer->setMontant(0);
        }

        return $prixRestantAPayer;
    }
    /**
     * indique le montant restant à payer aprés avoir retirer les avoir lié à cette commande
     * @param bool $abs =FALSE mettre TRUE si on veux la valeur absolu donc toujours positive
     */
    public function priceToPayAfterCreditNote(Order $order, bool $abs = FALSE): Prix
    {
        // on récupére le prix à payer hors avoir
        //TODO create entity Prix
        $return = $this->prixRestantAPayer($order);

        // on récupére un eventuel avoir lié à notre commande
        //TODO create class Avoir
        $creditNote = Avoir::findByOrderId($this->getOrdersId());

        // si on a un avoir
        if($creditNote != null)
        {
            // on retire le montant de l'avoir à notre prix
            $return->soustractPrix($creditNote->getPrix());
        }

        // si on a un prix négatif et qu'on veux une valeur absolu
        if($abs && $return->getMontant() < 0)
        {
            // on inverse le prix
            $return->setMontant($return->getMontant() * -1);
        }

        return $return;
    }

    /**
     * indique si cette commande est intégralement payé
     * @return bool true si la commande est intégralement payé
     */
    public function isFullPaid(Order $order): bool
    {
        // les commandes trop ancienne n'ayant pas de réglement
        if($order->getOrdersId() < 37000)
        {
            // on les considére comme réglé
            return true;
        }

        // si on a un reste à payer inférieur ou égale à 1 centime. On valide les commandes avec 1 centime à payer restant à cause de soucis d'arrondi
        if($order->priceToPayAfterCreditNote()->getMontant() <= 0.01)
        {
            // commande payé
            return true;
        }

        // commande non payé
        return false;
    }
    /**
     * Renvoi les commande fournisseur lié à notre objet dont le fournisseur est actif
     * @return TAOrderSupplierOrder[]
     */
    private function _aOrderSupplierOrderWithActiveSupplier(Order $order): array
    {
        $return = array();

        // pour chaque commande fournisseur
        foreach($order->getAOrderSupplierOrder() as $key => $orderSupplierOrder)
        {
            // si le fournisseur est actif
            if($this->baseProvider->isActive($orderSupplierOrder->getSupplierOrder()->getSupplier()))
            {
                // on l'ajoute
                $return[$key] = $orderSupplierOrder;
            }
        }

        return $return;
    }

    /**
     * Procedure mettant a jour le statut de la commande et creant un historique
     * @param int $status Nouveau statut de la commande
     * @param string $comments Message a ajouter dans l'historique
     * @param int $modeEnvoiDuMail =ordersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI mode d'envoi du mail voir les constante ordersStatusHistory::TYPE_ENVOI_MAIL_...
     * @param string $sujetMail Sujet du mail
     * @param string $mailEnvoye Corp du mail
     * @param string $login Nom de l'utilisateur ou une chaine vide pour prendre l'utilisateur connecte a l'admin
     * @param string $numCdeFournisseur Numero de commande fournisseur
     * @param string $idDossier Id du dossier de fichier client ou '' pour ne pas faire de changement
     * @param int|null $typeFichier null pas de changement, 0 : ancienne version, 1 : fichier client, 2 : designer, 3 : choix non effectue
     * @param int $importantComment =0 mettre 1 si le commentaire est important
     * @param array $pj =array() un tableau pour les piéces jointes
     */
    //TODO Review in this function
    public function updateStatus(Order $order,int $status, string $comments = 'Mise &agrave jour du statut', int $modeEnvoiDuMail = OrdersStatusHistory::TYPE_ENVOI_MAIL_PAS_D_ENVOI, string $sujetMail = '', string $mailEnvoye = '', string $login = '', string $numCdeFournisseur = '', string $idDossier = '', int $typeFichier = null, int $importantComment = 0, array $pj = array()): void
    {
        // on récupére le login de l'admin connecte si besoin
        $loginUser = $this->_recupAdminLogin($login);

        // date actuelle
        $dateHeureNow = new \DateTimeImmutable();

        // si on doit envoyé le mail automatiquement
        //TODO create entity OrderStatusHistory
        if($modeEnvoiDuMail == OrdersStatusHistory::TYPE_ENVOI_MAIL_MAIL_DU_STATUT)
        {
            // on commence par sauvegarder la langue par défaut actuel
            // TODO Create class DbTable
            $langueDefaut = DbTable::getLangDefaut();

            // on change la langue par défaut pour prendre celle du site de la client afin de localiser le mail
            DbTable::setLangDefaut($order->getCustomer()->getSiteHost()->getCodeLangue());

            // on récupére le statut que l'on veux changer
            //TODO create entity OrderStatus
            $futurStatut = OrdersStatus::findById($status);

            // remplacement des variables contenues dans l'objet et le corps du mail
            //TODO Create Template
            $aReplaceVariable	 = Template::replaceVariableMultiple(array($futurStatut->getOrdersStatusSujet(), $futurStatut->getOrdersStatusMail()), array('order' => $this));
            $sujetMail			 = $aReplaceVariable[0];
            $mailEnvoye			 = $aReplaceVariable[1];

            // on remet la langue par défaut comme avant
            DbTable::setLangDefaut($langueDefaut);

            // on passe le type d'envoi du mail en standard
            $modeEnvoiDuMail = OrdersStatusHistory::TYPE_ENVOI_MAIL_STANDARD;
        }

        // si la commande passe en status controle fichier FR ou M ou 48/72
        if($status == OrdersStatus::STATUS_CONTORLE_FICHIER_48_72_FR || $status == OrdersStatus::STATUS_CONTORLE_FICHIER_FR || $status == OrdersStatus::STATUS_CONTORLE_FICHIER_M)
        {
            // on supprime si besoin les fichier depart fab sur stationserveur et on ajoute un message
            //TODO Create entity StationServeur
            $comments .= Stationserveur::deleteProductionFileForOrder($this);
        }

        // si un utilisateur est connecte dans l'admin
        //TODO System
        if(System::getUserConnected())
        {
            // recupere le produit lie a la commande
            $aOrdersProducts = array_values($order->getProducts());
            // on ajoute les nombres de points réalisé par rapport aux objectifs si on en a
            if(count($aOrdersProducts) > 0)
            {
                //TODO TUserObjectPoint
                TUserObjectifPoint::gainCommandeGraphiste($order->getOrdersStatus(), $status, $order->getOrdersId(), System::getUserConnected()->getIdUser(), $aOrdersProducts[0]->getProductsName());
            }
        }

        // si la commande doit passer en statut 7-EN FABRICATION et qu'elle est "non-réglé"
        if($status == OrdersStatus::STATUS_FABRICATION && !$this->isFullPaid($order))
        {
            // on change le statut pour 7.1 V.A EN FABRICATION
            $status		 = OrdersStatus::STATUS_FABRICATION_VA;
            // ajout d'un message
            $comments	 .= '<br />Commande passée automatiquement en statut 7.1 VA en Fab &agrave; la place de "en fabrication"';
        }

        // si la commande doit passer en statut LIVRAISON et qu'elle est "non-réglé"
        if($status == OrdersStatus::STATUS_EXPEDITION && !$this->isFullPaid($order))
        {
            // on change le statut pour 7.1 V.A EN FABRICATION
            $status		 = OrdersStatus::STATUS_EXPEDITION_VA;
            // ajout d'un message
            $comments	 .= '<br />Commande passée automatiquement en statut VA en Expedition &agrave; la place de "en livraison"';
        }

        // si la commande doit passer en statut LIVRE ou RECLA TRAITE ou ERREUR DE LIVRAISON et qu'elle est "non-réglé"
        if(($status == OrdersStatus::STATUS_LIVRE || $status == OrdersStatus::STATUS_RECLA_TRAITE || $status == OrdersStatus::STATUS_LIVRAISON_ERREUR) && !$this->isFullPaid())
        {
            // on change le statut pour 7.1 V.A EN FABRICATION
            $status		 = OrdersStatus::STATUS_LIVRE_VA;
            // ajout d'un message
            $comments	 .= '<br />Commande passée automatiquement en statut VA en Livré &agrave; la place de "livre" ou "recla traité"';
        }

        // si la commande doit passer en statut SUPPRESSION et qu'elle posséde un réglement
        if($status == OrdersStatus::STATUS_SUPPRESSION && count($this->getReglementClient()) > 0)
        {
            // on la passera en statut annulation à la place
            $status = OrdersStatus::STATUS_ANNULATION;

            // message d'erreur
            $erreur		 = 'Commande passée en ANNULATION au lieu de SUPPRESSION car elle posséde un réglement.';
            $comments	 .= '<br />' . $erreur;
            FlashMessages::add('Error', $erreur);
        }

        // si la commande doit passer en statut ANNULATION et qu'elle ne posséde aucun réglement
        if($status == OrdersStatus::STATUS_ANNULATION && count($this->getReglementClient()) < 1)
        {
            // on la passera en statut SUPPRESSION à la place
            $status = OrdersStatus::STATUS_SUPPRESSION;

            // message d'erreur
            $erreur		 = 'Commande passée en SUPPRESSION au lieu de ANNULATION car elle ne posséde pas de réglement.';
            $comments	 .= '<br />' . $erreur;
            FlashMessages::add('Error', $erreur);
        }

        // si la commande passe en depart fab a
        if($status == OrdersStatus::STATUS_DEPART_FAB_A)
        {
            // création d'un objet fournisseur de la selection
            $fournisseur = $this->baseProvider->findByIdWithChildObject($this->getSelectionFournisseur()->getIdFour());

            // on met a jour la date de fabrication dans la selection
            $this->getSelectionFournisseur()->setDateFournisseur($dateHeureNow->format(DateHeure::DATETIMEMYSQL))
                ->save();

            // si le fournisseur de la selection ne devrait pas avoir de selection (ou si on a pas de selection)
            if($fournisseur->getOrdreSelection() <= 0)
            {
                // récupération de l'erreur
                $erreur = TMessage::findById(TMessage::ERR_ADM_BAD_SUPPLIER_SELECT);

                // on change le statut
                $status = OrdersStatus::STATUS_DEPART_FAB_RETOUR;

                // on ajoute l'erreur dans les commentaire et en flash message
                $comments .= '<br />' . $erreur->getMesText();
                FlashMessages::add('Error', $erreur->getMesText());
            }
            // si le fichier n'est pas disponible et qu'on arrive pas à le copier
            elseif(!$fournisseur->fichierDisponible($this->getOrdersId()) && !$fournisseur->copyProductionFileToSupplierFtp($this))
            {
                // on change le statut (inutile de mettre un message il est déjà mis par copyProductionFileToSupplierFtp();
                $status = OrdersStatus::STATUS_DEPART_FAB_RETOUR;

                // ajout de commentaire
                $comments .= '<br />Modification automatique de statut à la place de DEPART FAB A car le fichier est indisponible.';
            }
        }

        // si la commande passe en depart fab auto et qu'on a une seul commande fournisseur lié
        if(($status == OrdersStatus::STATUS_DEPART_FAB_AUTO || $status == OrdersStatus::STATUS_DEPART_FAB_A) && count($this->_aOrderSupplierOrderWithActiveSupplier($order)) == 1)
        {
            // pour chaque commande fournisseur lié
            foreach($this->_aOrderSupplierOrderWithActivSupplier() as $orderSupplierOrder)
            {
                // passage de la commande fournisseur en lancement auto
                $orderSupplierOrder->getSupplierOrder()->setIdSupplierOrderStatus(TSupplierOrderStatus::ID_STATUS_AUTO_LAUNCH)
                    ->save();
            }
        }

        // mise à jour du statut
        $this->setOrdersStatus($status);

        // mise à jour de la date de derniere modification
        $this->setLastModified($dateHeureNow->format(DateHeure::DATETIMEMYSQL));

        // si la commande n'est pas facture et qu'on veut la passer en statut 7 en fab ou en statut 6.3 depart fab auto
        if($this->getStatusFact() <> 1 && ($status == OrdersStatus::STATUS_FABRICATION || $status == OrdersStatus::STATUS_DEPART_FAB_AUTO || $status == OrdersStatus::STATUS_FABRICATION_VA))
        {
            // on passe la commande en statut facture
            $this->generateInvoice();
            $comments .= '<br />Facture finale générée';
        }

        // si on doit mettre a jour l'id de dossier
        if($idDossier <> '')
        {
            $this->setIdDossier($idDossier);
            $comments .= '<br />Version de fichier modifiée pour : ' . $idDossier;
        }

        // si on doit mettre a jour le type de fichier
        if($typeFichier <> null)
        {
            $this->setTypeFichier($typeFichier);
        }

        /**
         *  si la commande passe en livré
         */
        if($status == OrdersStatus::STATUS_LIVRE)
        {
            // on gére les mouvement de crédits et on ajoute les éventuel commentaire
            $comments .= TCreditMouvement::orderDelivered($this);
        }

        // sauvegarde de notre objet pour appliquer
        $this->save();

        // ajout d'un message dans l'historique
        $this->addHistory($comments, $status, $modeEnvoiDuMail, $sujetMail, $mailEnvoye, $loginUser, $numCdeFournisseur, $importantComment, $pj);

        // suivant le status on va mettre a jour la note du client.
        // une fois qu'une note a ete maj pour cette commande elle ne sera plus jamais mise a jour
        switch($status)
        {
            case OrdersStatus::STATUS_ERREUR_FICHIER :
                $this->updateNote(-5);
                break;
            case OrdersStatus::STATUS_FABRICATION :
            case OrdersStatus::STATUS_FABRICATION_VA :
            case OrdersStatus::STATUS_LIVRE :
                $this->updateNote(10);
                break;
            default:
                break;
        }
    }
}