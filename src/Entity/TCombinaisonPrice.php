<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TCombinaisonPriceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TCombinaisonPriceRepository::class)]
class TCombinaisonPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    //old $date_maj
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?float $price = null;

    //private $idFournisseur;
    //private $idCombinaison;
    //private $_combinaison;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    //Todo : relation
    /**
     * Retourne la combinaison correspondante
     * @return TCombinaison
     */
//    public function getCombinaison()
//    {
//
//        if($this->_combinaison == null)
//        {
//            $this->_combinaison = TCombinaison::findById($this->idCombinaison);
//        }
//
//        return $this->_combinaison;
//    }

    //Todo : repository
    /**
     * renvoi l'objet combinaison prix à partir de l'id de combinaison et de l'id du fournisseur
     * @param int $idCombinaison id de la combinaison
     * @param type $idFournisseur id du fournisseur
     * @return TCombinaisonPrix
     */
//    public static function findByParam($idCombinaison, $idFournisseur)
//    {
    // on recheche la combinaisonPrix
//        $combinaisonPrix = self::findBy(array('id_combinaison', 'id_fournisseur'), array($idCombinaison, $idFournisseur));

    // si on a pas de prix
//        if($combinaisonPrix == NULL)
//        {
//            return new TCombinaisonPrix();
//        }
//        else
//        {
//            return $combinaisonPrix;
//        }
//    }

    //Todo : service
    /**
     * renvoi l'objet combinaison prix à partir de l'id de combinaison et de l'id du fournisseur
     * @param int $idCombinaison id de la combinaison
     * @param fournisseur $fournisseur le fournisseur
     * @param float $prix prix du fournisseur HT en euro
     * @return TCombinaisonPrix
     */
//    public static function sauvegardeCombinaisonPrix($idCombinaison, $fournisseur, $prix)
//    {
    // si on est dans l'admin
//        if(System::isAdminContext())
//        {
    // on renvoi une combinaison vide car on ne veux pas sauvegarder
//            return new TCombinaisonPrix();
//        }

    // on récupére notre objet combinaison Prix si il existe
//        $combinaisonPrix = self::findByParam($idCombinaison, $fournisseur->getIdFour());

    // cette combinaison Prix n'existe pas
//        if(!$combinaisonPrix->exist())
//        {
    // on met à jour les paramétre de la combinaison
//            $combinaisonPrix->setIdCombinaison($idCombinaison);
//            $combinaisonPrix->setIdFournisseur($fournisseur->getIdFour());
//        }
    // si cette combinaison existe déjà et que son prix à changer
//        elseif($combinaisonPrix->getComPriPrix() != $prix && $combinaisonPrix->getComPriDateMaj() > $fournisseur->getFouDateValidCachePrice())
//        {
//            $date = new DateHeure();

    // on met à jour la date du cache
//            $fournisseur->setFouDateValidCachePrice($date->format(DateHeure::DATETIMEMYSQL))
//                ->save();
//        }

    // On met à jour le prix
//        $combinaisonPrix->setComPriPrix($prix);

    // on sauvegarde les modification
//        $combinaisonPrix->save();

    // on retourne l'objet
//        return $combinaisonPrix;
//    }


    /**
     * purge les vieille combinaisonPrix qui ont plus d'un mois
     */
//    static public function purgeOld()
//    {
    // on créé une nouvelle date il y a un mois
//        $date = new DateHeure();
//        $dure = new Duree(-1, Duree::TYPE_MOIS);
//        $date->addTime($dure);

    // on supprime les vieux enregistrement
//        DB::prepareDeleteAndExecute(self::$_SQL_TABLE_NAME, array(array('com_pri_date_maj', $date->format(DateHeure::DATETIMEMYSQL), 's', '<')));
//    }
}
