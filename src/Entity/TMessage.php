<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TMessageRepository::class)]
class TMessage
{
    /**
     * constante utilisé pour les paramétre des messages.
     */
    public const PARAM_ERR_FIELD = 1;
    public const PARAM_ERR_MIN = 2;
    public const PARAM_ERR_MAX = 3;

    /**
     * id pour une erreur inconnu.
     */
    public const ERR_INCONNUE = 0;

    /**
     * id pour une erreur inconnu.
     */
    public const ERR_UNKNOWN = 0;

    /**
     * code de l'erreur pour un champ invalide.
     */
    public const ERR_FIELD_INVALID = 1;

    /**
     * code de l'erreur pour un champ qui doit être compris entre 2 valeurs.
     */
    public const ERR_FIELD_SIZEBETWEEN = 2;

    /**
     * code de l'erreur pour un champ qui doit avoir un format de date.
     */
    public const ERR_FIELD_FORMAT = 3;

    /**
     * code de l'erreur pour 2 champs différent.
     */
    public const ERR_FIELD_DIFFERENT = 4;

    /**
     * code de l'erreur pour un champ déjà utilisé.
     */
    public const ERR_FIELD_USED = 5;

    /**
     * code de l'erreur pour valider les CGV.
     */
    public const ERR_FIELD_CGV = 6;

    /**
     * code de l'erreur pour le code franchiseur.
     */
    public const ERR_FIELD_FRANCHISEUR = 8;

    /**
     * code de l'erreur pour un champ obligatoire.
     */
    public const ERR_FIELD_MANDATORY = 9;

    /**
     * code de l'erreur pour un champ trop petit.
     */
    public const ERR_FIELD_SIZEMIN = 10;

    /**
     * code de l'erreur pour un champ trop grand.
     */
    public const ERR_FIELD_SIZEMAX = 11;

    /**
     * code de l'erreur pour un numéro de tva invalide.
     */
    public const ERR_FIELD_TVAINTRA = 12;

    /**
     * code de l'erreur pour une mise à jour de note de client trop recente.
     */
    public const ERR_CUSTOMER_NOTE_SAVE = 13;

    /**
     * code de l'erreur pour une modification erroné du tableau des notes.
     */
    public const ERR_CUSTOMER_NOTE_LIST_SAVE = 14;

    /**
     * code de l'erreur pour un code revendeur invalide.
     */
    public const ERR_FIELD_CODE_REVENDEUR = 15;

    /**
     * code de l'erreur pour un nom de fichier incorrect.
     */
    public const ERR_FILE_INCORRECT_NAME = 16;

    /**
     * code de l'erreur pour un type de fichier incorrect.
     */
    public const ERR_FILE_INCORRECT_TYPE = 17;

    /**
     * code de l'erreur pour un fichier introuvable.
     */
    public const ERR_FILE_NOT_FOUND = 18;

    /**
     * code de l'erreur pour un panier expiré.
     */
    public const ERR_PANIER_EXPIRE = 19;

    /**
     * code de l'erreur pour un panier vide.
     */
    public const ERR_PANIER_VIDE = 20;

    /**
     * code de l'erreur pour une adresse manquante.
     */
    public const ERR_ADRESS_MISSING = 22;

    /**
     * code de l'erreur pour un moyen de paiement manquant.
     */
    public const ERR_PAYMENT_MISSING = 23;

    /**
     * code de l'erreur pour une maquette UD qui n'existe pas.
     */
    public const ERR_UD_NOT_EXIST = 24;

    /**
     * code de l'erreur pour une maquette UD qui n'appartient pas au client.
     */
    public const ERR_UD_NOT_CUSTOMER = 25;

    /**
     * le fournisseur de la selection ne devrait pas avoir de selection.
     */
    public const ERR_ADM_BAD_SUPPLIER_SELECT = 26;

    /**
     * code de l'erreur pour un code promo déjà utilisé.
     */
    public const ERR_CODE_PROMO_DEJA_UTILISE = 27;

    /**
     * code du message pour un code promo utilisé avec succés.
     */
    public const SUC_CODE_PROMO = 28;

    /**
     * code de l'erreur pour un code promo déjà invalide.
     */
    public const ERR_CODE_PROMO_INVALIDE = 29;

    /**
     * code de la notification pour les fetes de noel.
     */
    public const NOT_JOYEUX_NOEL = 30;

    /**
     * code de l'erreur pour une modification de page non autorisé.
     */
    public const ERR_UNAUTHORIZED_PAGE_EDIT = 31;

    /**
     * code du message de modification ok.
     */
    public const SUC_MODIFICATION_OK = 32;

    /**
     * code du message d'alerte concernant un texte 2 manquant.
     */
    public const ALE_TEXTE2_MISSING = 33;

    /**
     * code de l'erreur pour des paramétres manquant.
     */
    public const ERR_MISSING_PARAM = 34;

    /**
     * code de l'erreur pour une page qui n'existe pas.
     */
    public const ERR_PAGE_NOT_FOUND = 35;

