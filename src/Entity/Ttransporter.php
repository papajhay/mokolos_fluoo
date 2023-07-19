<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TtransporterRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

// $_SQL_TABLE_NAME = 'lesgrand.t_transporteur';
#[ORM\Entity(repositoryClass: TtransporterRepository::class)]
class Ttransporter
{
    // =================== Constantes ===================

    /**
     * id du transporteur DPD France.
     */
    // const ID_CARRIER_DPD_FRANCE = 1;

    /**
     * id du transporteur CHRONOPOST.
     */
    public const ID_TRANSPORTEUR_CHRONOPOST = 4;

    /**
     * id du transporteur DHL.
     */
    // const ID_TRANSPORTEUR_DHL = 5;

    /**
     * id du transporteur shenker.
     */
    // const ID_TRANSPORTEUR_SHENKER = 6;

    /**
     * id du transporteur UPS.
     */
    // const ID_TRANSPORTEUR_UPS = 7;

    /**
     * id du transporteur DPD.
     */
    // const ID_CARRIER_DPD_EUROPE = 8;

    /**
     * id du transporteur DPD Mail.
     */
    public const ID_TRANSPORTEUR_TNT = 10;

    /**
     * id du transporteur DPD Mail.
     */
    // const ID_TRANSPORTEUR_DASHER = 11;

    /**
     * id du transporteur FEDEX.
     */
    // const ID_TRANSPORTEUR_FEDEX = 12;

    /**
     * id du transporteur Robert Mueller.
     */
    // const ID_TRANSPORTEUR_ROBERT_MUELLER = 14;

    /**
     * id du transporteur Colissimo (La Poste).
     */
    public const ID_CARRIER_COLISSIMO = 15;

    /**
     * id du transporteur XPO Logistics.
     */
    // const ID_TRANSPORTEUR_XPO = 16;

    /**
     * id du transporteur GLS France.
     */
    // const ID_CARRIER_GLS = 17;

    /**
     * id du transporteur France Express.
     */
    // const ID_CARRIER_FRANCE_EXPRESS = 18;

    /**
     * id du transporteur BRT (filiale italienne de DPD a priori).
     */
    // const ID_CARRIER_BRT = 19;

    /**
     * id du transporteur Ciblex.
     */
    // const ID_CARRIER_CIBLEX = 20;

    /**
     * nombre de jour maximum pendant lequel le tracking est disponible. 0 car par défaut pas de tracking.
     */
    // const MAX_TRACKING_DELAY = 0;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    // nom complet tel que vu par le fournisseur
    private ?string $fullname = null;

    #[ORM\Column(length: 255)]
    // code du type de colis pour le mail de livraison
    private ?string $typeColis = null;

    #[ORM\Column(length: 255)]
    // code pour le sprite si on en a un pour ce transporteur
    private ?string $sprite = null;

    /**
     * TLog pour le tracking de ce transporteur.
     * @var TLog
     */
    // public $_trackingLog = null;

