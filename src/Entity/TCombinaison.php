<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TCombinaisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TCombinaisonRepository::class)]
class TCombinaison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $selection = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateMaj = null;

    // private $_produit		 = NULL;
    #[ORM\ManyToOne(inversedBy: 'tCombinaisons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TProduct $tProduct = null;

    // private $_optionValue	 = NULL;
    #[ORM\ManyToOne(inversedBy: 'tCombinaisons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TOptionValue $tOptionValue = null;

    /**
     * l'objet de prix associé
     * l'objet est rempli automatiquement quand on appel findAllBySelectionWithPrix. Dans le cas contraire un id de fournisseur sera requis pour aller chercher le bon prix.
     * @var TCombinaisonPrix
     */
    #[ORM\OneToOne(inversedBy: 'tCombinaison', cascade: ['persist', 'remove'])]
    private ?TCombinaisonPrice $tCombinaisonPrice = null;

    public function __construct()
    {
        $this->tCombinaisonPrices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSelection(): ?string
    {
        return $this->selection;
    }

    public function setSelection(string $selection): static
    {
        $this->selection = $selection;

        return $this;
    }

    public function getDateMaj(): ?\DateTimeImmutable
    {
        return $this->dateMaj;
    }

    public function setDateMaj(\DateTimeImmutable $dateMaj): static
    {
        $this->dateMaj = $dateMaj;

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

    public function getTProduct(): ?TProduct
    {
        return $this->tProduct;
    }

    public function setTProduct(?TProduct $tProduct): static
    {
        $this->tProduct = $tProduct;

        return $this;
    }

    public function getTOptionValue(): ?TOptionValue
    {
        return $this->tOptionValue;
    }

    public function setTOptionValue(?TOptionValue $tOptionValue): static
    {
        $this->tOptionValue = $tOptionValue;

        return $this;
    }

    public function getTCombinaisonPrice(): ?TCombinaisonPrice
    {
        return $this->tCombinaisonPrice;
    }

    public function setTCombinaisonPrice(?TCombinaisonPrice $tCombinaisonPrice): static
    {
        $this->tCombinaisonPrice = $tCombinaisonPrice;

        return $this;
    }

    /**
     * Getter pour le sous-objet TCombinaisonPrix.
     * l'objet est rempli automatiquement quand on appel findAllBySelectionWithPrix. Dans le cas contraire un id de fournisseur sera requis pour aller chercher le bon prix.
     * @return TCombinaisonPrix
     */
    //    public function getTCombinaisonPrix($idFournisseur = NULL)
    //    {
    //        if($this->_tCombinaisonPrix === NULL)
    //        {
    //            $this->_tCombinaisonPrix = TCombinaisonPrix::findByParam(array($this->getIdCombinaison(), $idFournisseur));
    //        }
    //
    //        return $this->_tCombinaisonPrix;
    //    }

    /**
     * Setter pour le sous-objet TCombinaisonPrix
     * l'objet est rempli automatiquement quand on appel findAllBySelectionWithPrix. Dans le cas contraire un id de fournisseur sera requis pour aller chercher le bon prix.
     * @return TCombinaison notre objet
     */
    //    public function setTCombinaisonPrix($tCombinaisonPrix)
    //    {
    //        $this->_tCombinaisonPrix = $tCombinaisonPrix;
    //        return $this;
    //    }

    // TODO Repository
    /**
     * Recupere l'objet combinaison en fonction de l'id du produit, de la séléction, de l'id de la quantité et du type de combinaison.
     *
     * @return TCombinaison
     */
    //    public static function findByParam($idProduit, $selection, $idQuantite)
    //    {
    //         //on va verifier si cette combinaison existe dans notre base
    //        $combinaisonRslt = self::findBy(array('id_produit', 'com_selection', 'id_option_value'), array($idProduit, $selection, $idQuantite));
    //
    //         //si on a pas de prix
    //        if($combinaisonRslt == NULL)
    //        {
    //            return new TCombinaison();
    //        }
    //        else
    //        {
    //            return $combinaisonRslt;
    //        }
    //    }

    /**
     * Renvoi les combinaison avec les prix dans les sous objet par rapport aux paramétre.
     * @return TCombinaison[]
     */
    //    public static function findAllBySelectionWithUpdatePrice($selection, $idProduit, $fournisseur)
    //    {
    //         //jointure
    //        $joinList = array(
    //            'cp' => array('table' => TCombinaisonPrix::$_SQL_TABLE_NAME, 'alias' => 'cp', 'joinCondition' => 'cp.id_combinaison = t.id_combinaison', 'subObjectClass' => 'TCombinaisonPrix')
    //        );
    //
    //         //on renvoi l'objet avec les sous objet
    //        return self::findAllBy(array('com_selection', 'id_produit', 'id_fournisseur', 'com_pri_date_maj'), array($selection, $idProduit, $fournisseur->getIdFour(), array($fournisseur->getFouDateValidCachePrice(), '>=')), array(), 0, $joinList);
    //    }

    /**
     * Renvoi un tableau de toutes les combinaisons existant en fonction des paramétres.
     * @return array le tableau des selection
     */
    //    public static function findAllSelectionFromLikeSelection($idProduit, $selection)
    //    {
    //        $return = array();
    //
    //         //on va chercher toutes les combinaisons correspondant à nos critéres
    //        $sql = 'SELECT com_selection
    //				FROM ' . self::$_SQL_TABLE_NAME . '
    //				WHERE id_produit = ' . $idProduit . '
    //				AND com_selection LIKE "' . $selection . '"
    //				GROUP BY com_selection';
    //
    //        $allSelectionRqt = DB::req($sql);
    //         //pour chaque combinaison
    //        while($allSelection	 = $allSelectionRqt->fetch_object())
    //        {
    //             //on l'ajoute dans notre tableau de retour
    //            $return[] = $allSelection->com_selection;
    //        }
    //
    //        return $return;
    //    }
    /**
     * Renvoi un tableau d'objet combinaison pour toutes les combinaison de type souhaité lié à cette séléction.
     *
     * @return array tableau d'objet combinaison
     */
    //    public static function findCombinaisonBySelection($idProduit, $selection)
    //    {
    //        return self::findAllBy(array('id_produit', 'com_selection'), array($idProduit, $selection));
    //    }

    /**
     * Cherche et retourne le prix le plus bas pour un produit.
     *
     * @return \Prix|null
     */
    //    public static function getFirstPriceFor($produit, $idHost, $tabSelection = array(), $returnPrixOnly = TRUE, $client = 0)
    //    {
    //        //on recupere l'objet host
    //        $host = siteHost::findById($idHost);
    //
    //        //on récupére le fournisseur de ce produit
    //        $fournisseur = $produit->getProduitFournisseur()->getFournisseur();
    //
    //        //on crée le critere sous lequel la selection va etre enregistré
    //        $critere = self::ordonneSelection($tabSelection);
    //
    //        //si on a pas de critére on recherchera pour toutes les selections
    //        if($critere == '')
    //        {
    //            $critere = '%';
    //        }
    //
    //        $sql = 'SELECT *
    //							FROM ' . self::$_SQL_TABLE_NAME . ' tc
    //							join ' . TCombinaisonPrix::$_SQL_TABLE_NAME . ' tcp on tcp.id_combinaison = tc.id_combinaison
    //							WHERE com_selection LIKE "' . $critere . '"
    //							and id_produit = ' . $produit->getIdProduit() . '
    //							and id_fournisseur = "' . $fournisseur->getIdFour() . '"
    //							order by com_pri_prix  limit 1';
    //
    //        // récupération du prix à partir de actuel
    //        $papBaseRqt = DB::req($sql);
    //
    //        //le prix n'exite pas on renvoi NULL
    //        if($papBaseRqt->num_rows == 0)
    //        {
    //            return NULL;
    //        }
    //        //le prix existe
    //        else
    //        {
    //            //on récupére les infos du prix à parti de actuellement en base
    //            $papBase = $papBaseRqt->fetch_array();
    //
    //            //si on ne veux que le prix
    //            if($returnPrixOnly)
    //            {
    //                // création d'un objet prix
    //                $prixAchat = new Prix($papBase['com_pri_prix'], 2, System::getTauxTva($host));
    //
    //                // on applique la marge pour obtenir un prix de vente
    //                $prix = $prixAchat->margePrix($fournisseur, $client, $host, $produit->getIdProduit(), implode('-', $tabSelection), $host->getHosPriceDecimal());
    //
    //                //on renvoi le prix
    //                return $prix;
    //            }
    //            //si on veux tous l'array
    //            else
    //            {
    //                return $papBase;
    //            }
    //        }
    //    }

    // TODO Service
    /**
     * Avant une mise à jour, on met à jour la date de derniére mise à jour.
     */
    //    public function _preUpdate()
    //    {
    //        $date = new DateHeure();
    //        $this->setComDateMaj($date->format(DateHeure::DATETIMEMYSQL));
    //        parent::_preUpdate();
    //    }

    /**
     * Met à jour la date de la derniére maj de cette combinaison ou sauvegarde une nouvelle combinaison si elle n'existait pas.
     *
     * @return bool TRUE si on a sauvé une combinaison FALSE si on ne l'a pas fait (prix NULL, fournisseur LGI)
     */
    //    public static function sauvegardeCombinaison($idProduit, $selection, $idQuantite, $fournisseur, $prix)
    //    {
    //         //si on a pas de prix
    //        if($prix == NULL)
    //        {
    //             on ne fera rien
    //            return FALSE;
    //        }
    //
    //         //si on est sur le fournisseur LGI
    //        if($fournisseur->getIdFour() == FournisseurLgi::ID_FOUR)
    //        {
    //            // on ne fera rien car inutil de sauvegarder les combinaisons
    //            return FALSE;
    //        }
    //
    //        // si on est dans l'admin
    //        if(System::isAdminContext())
    //        {
    //             //on ne fera rien
    //            return FALSE;
    //        }
    //
    //         //on récupére notre id combinaison par rapport au paramétre
    //        $combinaison = self::findByParam($idProduit, $selection, $idQuantite);
    //
    //        $combinaison->setComDateMaj(Tools::getDateForMysqlFormat());
    //
    //         //cette combinaison n'existe pas
    //        if(!$combinaison->exist())
    //        {
    //            $combinaison->setIdProduit($idProduit);
    //            $combinaison->setIdOptionValue($idQuantite);
    //            $combinaison->setComSelection($selection);
    //        }
    //
    //        $combinaison->save();
    //
    //         //on sauvegarde le prix de cette combinaison
    //        TCombinaisonPrix::sauvegardeCombinaisonPrix($combinaison->getIdCombinaison(), $fournisseur, $prix->getMontant(Prix::PRIXHT));
    //
    //        return TRUE;
    //    }

    /**
     * Renvoi la selection réordonné par rapport aux combinaisons.
     * @return string la selection réordonné sous forem de string 1-3-25 ...
     */
    //    public static function ordonneSelection($selection, $aIdOptionValueToRemove = array())
    //    {
    //         //on initialise notre tableau qui contiendra la valeur de retour
    //        $return = array();
    //
    //         //si notre selection est déjà un tableau
    //        if(is_array($selection))
    //        {
    //            $tabSelection = $selection;
    //        }
    //         //si la selection n'est pas un tableau
    //        else
    //        {
    //             //on la transforme en tableau
    //            $tabSelection = explode('-', $selection);
    //        }
    //
    //         //pour chaque élément de la selection
    //        foreach($tabSelection AS $idOptionValue)
    //        {
    //             //si on doit supprimer cette optionValue de la séléction
    //            if(in_array($idOptionValue, $aIdOptionValueToRemove))
    //            {
    //                // on passe à l'étape suivante de la boucle
    //                continue;
    //            }
    //
    //             //on récupére l'objet optionValue
    //            $optionValue = TOptionValue::findById($idOptionValue);
    //
    //             //on ajoute notre id d'optionValue dans notre tableaux de retour
    //            $return[$optionValue->getOption()->getOptOrdre()] = $optionValue->getIdOptionValue();
    //        }
    //
    //        //on trie notre tableau dans le bonne ordre
    //        ksort($return);
    //
    //         //on retourne notre nouvelle selection
    //        return implode('-', $return);
    //    }

    /**
     * Purge les vieille combinaison qui ont plus d'un mois.
     */
    //    public static function purgeOld()
    //    {
    //         //on créé une nouvelle date il y a un mois
    //        $date	 = new DateHeure();
    //        $dure	 = new Duree(-1, Duree::TYPE_MOIS);
    //        $date->addTime($dure);
    //
    //         //on supprime les vieux enregistrement
    //        DB::prepareDeleteAndExecute(self::$_SQL_TABLE_NAME, array(array('com_date_maj', $date->format(DateHeure::DATETIMEMYSQL), 's', '<')));
    //    }

    /**
     * met à jour les prix manquant à partir des combinaison sauvegarder.
     * @return array les donnée avec les prix
     */
    //    public static function updateMissingPriceFromCombinaison($data, $idProduit, $fournisseur)
    //    {
    //         //si On a pas de prix
    //        if(!isset($data['tabPrice']))
    //        {
    //             //on renvoi les donnée sans modification
    //            return $data;
    //        }
    //
    //         //il ne manque pas de prix par défaut
    //        $prixManquant = FALSE;
    //
    //         //on commence par vérifier si il nous manque des prix
    //        foreach($data['tabPrice'] as $quantite)
    //        {
    //             //si il manque un prix
    //            if($quantite['prix'] == NULL)
    //            {
    //                 //on indique qu'il manque un prix
    //                $prixManquant = TRUE;
    //
    //                 //on quitte la boucle
    //                break;
    //            }
    //        }
    //    Debug::dump($data);
    //         //si il ne manque aucun prix
    //        if($prixManquant == FALSE)
    //        {
    //            // on n'a rien à modifié
    //            return $data;
    //        }
    //
    //         //on récupére toutes les combinaison avec le bon prix
    //        $allCombinaison = TCombinaison::findAllBySelectionWithUpdatePrice($data['selection'], $idProduit, $fournisseur);
    //
    //         //pour chaque combinaison
    //        foreach($allCombinaison AS $combinaison)
    //        {
    //             //si on a cette quantité avec un prix qu'on a pas encore récupéré
    //            if(isset($data['tabPrice'][$combinaison->getIdOptionValue()]) && $data['tabPrice'][$combinaison->getIdOptionValue()]['prix'] == NULL)
    //            {
    //                 //on met à jour le prix et on indique que cela vient des combinaison
    //                $data['tabPrice'][$combinaison->getIdOptionValue()]['prix']			 = new Prix($combinaison->getTCombinaisonPrix()->getComPriPrix());
    //                $data['tabPrice'][$combinaison->getIdOptionValue()]['combinaison']	 = TRUE;
    //            }
    //        }
    //
    //        return $data;
    //    }

    /**
     * @return Collection<int, TCombinaisonPrice>
     */
    public function getTCombinaisonPrices(): Collection
    {
        return $this->tCombinaisonPrices;
    }

    public function addTCombinaisonPrice(TCombinaisonPrice $tCombinaisonPrice): static
    {
        if (!$this->tCombinaisonPrices->contains($tCombinaisonPrice)) {
            $this->tCombinaisonPrices->add($tCombinaisonPrice);
            $tCombinaisonPrice->setCombinaison($this);
        }

        return $this;
    }

    public function removeTCombinaisonPrice(TCombinaisonPrice $tCombinaisonPrice): static
    {
        if ($this->tCombinaisonPrices->removeElement($tCombinaisonPrice)) {
            // set the owning side to null (unless already changed)
            if ($tCombinaisonPrice->getCombinaison() === $this) {
                $tCombinaisonPrice->setCombinaison(null);
            }
        }

        return $this;
    }
}