    /**
     * code de l'erreur pour un client qui n'existe pas.
     */
    public const ERR_CUSTOMER_NOT_FOUND = 36;

    /**
     * code de l'erreur pour des mots de passe différent.
     */
    public const ERR_PASSWORD_DIFFERENT = 37;

    /**
     * code de la notification quand le mot de passe est identique a l'ancien.
     */
    public const NOT_PASSWORD_SAME_AS_OLD = 38;

    /**
     * code du succés pour un mot de passe changé.
     */
    public const SUC_PASSWORD_CHANGED = 39;

    /**
     * code du succés pour une arrivée enregistré.
     */
    public const SUC_POINTEUSE_ARRIVEE_OK = 40;

    /**
     * code du succés pour un départ enregistré.
     */
    public const SUC_POINTEUSE_DEPART_OK = 41;

    /**
     * code de l'erreur pour une personne non connecté.
     */
    public const ERR_DISCONNECTED = 42;

    /**
     * code de l'erreur pour un utilisateur qui n'existe pas.
     */
    public const ERR_USER_NOT_FOUND = 43;

    /**
     * code de l'alerte pour un utilisateur qui n'a pas cliquer sur j'arrive.
     */
    public const ALE_POINTEUSE_NOT_CONNECTED = 44;

    /**
     * code de l'erreur pour un produit n'existant pas.
     */
    public const ERR_PRODUCT_NOT_FOUND = 45;

    /**
     * code du message quand on a supprimé un produit.
     */
    public const SUC_PRODUCT_DELETED = 46;

    /**
     * code de l'erreur pour une commande n'existant pas.
     */
    public const ERR_ORDER_NOT_FOUND = 47;

    /**
     * code de l'erreur pour un panier n'existant pas.
     */
    public const ERR_CART_NOT_FOUND = 48;

    /**
     * code de l'erreur pour une absence de commande et de panier.
     */
    public const ERR_NO_ORDER_NO_CART = 49;

    /**
     * code de l'erreur pour une erreur d'initialissation CB.
     */
    public const ERR_CB_INITIALISATION = 50;

    /**
     * code de l'erreur quand un réglement est lié à d'autres commandes.
     */
    public const ERR_REGLEMENT_OTHER_ORDER = 51;

    /**
     * code de l'erreur pour une commande déjà payé.
     */
    public const ERR_ORDER_ALREADY_PAID = 52;

    /**
     * code du message en cas d'enregistrment d'un message avec succes.
     */
    public const SUC_MESSAGE_SAVE = 53;

    /**
     * code de l'erreur pour un message qui n'existe pas.
     */
    public const ERR_MESSAGE_NOT_FOUND = 54;

    /**
     * code du message en cas d'enregistrment d'une liste de messages avec succes.
     */
    public const SUC_MESSAGE_LIST_SAVE = 55;

    /**
     * code de l'erreur lors de l'enregistrement d'une liste de messages.
     */
    public const ERR_MESSAGE_LIST_SAVE = 56;

    /**
     * code du message quand on a supprimé un produit.
     */
    public const SUC_PAGE_DELETED = 57;

    /**
     *  code du message quand on a vidé le cache.
     */
    public const SUC_CACHE_PURGED = 58;

    /**
     * code de l'erreur lors du vidage du cache.
     */
    public const ERR_CACHE_PURGE = 59;

    /**
     * code de l'erreur pur un cache introuvable.
     */
    public const ERR_CACHE_MISSING = 60;

    /**
     * code de l'erreur quand on a mis un mauvais statut par rapport au BAT.
     */
    public const ERR_BAT_WRONG_STATUS = 61;

    /**
     * code de l'erreur quand il manque le BAT.
     */
    public const ERR_BAT_MISSING = 62;

    /**
     * code de l'erreur quand le devis n'existe pas.
     */
    public const ERR_DEVIS_NOT_FOUND = 63;

    /**
     * code de l'erreur du message non envoye.
     */
    public const ERR_MESSAGE_NOT_SENT = 64;

    /**
     * code du message envoye.
     */
    public const SUC_MESSAGE_SENT = 65;

    /**
     * code du message pour une adresse ajouté.
     */
    public const SUC_ADDRESS_ADDED = 66;

    /**
     * code du message pour une adresse modifié.
     */
    public const SUC_ADDRESS_UPDATED = 67;

    /**
     * code de l'erreur pour une adresse qui n'appartient pas au client.
     */
    public const ERR_ADDRESS_OTHER_CUSTOMER = 68;

    /**
     * code du message pour une adresse par défaut modifié.
     */
    public const SUC_ADDRESS_DEFAULT_UPDATED = 69;

    /**
     * code de l'erreur pour une adresse par défaut qu'on a pas réussi à modifié.
     */
    public const ERR_ADDRESS_DEFAULT_UPDATE = 70;

    /**
     * code de l'erreur pour une adresse qui est dans une commande en cours.
     */
    public const ERR_ADDRESS_DELETE_IN_ORDER = 71;