    /**
     * liaison entre les id de fournisseur et leur classe spécifique.
     * @var array
     */
    //    private static $_classeDeTransporteur = array(
    //        TTransporteur::ID_CARRIER_DPD_EUROPE => 'transporteurDPD',
    //        TTransporteur::ID_TRANSPORTEUR_DHL	 => 'transporteurDHL',
    //        TTransporteur::ID_CARRIER_COLISSIMO	 => 'transporteurColissimo'
    //    );

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): static
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getTypeColis(): ?string
    {
        return $this->typeColis;
    }

    public function setTypeColis(string $typeColis): static
    {
        $this->typeColis = $typeColis;

        return $this;
    }

    public function getSprite(): ?string
    {
        return $this->sprite;
    }

    public function setSprite(string $sprite): static
    {
        $this->sprite = $sprite;

        return $this;
    }
    /**
     * renvoi le log.
     * @return TLog
     */
    //    public function getTrackingLog()
    //    {
    //        // si on n'a pas encore de log de tracking
    //        if($this->_trackingLog == null)
    //        {
    //            // on le créé
    //            $this->_trackingLog = TLog::initLog('Récupération de tracking ' . $this->getTraNomComplet());
    //        }
    //
    //        return $this->_trackingLog;
    //    }

    /**
     * setteur pour le log.
     * @return $this notre objet
     */
    //    public function setTrackingLog($trackingLog)
    //    {
    //        $this->_trackingLog = $trackingLog;
    //        return $this;
    //    }
    // TODO Repository
    // TODO Service
    /**
     * Crée un nouvel objet "TTransporteur" et le retourne.
     * @return TTransporteur Nouvel Objet inserer un base
     */
    //    public static function createNew($traNom, $traNomComplet, $traTypeColis, $traSprite = '')
    //    {
    //        $transporteur = new self();
    //        $transporteur->setTraNom($traNom)
    //            ->setTraNomComplet($traNomComplet)
    //            ->setTraTypeColis($traTypeColis)
    //            ->setTraSprite($traSprite)
    //            ->save();
    //
    //        return $transporteur;
    //    }
    /**
     * fait un findById mais renvoi l'objet spécifique du transporteur comme transporteurDPD pour DPD ou renvoi un objet TTransporteur par défaut.
     * @param  int                           $idTransporteur id du transporteur
     * @return TTransporteur|transporteurDPD
     */
    public static function findByIdWithChildObject($idTransporteur)
    {
        // si on a déjà chercher ce transporteur
        if (!isset(self::$_cacheFindAllWithChild[$idTransporteur])) {
            // si ce transporteur à sa propre classe
            if (isset(self::$_classeDeTransporteur[$idTransporteur])) {
                // on renverra un objet de cette classe
                $rawClasseName = self::$_classeDeTransporteur[$idTransporteur];
            }
            // pas de classe spécifique
            else {
                // on renvoi un objet TTransporteur
                $rawClasseName = __CLASS__;
            }

            // on récupére la classe avec d'eventuel modification de transporteur
            $classeName = $rawClasseName::className();

            // on met en cache notre objet
            self::$_cacheFindAllWithChild[$idTransporteur] = $classeName::findById($idTransporteur);
        }

        // on renvoi notre objet
        return self::$_cacheFindAllWithChild[$idTransporteur];
    }

    /*
     * fonction permettant de connaitre le nom de la classe appelé
     * @return type
     */
    //    public static function className()
    //    {
    //        return get_called_class();
    //    }

    /*
     * retrouve un transporteur par rapport à une url de tracking
     * @param string $trackingUrl
     * @return TTransporteur|false
     */
    //    public static function findByTrackingUrl($trackingUrl)
    //    {
    //        // url de tracking de DPD
    //        if(preg_match('#tracking.dpd.#', $trackingUrl))
    //        {
    //            // on renvoi le transporteur DPD
    //            return TTransporteur::findById(TTransporteur::ID_CARRIER_DPD_EUROPE);
    //        }
    //
    //        // url de tracking de dasher
    //        if(preg_match('#.dachser.com#', $trackingUrl))
    //        {
    //            // on renvoi le transporteur DPD
    //            return TTransporteur::findById(TTransporteur::ID_TRANSPORTEUR_DASHER);
    //        }
    //
    //        return false;
    //    }
    /*
     * effectue le tracking d'un colis
     * @param TOrdersLivraison $shipping
     * @param TLog|null $log [=null] objet de log ou null si on n'en a pas.
     * @param int $wait [=10] temps en secondes à attendre avant chaque requête de tracking
     * @return boolean true en cas de succés et false si on a un probléme
     */
    //    public function trackShipping(TOrdersLivraison $shipping, $log = null, $wait = 10)
    //    {
    //        // on unset les variables pour virer les erreurs
    //        unset($shipping);
    //        unset($wait);
    //        unset($log);
    //
    //        // par défaut pas de tracking si on n'a pas un objet spécifique qqui gére le transporteur
    //        return false;
    //    }

    /*
     * insére les scan en base
     * @param array $json tableau du json du tracking
     * @return array un tableau des scan du tracking
     */
    //    protected function _insertScan($allScan, TOrdersLivraison $shipping)
    //    {
    //        // pour chaque scan
    //        foreach($allScan AS $scan)
    //        {
    //            // on récupére le statut de cce détail pour savoir si on doit l'affiché au client
    //            $status = $this->_statusForScan($scan);
    //
    //            // mise à jour ou insertion en base du détail
    //            TShippingDetail::createNewOrUpdate($shipping->getIdOrdersLivraison(), $scan['name'], $scan['location'], $scan['date']->format(DateHeure::DATETIMEMYSQL), $status);
    //        }
    //
    //        // on sauvegarde le shipping pour mettre à jour la date de derniére modification
    //        $shipping->save();
    //    }

    /*
     * renvoi le statut d'un scan pour savoir si on va l'afficher au client
     * @param array $scan le scan
     * @return int le statut TShippingDetail::VISIBLE_ADMIN ou TShippingDetail::VISIBLE_ALL
     */
    //    protected function _statusForScan($scan)
    //    {
    //        // si on voit que cette ligne vient d'allemagne ou d'italie
    //        if(preg_match('#\(DE\)|GERMANY|\(IT\)|ITALY#i', $scan['location']))
    //        {
    //            // on ne l'affichera que dans l'admin
    //            return TShippingDetail::VISIBLE_ADMIN;
    //        }
    //
    //        // affichage pour tous
    //        return TShippingDetail::VISIBLE_ALL;
    //    }
}