    /**
     * code du message pour une adresse supprimé.
     */
    public const SUC_ADDRESS_DELETED = 72;

    /**
     * code de l'erreur pour une suppression de l'adresse principale.
     */
    public const ERR_ADDRESS_DELETE_MAIN = 73;

    /**
     * code de l'erreur pour une erreur de suppression.
     */
    public const ERR_ADDRESS_DELETE = 74;

    /**
     * code du message quand l'avis a était envoyé.
     */
    public const SUC_AVIS_SEND = 75;

    /**
     * code de l'erreur quand un mail n'existe pas.
     */
    public const ERR_MAIL_NOT_FOUND = 76;

    /**
     * code du message quand le mail a était envoyé.
     */
    public const SUC_MAIL_SENT = 77;

    /**
     * code du message quand une ise à jour à réussi.
     */
    public const SUC_UPDATE_OK = 78;

    /**
     * code de l'erreur quand un mail n'a pas pût être envoyé.
     */
    public const ERR_MAIL_NOT_SENT = 79;

    /**
     * notification concernant les vacance d'été.
     */
    public const NOT_SUMMER = 80;

    /**
     * code du message lors du succés de génération de fichier.
     */
    public const SUC_FILE_GENERATION = 81;

    /**
     * code de l'erreur quand on arrive pas à généré un fichier.
     */
    public const ERR_FILE_GENERATION = 82;

    /**
     * code de l'erreur pour un login incorrect.
     */
    public const ERR_LOGIN_BAD = 83;

    /**
     * code du message quand on a réussi à ce connecter.
     */
    public const SUC_LOGIN_OK = 84;

    /**
     * code de l'erreur pour la suppression de facilecrédit déjà utilisé.
     */
    public const ERR_REGLEMENT_CREDIT_USED = 85;

    /**
     * code de l'erreur pour la suppression d'un chéque déjà dans une remise.
     */
    public const ERR_REGLEMENT_CHEQUE_REMISE = 86;

    /**
     * code de l'erreur pour une longeur qui doit être comprise entre 2 dimensions.
     */
    public const ERR_LENGTH_BETWEEN = 87;

    /**
     * code de l'erreur pour une longeur qui doit être comprise entre 2 dimensions.
     */
    public const ERR_WIDTH_BETWEEN = 88;

    /**
     * code de l'erreur pour un matériaux inconnu.
     */
    public const ERR_MATERIAL_NOT_FOUND = 89;

    /**
     * code de l'erreur pour un produit non disponible.
     */
    public const ERR_PRODUCT_NOT_AVAILABLE = 90;

    /**
     * code de l'erreur quand une commande à était changé par un autre utilisateur.
     */
    public const ERR_ORDER_STATUS_MODIFIED = 91;

    /**
     * code de l'erreur quand une commande n'appartient pas au client.
     */
    public const ERR_ORDER_NOT_THIS_CUSTOMER = 92;

    /**
     * code de l'erreur d'initialisation du paiement bancaire.
     */
    public const ERR_BANK_CARD_INIT = 93;

    /**
     * code du message quand on a réussi à ajouter une maquette dans les favoris.
     */
    public const SUC_UD_FAVORIS_ADD = 94;

    /**
     * code du message affiché pour conditions exceptionnel.
     */
    public const NOT_SPECIAL_CONDITION = 95;

    /**
     * code de l'erreur pour un avoir n'existant pas.
     */
    public const ERR_CREDIT_INVOICE_NOT_FOUND = 96;

    /**
     * code du message pour un avoir supprimé.
     */
    public const SUC_CREDIT_INVOICE_DELETED = 97;

    /**
     * code de l'erreur pour un mail incorrect.
     */
    public const ERR_MAIL_INCORRECT = 98;

    /**
     * code du message pour un désabonnement à la newletter.
     */
    public const SUC_UNSUBSCRIBE_NEWSLETTER = 100;

    /**
     * code du message pour un devis envoyé de l'admin avec succés.
     */
    public const SUC_DEVIS_ADM_SENT = 101;

    /**
     * code du message pour un devis en attente de validation dans l'admin.
     */
    public const NOT_DEVIS_ADM_WAITING = 102;

    /**
     * code du message quand on a supprimé une option.
     */
    public const SUC_OPTION_DELETED = 103;

    /**
     * code de l'erreur quand on ne trouve pas le fournisseur.
     */
    public const ERR_SUPPLIER_NOT_FOUND = 104;

    /**
     * code du message quand on a désactivé un cache.
     */
    public const SUC_CACHE_DESACTIVED = 105;

    /**
     * code de l'erreur quand on ne peux plus envoyé de fichier sur une commande.
     */
    public const ERR_ORDER_NOT_UPLOAD_FILE = 106;

    /**
     * code de l'erreur quand la commande n'est pas dans le bon statut.
     */
    public const ERR_ORDER_BAD_STATUS = 107;

    /**
     * code du message quand un BAT est validé.
     */
    public const SUC_BAT_VALIDE = 108;

    /**
     * code du message quand un BAT est refusé.
     */
    public const NOT_BAT_REFUSE = 109;

    /**
     * code de l'erreur quand on a pas de commentaire pour un refus de BAT.
     */
    public const ERR_BAT_REFUSE_NO_COMMENT = 110;

    /**
     * code de l'erreur quand une reduction nouveau client n'est pas valable car le SIRET est invalide.
     */
    public const ERR_CUSTOMER_SIRET_INVALID = 111;

    /**
     * code de l'erreur quand une reduction nouveau client n'est pas valable car le siret l'a déjà utilisé.
     */
    public const ERR_SIRET_ALREADY_USED = 112;

    /**
     * code de l'erreur du login incorrect.
     */
    public const ERR_LOGIN_INCORRECT = 114;

    /**
     * code du message lors du succés de suppression de fichier.
     */
    public const SUC_FILE_DELETE = 115;

    /**
     * code de l'erreur quand on arrive pas à supprimer un fichier.
     */
    public const ERR_FILE_DELETION = 116;

    /**
     * code de l'erreur quand on arrive pas à supprimer un fichier.
     */
    public const WAR_PAGE_ACCESSIBLE_ADMIN_ONLY = 117;

    /**
     * code de l'erreur quand on arrive pas à supprimer un fichier.
     */
    public const ERR_MIN_SUP_MAX = 118;

    /**
     * code de l'erreur quand on arrive pas à trouver un élément.
     */
    public const ERR_NOT_FOUND = 119;

    /**
     * code de l'erreur quand on essaye de supprimé un coefficient de reduction de marge par défaut.
     */
    public const ERR_MARGE_COEFF_DEFAULT_NOT_DELETE = 120;

    /**
     * code du message quand on a supprimé avec succés.
     */
    public const SUC_DELETE_OK = 121;

    /**
     * code du message quand on a ajouté avec succés.
     */
    public const SUC_CREATE_OK = 122;

    /**
     * code du message qunad on a une reduction de marge temporaire.
     */
    public const NOT_REDUCTION_MARGE_DATE = 123;

    /**
     * code du message qunad On supprime des Gab UD pour la free PAO (1) et que la commande n'existe pas.
     */
    public const FREE_PAO_ORDER_NO_EXIST = 124;

    /**
     * code du message qunad on a une reduction de marge nouveau client.
     */
    public const NOT_REDUCTION_MARGE_NEW_CUSTOMER = 125;

    /**
     * code de l'erreur quand on ajuste un coefficient à la valeur minimum.
     */
    public const WAR_COEFFICIENT_ADJUSTED_MINI = 126;

    /**
     * code de l'erreur quand une reduction nouveau client n'est pas valable car il a déjà des commandes.
     */
    public const ERR_CUSTOMER_WITH_ORDER = 127;

    /**
     * code de l'erreur quand un devis n'appartient pas au client.
     */
    public const ERR_DEVIS_NOT_THIS_CUSTOMER = 128;

    /**
     * code du message quand on a supprimé un mail.
     */
    public const SUC_MAIL_DELETE = 129;

    /**
     * code du message quand on a ajouté un mouvement de crédit.
     */
    public const SUC_CREDIT_MOUVEMENT_ADDED = 130;

    /**
     * code de l'erreur quand un avis est déjà validé.
     */
    public const ERR_AVIS_ALREADY_VALIDED = 131;

    /**
     * code du message quand on a ajouté un devis fournisseur à l'accés fournisseur.
     */
    public const SUC_DEVIS_ACCES_FOURNISSEUR = 132;

    /**
     * code du message quand on a téléchargé un fichier.
     */
    public const SUC_FILE_DOWNLOAD = 133;

    /**
     * code du message quand on a intégré un rapport.
     */
    public const SUC_REPORT_INTEGRATED = 134;

    /**
     * code du message quand on a enregistré une proposition.
     */
    public const SUC_PROPOSITION_SAVE = 135;

    /**
     * code de l'erreur pour une reduction déjà appliqué.
     */
    public const ERR_REDUCTION_ALREADY_DONE = 136;

    /**
     * code du message de copie réussi.
     */
    public const SUC_COPY_OK = 137;

    /**
     * code du message quand on a envoyé la demande d'échantillons.
     */
    public const SUC_SAMPLES_AUTO_MAIL = 138;

    /**
     * code de l'erreur quand les date de fin est antérieur a la date de début.
     */
    public const ERR_DATE_END_BEFORE_DATE_START = 139;

    /**
     * code de l'erreur quand une commade est déjà payé en facile crédit et qu'il faut rafraichir la page.
     */
    public const ERR_ORDER_PAID_CREDIT_REFRESH = 140;

    /**
     * code de l'erreur quand une commade est déjà payé en facile crédit et qu'il faut rafraichir la page.
     */
    public const ERR_FAST_USER_PAIEMENT_CB = 141;

    /**
     * code de l'erreur quand on n'a pas de montant à payer et qu'on demande de rafraichir la page.
     */
    public const ERR_NO_AMOUNT_REFRESH = 142;

    /**
     * code de l'erreur quand on n'a pas de montant à payer et qu'on demande de rafraichir la page.
     */
    public const ERR_PAYMENT_PARTNER_UNAVAILABLE = 143;

    /**
     * code de l'erreur quand on n'arrive pas à initialiser le paiement CB mais qu'on ne sais pas ce qui se passe.
     */
    public const ERR_PAYMENT_INIT = 144;

    /**
     * code du message quand on a réussi à recharger le panier du client.
     */
    public const SUC_CART_LOADED = 145;

    /**
     * code de l'erreur pour une facture non trouvé.
     */
    public const ERR_INVOICE_NOT_FOUND = 146;

    /**
     * code de l'erreur pour une image non supprimable car utilisé dans une maquette cliente.
     */
    public const ERR_UD_IMAGE_NOT_DELETABLE = 147;

    /**
     * code du message quand on a réussi une action.
     */
    public const SUC_ACTION = 148;

    /**
     * code de l'erreur pour un captcha incorrect.
     */
    public const ERR_CAPTCHA_BAD = 149;

    /**
     * code de l'erreur pour un captcha incorrect.
     */
    public const NOT_UD_NO_IMG_IN_USER_FILE = 150;

    /**
     * code de l'avertissement quand on a trop de remise dans un panier.
     */
    public const WAR_DISCOUNT_TOO_MANY = 151;

    /**
     * code de l'erreur pour un trasfert de fichier.
     */
    public const ERR_FILE_DOWNLOAD = 152;

    /**
     * code de l'erreur pour un siret manquant pour chorus.
     */
    public const ERR_NO_SIRET_FOR_CHORUS = 153;

    /**
     * code de l'avertissement quand on doit ajouter un produit au panier avant de bénéficier de l'offre.
     */
    public const WAR_OFFER_NEED_ITEM_IN_CART = 155;

    /**
     * code de l'erreur pour une commande non payé.
     */
    public const ERR_ORDER_NOT_PAID = 156;

    /**
     * code de la notification exceptionnel.
     */
    public const NOT_EXCEPTIONAL = 157;

    /**
     * code de l'erreur de conversion en webp.
     */
    public const ERR_WEBP_CONVERT = 158;

    /**
     * code du message de conversion en webp réussi.
     */
    public const SUC_WEBP_CONVERT = 159;

    /**
     * code du message de suppression d'un image webp.
     */
    public const SUC_WEBP_DELETE = 160;

    /**
     * code de l'erreur de format de fichier incorrect. uniquement autorisé : pdf jpg pdf.
     */
    public const ERR_BAD_FORMAT_ONLY_PDF_JPG_PNG = 161;

    /**
     * code du message de sélection sauvegarder.
     */
    public const SUC_SELECTION_SAVED = 162;

    /**
     * code du message de lockProcess qui n'existe plus.
     */
    public const NOT_LOCK_NOT_EXIST = 163;

    /**
     * code du message de lockProcess supprimé.
     */
    public const SUC_LOCK_DELETED = 164;

    /**
     * code de l'erreur pour une image non trouvé.
     */
    public const ERR_IMG_NOT_FOUND = 165;

    /**
     * code de l'erreur pour une image non trouvé.
     */
    public const ERR_ZIP_CORRUPTED = 166;

    /**
     * code de l'erreur pour une transaction trop ancienne.
     */
    public const ERR_TRANSACTION_TOO_OLD = 167;

    /**
     * code du message d'un réglement annulé.
     */
    public const SUC_PAYMENT_CANCELED = 168;

    /**
     * code de l'erreur quand on a un montant à remboursé supérieur au montant de la commande.
     */
    public const ERR_REFUND_GREATER_ORDER = 169;

    /**
     * code du message d'utilisation des crédits avec succés.
     */
    public const SUC_CREDITS_USED = 170;

    /**
     * code de l'avertissement quand on n'a rien à remboursé.
     */
    public const WAR_NOTHING_TO_REFUND = 171;

    /**
     * code de l'erreur de l'utilisation de crédit sur une commande facturée.
     */
    public const ERR_CREDITS_ON_INVOICE = 172;

    /**
     * code de l'erreur d'un solde de crédit insuffisant.
     */
    public const ERR_CREDITS_INSUFFICIENT = 173;

    /**
     * code du message quand on a ajouté un réglement.
     */
    public const SUC_PAYMENT_ADDED = 174;

    /**
     * code du l'erreur de format : uniquement jpg et png autorisé.
     */
    public const ERR_BAD_FORMAT_ONLY_JPG_PNG = 175;

    /**
     * code de l'erreur quand la session a expiré.
     */
    public const ERR_SESSION_EXPIRED = 176;

    /**
     * code de l'erreur quand la session a expiré.
     */
    public const ERR_TEMPLATE_NOT_FOUND = 177;

    /**
     * code de l'erreur pour une suppression impossible.
     */
    public const ERR_DELETE = 178;

    /**
     * code de l'erreur pour un prix qu'on n'arrive pas à récupérer.
     */
    public const ERR_PRICE_NOT_RECOVERED = 179;

    /**
     * code de l'erreur pour un prix qu'on n'arrive pas à récupérer et qu'on a désactivé le produit.
     */
    public const ERR_PRICE_NOT_RECOVERED_PRODUCT_INACTIVE = 180;

    /**
     * code du message quand on a lancé un cron.
     */
    public const SUC_CRON_LAUNCHED = 181;

    /**
     * code du message quand on a créé ou mis à jour un produit.
     */
    public const SUC_PRODUCT_CREATED_OR_UPDATED = 182;

    /**
     * code de l'erreur pour une image non valide.
     */
    public const ERR_INVALID_IMAGE = 183;

    /**
     * code du message quand on a relancé la mise à jour un produit.
     */
    public const SUC_PRODUCT_WAITING_UPDATE = 184;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $text = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $fieldLabel = null;

    #[ORM\Column(length: 255)]
    private ?string $fieldType = null;

    #[ORM\Column(length: 255)]
    private ?string $fieldValue = null;

    #[ORM\Column]
    private ?bool $fieldIsMandatory = null;

    #[ORM\Column]
    private ?int $fieldSizeMin = null;

    #[ORM\Column]
    private ?int $fieldSizeMax = null;

    #[ORM\Column]
    private ?bool $fieldHasError = null;

    #[ORM\Column(length: 255)]
    private ?string $errorField = null;

    #[ORM\Column]
    private ?int $errorCharMin = null;

    #[ORM\Column]
    private ?int $errorCharMax = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getFieldLabel(): ?string
    {
        return $this->fieldLabel;
    }

    public function setFieldLabel(string $fieldLabel): static
    {
        $this->fieldLabel = $fieldLabel;

        return $this;
    }

    public function getFieldType(): ?string
    {
        return $this->fieldType;
    }

    public function setFieldType(string $fieldType): static
    {
        $this->fieldType = $fieldType;

        return $this;
    }

    public function getFieldValue(): ?string
    {
        return $this->fieldValue;
    }

    public function setFieldValue(string $fieldValue): static
    {
        $this->fieldValue = $fieldValue;

        return $this;
    }

    public function isFieldIsMandatory(): ?bool
    {
        return $this->fieldIsMandatory;
    }

    public function setFieldIsMandatory(bool $fieldIsMandatory): static
    {
        $this->fieldIsMandatory = $fieldIsMandatory;

        return $this;
    }

    public function getFieldSizeMin(): ?int
    {
        return $this->fieldSizeMin;
    }

    public function setFieldSizeMin(int $fieldSizeMin): static
    {
        $this->fieldSizeMin = $fieldSizeMin;

        return $this;
    }

    public function isFieldSizeMax(): ?int
    {
        return $this->fieldSizeMax;
    }

    public function setFieldSizeMax(int $fieldSizeMax): static
    {
        $this->fieldSizeMax = $fieldSizeMax;

        return $this;
    }

    public function isFieldHasError(): ?bool
    {
        return $this->fieldHasError;
    }

    public function setFieldHasError(bool $fieldHasError): static
    {
        $this->fieldHasError = $fieldHasError;

        return $this;
    }

    public function getErrorField(): ?string
    {
        return $this->errorField;
    }

    public function setErrorField(string $errorField): static
    {
        $this->errorField = $errorField;

        return $this;
    }

    public function getErrorCharMin(): ?int
    {
        return $this->errorCharMin;
    }

    public function setErrorCharMin(int $errorCharMin): static
    {
        $this->errorCharMin = $errorCharMin;

        return $this;
    }

    public function getErrorCharMax(): ?int
    {
        return $this->errorCharMax;
    }

    public function setErrorCharMax(int $errorCharMax): static
    {
        $this->errorCharMax = $errorCharMax;

        return $this;
    }

    // TODO Repository
    /*
     * Recupere tous les messages dans une langue.
     * @param string $idCodePays Code du pays pour la langue
     * @retrun NULL|TMessage[] Liste des messages correpondant a la langue
     */
    //    public static function findAllByIdCodePays($idCodePays)
    //    {
    //        // fixe le code du pays
    //        self::setLangDefaut($idCodePays);
    //
    //        return self::findAll();
    //    }

    // TODO Service
    /*
     * Genere un message d'erreur selon le champ a controler
     * @param int $idError
     */
    //    private function _makeError($idError)
    //    {
    //        $tError = self::findById(array($idError));
    //        $this->setErrField($this->getFieldLabel());
    //        $this->setErrCharMin($this->getFieldSizeMin());
    //        $this->setErrCharMax($this->getFieldSizeMax());
    //        $this->setIdMessage($tError->getIdMessage());
    //        $this->setMesText($tError->getMesText());
    //        $this->setFieldHasError(TRUE);
    //    }
    /*
     * Charge un champ avec les criteres a controler
     * @param string $fieldLabel		Libelle du champ
     * @param string $fieldType			Type de champ
     * @param string $fieldValue		Valeur du champ
     * @param bool $fieldIsMandatory	Le champ est obligatoire ou pas
     * @param int $fieldSizeMin			Taille minimale du champ
     * @param int $fieldSizeMax			Taille maximale du champ
     */
    //    private static function _load($fieldLabel = '', $fieldType = '', $fieldValue = '', $fieldIsMandatory = FALSE, $fieldSizeMin = 0, $fieldSizeMax = 0)
    //    {
    //        $tError = new TMessage();
    //        $tError->setFieldLabel($fieldLabel);
    //        $tError->setFieldType($fieldType);
    //        $tError->setFieldValue($fieldValue);
    //        $tError->setFieldIsMandatory($fieldIsMandatory);
    //        $tError->setFieldSizeMin($fieldSizeMin);
    //        $tError->setFieldSizeMax($fieldSizeMax);
    //
    //        return $tError;
    //    }
    /*
     * Verifie un code postal par regex.
     */
    //    private function _checkCodePostal()
    //    {
    //        if (!Tools::isCodePostalValide($this->getFieldValue())) {
    //            $this->_makeError(self::ERR_FIELD_INVALID);
    //        }
    //    }

    /*
     * Verifie une date au format FR par regex.
     */
    //    private function _checkDateFr()
    //    {
    //        if (!DateHeure::isDateFr($this->getFieldValue())) {
    //            $this->_makeError(self::ERR_FIELD_FORMAT);
    //        }
    //    }

    /*
     * Verifie une adresse email par regex.
     */
    //    private function _checkEmail()
    //    {
    //        if (!Tools::isAdresseMailValide($this->getFieldValue())) {
    //            $this->_makeError(self::ERR_FIELD_INVALID);
    //        }
    //    }

    /*
     * Verifie un nombre decimal par regex.
     */
    //    private function _checkFloat()
    //    {
    //        if (!preg_match('#^[\+\-]?[0-9]+[\.\,]?[0-9]*$#', $this->getFieldValue())) {
    //            $this->_makeError(self::ERR_FIELD_INVALID);
    //        }
    //    }

    /*
     * Verifie un nombre entier par regex.
     */
    //    private function _checkInt()
    //    {
    //        if (!preg_match('#^[\+\-]?[0-9]+$#', $this->getFieldValue())) {
    //            $this->_makeError(self::ERR_FIELD_INVALID);
    //        }
    //    }

    /*
     * Verifie un numero de telephone par regex.
     */
    //    private function _checkPhoneNumber()
    //    {
    //        if (!Tools::isPhoneNumberValide($this->getFieldValue())) {
    //            $this->_makeError(self::ERR_FIELD_INVALID);
    //        }
    //    }

    /*
     * Verifie un numero de telephone portable suivant le pays.
     */
    //    private function _checkTelPortCountry()
    //    {
    //        $countries = Countries::findByCode(System::getCurrentHost()->getPaysCode());
    //
    //        $isTelPortValide = $countries->isTelPortValide($this->getFieldValue());
    //        if (false === $isTelPortValide) {
    //            $this->_makeError(self::ERR_FIELD_INVALID);
    //        }
    //    }

    /*
     * Verifie un texte seul par regex (sans possibilite de mettre des chiffres).
     */
    //    private function _checkTextOnly()
    //    {
    //        if (!preg_match('#^[^0-9]+$#', $this->getFieldValue())) {
    //            $this->_makeError(self::ERR_FIELD_INVALID);
    //        }
    //    }

    /*
     * Verifie le numero de TVA intracom.
     */
    //    private function _checkTvaIntracom()
    //    {
    //        $countries = Countries::findByCode(System::getCurrentHost()->getPaysCode());
    //
    //        $isTvaIntracomValide = $countries->isTvaIntracomValide($this->getFieldValue());
    //        if (false === $isTvaIntracomValide) {
    //            $this->_makeError(self::ERR_FIELD_INVALID);
    //        }
    //    }

    /*
     * Verifie si le champ est obligatoire.
     */
    //    private function _checkFieldIsMandatory()
    //    {
    //        if (true === $this->getFieldIsMandatory() && strlen($this->getFieldValue()) <= 0) {
    //            $this->_makeError(self::ERR_FIELD_MANDATORY);
    //        }
    //    }

    /*
     * Verifie la taille d'un champ.
     * @return string Le message d'avertissement en cas d'erreur
     */
    //    private function _checkFieldSize()
    //    {
    //        if ($this->getFieldSizeMin() > 0) {
    //            if (mb_strlen($this->getFieldValue()) < $this->getFieldSizeMin()) {
    //                $this->_makeError(self::ERR_FIELD_SIZEMIN);
    //            }
    //        }
    //        if ($this->getFieldSizeMax() > 0) {
    //            if (mb_strlen($this->getFieldValue()) > $this->getFieldSizeMax()) {
    //                $this->_makeError(self::ERR_FIELD_SIZEMAX);
    //            }
    //        }
    //    }

    /*
     * Verifie un champ selon son type par regex
     */
    //    private function _checkFieldType()
    //    {
    //        switch($this->getFieldType())
    //        {
    //            case 'cp' : // verification du code postal
    //                $this->_checkCodePostal();
    //                break;
    //
    //            case 'datefr' : // verification date FR
    //                $this->_checkDateFr();
    //                break;
    //
    //            case 'email' : // verification email
    //                $this->_checkEmail();
    //                break;
    //
    //            case 'float' : // verification un nombre decimal
    //                $this->_checkFloat();
    //                break;
    //
    //            case 'int' : // verification un nombre entier
    //                $this->_checkInt();
    //                break;
    //
    //            case 'tel' : // verification numero de telephone
    //                $this->_checkPhoneNumber();
    //                break;
    //
    //            case 'tel_port' : // verification numero de telephone portable
    //                $this->_checkTelPortCountry();
    //                break;
    //
    //            case 'textonly' : // verification d'un texte sans chiffres
    //                $this->_checkTextOnly();
    //                break;
    //
    //            case 'tva_intracom' : // verification de la TVA intracom
    //                $this->_checkTvaIntracom();
    //                break;
    //
    //            default : break;
    //        }
    //    }

    /*
     * met à jout les paramétres du message
     * @param type $aParam tableau des paramétre avec en clef l'id correspondant au type de champ et en paramétre la valeur
     */
    //    public function updateParamByArray($aParam)
    //    {
    //        // pour chaque param
    //        foreach($aParam AS $idParam => $param)
    //        {
    //            // suivant le type de paramétre on met à jour le champ qui correspond
    //            switch($idParam)
    //            {
    //                case TMessage::PARAM_ERR_FIELD:
    //                    $this->setErrField($param);
    //                    break;
    //                case TMessage::PARAM_ERR_MIN:
    //                    $this->setErrCharMin($param);
    //                    break;
    //                case TMessage::PARAM_ERR_MAX:
    //                    $this->setErrCharMax($param);
    //                    break;
    //
    //                default:
    //                    break;
    //            }
    //        }
    //    }

    /*
     * Verifie la valeur d'un champ a partir d'un type, taille min et max
     * @param string $fieldLabel		Libelle du champ
     * @param string $fieldType			Type de champ
     * @param string $fieldValue		Valeur du champ
     * @param bool $fieldIsMandatory	Le champ est obligatoire ou pas
     * @param int $fieldSizeMin			Taille minimale du champ
     * @param int $fieldSizeMax			Taille maximale du champ
     * @return string Le message d'avertissement en cas d'erreur
     */
    //    public static function checkFieldFrom($fieldLabel, $fieldType, $fieldValue, $fieldIsMandatory = FALSE, $fieldSizeMin = 0, $fieldSizeMax = 0)
    //    {
    //        $tError = self::_load($fieldLabel, $fieldType, $fieldValue, $fieldIsMandatory, $fieldSizeMin, $fieldSizeMax);
    //
    //        if($fieldIsMandatory || $fieldValue != '')
    //        {
    //            $tError->_checkFieldIsMandatory();
    //
    //            if(!$tError->getFieldHasError())
    //            { // pas d'erreur dans l'obligation d'avoir une valeur pour le champ
    //                $tError->_checkFieldSize();
    //            }
    //
    //            if(!$tError->getFieldHasError())
    //            { // pas d'erreur de taille du champ => verification sur la regex du champ
    //                $tError->_checkFieldType();
    //            }
    //        }
    //
    //        return $tError;
    //    }

    /*
     * Verifie une liste de champs avec tous les criteres de controle
     * @param array $aField		Liste des champs a verifier
     * @return array Liste des objets TMessage generes en cas d'erreurs
     */
    //    public static function checkFieldList($aField = array())
    //    {
    //        $aTMessage = array();
    //        foreach($aField as $fieldId => $field)
    //        {
    //            $tError = self::checkFieldFrom($field['label'], $field['type'], $field['value'], $field['isMandatory'], $field['sizeMin'], $field['sizeMax']);
    //            if($tError->getFieldHasError())
    //            {
    //                $aTMessage[$fieldId] = $tError;
    //            }
    //        }
    //        return $aTMessage;
    //    }

    /*
     * Retourne tous les types de message
     * @return array Liste des types de message
     */
    //    public static function allMesType()
    //    {
    //        return array('Notice', 'Succes', 'Alert', 'Erreur');
    //    }

    /*
     * renvoi le code html pour afficher le message
     * @return string
     */
    //    public function html()
    //    {
    //        // on renvoi un template avec le flash message
    //        $tpl = new Template();
    //        $tpl->assign('flashMessages', array($this->getMesType() => $this->replaceVariableErrText()));
    //        return $tpl->fetch('layout/_flash-message.tpl', NULL, NULL, NULL, FALSE, TRUE, FALSE, TRUE);
    //    }
}
