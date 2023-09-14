<?php
declare(strict_types=1);

namespace App\Entity;

use App\Enum\RealisaPrint\SpecialOptionEnum;
use App\Repository\TOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Doctrine\Common\Util\ClassUtils;

#[ORM\Entity(repositoryClass: TOptionRepository::class)]
#[ORM\Table(name: "toption")]
class TOption extends BaseEntity
{
    /**
     * id de l'option pour la quantité.
     * @todo voir si on ne peux pas le supprimer pour utilliser autre chose
     */
    // const ID_OPTION_QUANTITY = 7;

    /**
     * type d'option : case à cocher.
     */
     const TYPE_OPTION_CHECKBOX = 3;

    /**
     * type d'option : menu déroulant.
     */
    public const TYPE_OPTION_SELECT = 0;

    /**
     * type d'option : text.
     */
    public const TYPE_OPTION_TEXT = 1;

    /**
     * type d'option : text en lecture seul.
     */
     const TYPE_OPTION_READONLY = 2;

    /**
     * option spécial : option standard.
     */

    public const SPECIAL_OPTION_STANDARD = 0;


    /**
     * option spécial : option des quantité.
     */
    public const SPECIAL_OPTION_QUANTITY = 1;

    /**
     * option spécial : option des délais.
     */
    public const SPECIAL_OPTION_DELAY = 2;

    /**
     * option spécial : option des pays de livraison.
     */
     const SPECIAL_OPTION_DELIVERY_COUNTRY = 3;

    /**
     * option spécial : option des pays de livraison.
     */
    // const SPECIAL_OPTION_NUMBER_OF_MODELS = 4;

    /**
     * option spécial : option des quantité par rouleaux.
     */
    // const SPECIAL_OPTION_QUANTITY_BY_ROLL = 5;

    /**
     * option spécial : option des format.
     */
    public const SPECIAL_OPTION_FORMAT = 6;

    /**
     * option spécial : option des supports (type de papier).
     */
    public const SPECIAL_OPTION_SUPPORT = 7;

    /**
     * option spécial : option de largeur.
     */
    // const SPECIAL_OPTION_WIDTH = 8;

    /**
     * option spécial : option de hauteur.
     */
    // const SPECIAL_OPTION_HEIGHT = 9;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    // clef primaire. id de l'option
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // libéllé de l'option
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    // commentaire de l'option
    private ?string $comments = null;

    #[ORM\Column]
    // ordre d'affichage de l'option
    private ?int $optionOrder = null;

    #[ORM\Column]
    // Type d'option. 0 pour un menu dérulantselect et 1 pour un type texte
    private ?int $typeOption = null;
    // private $optTypeOption = TOption::TYPE_OPTION_SELECT;

    #[ORM\Column(type: 'integer', enumType: SpecialOptionEnum::class)]
    // indique si il s'agit d'une option spécial (quantité, délai, pays de livraison, ...)
    private SpecialOptionEnum |null $specialOption = null;

    #[ORM\OneToMany(mappedBy: 'tOption', targetEntity: TAOptionValueProvider::class)]
    private Collection $taOptionValueProviders;

    #[ORM\OneToMany(mappedBy: 'TOption', targetEntity: TOptionValue::class, orphanRemoval: true)]
    private Collection $tOptionValues;

    #[ORM\OneToMany(mappedBy: 'TOption', targetEntity: TAProductOption::class)]
    private Collection $tAProductOptions;

    #[ORM\OneToMany(mappedBy: 'tOption', targetEntity: TAOptionProvider::class)]
    private Collection $tAOptionProviders;

    public function __construct()
    {
        $this->taOptionValueProviders = new ArrayCollection();
        $this->tOptionValues = new ArrayCollection();
        $this->tAProductOptions = new ArrayCollection();
        $this->tAOptionProviders = new ArrayCollection();
    }
    // private $optSpecialOption = TOption::SPECIAL_OPTION_STANDARD;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function getOptionOrder(): ?int
    {
        return $this->optionOrder;
    }

    public function setOptionOrder(int $optionOrder): static
    {
        $this->optionOrder = $optionOrder;

        return $this;
    }

    public function getTypeOption(): ?int
    {
        return $this->typeOption;
    }

    public function setTypeOption(int $typeOption): static
    {
        $this->typeOption = $typeOption;

        return $this;
    }

    public function getSpecialOption(): ?SpecialOptionEnum
    {
        return $this->specialOption;
    }

    // Setter pour specialOption
    public function setSpecialOption(?SpecialOptionEnum $specialOption): void
    {
        $this->specialOption = $specialOption;
    }

    // TODO Repository
    /**
     * avant de supprimer notre option.
     */
    //    public function _preDelete()
    //    {
    //        parent::_preDelete();
    //
    //        // on supprime les infos lié à ce fournisseur
    //        TAOptionFournisseur::deleteByIdOption($this->getIdOption());
    //
    //        //On récupére tous les Produit Option
    //        $allProduitOption = TAProduitOption::findAllByIdOption($this->getIdOption());
    //
    //        // pour chaque produit option
    //        foreach($allProduitOption AS $produitOption)
    //        {
    //            // on la supprime
    //            $produitOption->delete();
    //        }
    //
    //        //On récupére tous les Option values
    //        $allOptionValue = TOptionValue::findAllByIdOption($this->getIdOption());
    //
    //        // pour chaque option value
    //        foreach($allOptionValue AS $optionValue)
    //        {
    //            // on la supprime
    //            $optionValue->delete();
    //        }
    //    }

    /**
     * Retourne un objet TOption via l'id src et le fournisseur retourne l'id de l'option value si elle n'a pas était trouvé ou NULL si on a pas de source.
     * @return TOption|int|null
     */
    //    public static function findByIdOptionSrc($idOptionFourSrc, $idFour, $idProduct = NULL)
    //    {
    //        // on récupére l'option fournisseur
    //        $optionFournisseur = TAOptionFournisseur::findByIdOptionSrc($idOptionFourSrc, $idFour, $idProduct);
    //
    //        // si on n'a pas trouvé d'option fournisseur
    //        if($optionFournisseur == null || $optionFournisseur->getIdOption() == null)
    //        {
    //            // on quitte la fonction
    //            return null;
    //        }
    //
    //        // on récupére l'option value correspondante
    //        $option = self::findById($optionFournisseur->getIdOption());
    //
    //        // si elle n'a pas était récupéré (localisation manquante)
    //        if($option->getIdOption() === NULL)
    //        {
    //            // on renverra l'id
    //            $option = $optionFournisseur->getIdOption();
    //        }
    //
    //        return $option;
    //    }

    /**
     * retourn les options de produit option value selon le id produits.
     * @return list of TOption
     */
    //    public static function getOptionsFromProduitOptionValue($idProduit)
    //    {
    //        // liste des tables pour les jointure
    //        $joinList = array(array('table' => TOptionValue::$_SQL_TABLE_NAME, 'alias' => 'ov', 'joinCondition' => 't.id_option = ov.id_option'),
    //            array('table' => TAProduitOptionValue::$_SQL_TABLE_NAME, 'alias' => 'pov', 'joinCondition' => 'pov.id_option_value = ov.id_option_value'));
    //
    //        return self::findAllBy(array('pov.id_produit'), array($idProduit), array('tl.opt_libelle'), 0, $joinList);
    //    }

    /**
     * créé un option et le optionfournisseur associé si il n'existe pas.
     */
    //    public static function createIfNotExist($idOptionSource, $idFournisseur, $nomOption, $ordre = 100, $idProduit = 0, $typeOption = TOption::TYPE_OPTION_SELECT, $optSpecialOption = TOption::SPECIAL_OPTION_STANDARD)
    //    {
    //        // on fait un trim sur l'id option value source pour éviter des bugs avec des espaces qui pourrait être ajouter
    //        $idOptionSourceTrim = trim($idOptionSource);
    //
    //        // on va chercher si cette option existe dans la base par rapport au fournisseur
    //        if(TOption::existByIdOptionSrc($idOptionSourceTrim, $idFournisseur, $idProduit))
    //        {
    //            // récupération de l'option value
    //            $o		 = TOption::findByIdOptionSrc($idOptionSourceTrim, $idFournisseur, $idProduit);
    //            $option	 = $o;
    //
    //            // si on a pas de localisation
    //            if(is_numeric($o))
    //            {
    //                // on sauvegarde la localisation
    //                $option = new TOption;
    //                $option->setOptLibelle($nomOption)
    //                    ->setIdOption($o)
    //                    ->setOptTypeOption($typeOption)
    //                    ->saveJustLocalization();
    //            }
    //        }
    //        // si l'option n'existe pas encore
    //        else
    //        {
    //            // on récupére l'ordre qu'on va assigné à l'option
    //            $newOrdre = self::ordreForNewOption($ordre);
    //
    //            // création de l'option
    //            $option = new TOption();
    //            $option->setOptLibelle($nomOption)
    //                ->setOptOrdre($newOrdre)
    //                ->setOptTypeOption($typeOption)
    //                ->setOptSpecialOption($optSpecialOption)
    //                ->save();
    //
    //            // création de l'objet option fournisseur
    //            TAOptionFournisseur::createNew($idFournisseur, $option->getIdOption(), $idOptionSourceTrim, '', $idProduit);
    //        }
    //
    //        // on renvoi l'optionValue
    //        return $option;
    //    }

    /**
     * renvoi le prochain ordre disponible à inséré en base.
     * @return int l'ordre à inséré en base (il aura était multiplié par 100)
     */
    //    public static function ordreForNewOption($ordre = 100)
    //    {
    //        // si notre ordre n'est pas numerique
    //        if(!is_numeric($ordre))
    //        {
    //            // on prend notre ordre par défaut
    //            $ordre = 100;
    //        }
    //        // on commence par vérifier si notre ordre à une valeur trop grande
    //        elseif($ordre > 300)
    //        {
    //            // on le fixe à une valeur max acceptable
    //            $ordre = 300;
    //        }
    //        // on commence par vérifier si notre ordre à une valeur trop petite
    //        elseif($ordre < -300)
    //        {
    //            // on le fixe à une valeur max acceptable
    //            $ordre = -300;
    //        }
    //
    //        // on multiplie notre ordre par 100 car en base on stock des valeurs bien plus grande
    //        $ordreCible = $ordre * 100;
    //
    //        // on récupére en base tous les ordres utilisé
    //        $allOrdreDB = DB::prepareSelectAndExecuteAndFetchAll(self::$_SQL_TABLE_NAME, array('opt_ordre'), array(), 0, array('opt_ordre'));
    //
    //        $allOrdre = array();
    //        // on va en faire un tableau avec uniquement les ordres
    //        foreach($allOrdreDB AS $ordreDB)
    //        {
    //            $allOrdre[$ordreDB['opt_ordre']] = $ordreDB['opt_ordre'];
    //        }
    //
    //        // tant que notre ordre cible est déjà utilisé en base
    //        while(in_array($ordreCible, $allOrdre))
    //        {
    //            // on va chercher le suivant
    //            $ordreCible++;
    //        }
    //
    //        // on renvoi notre ordre
    //        return $ordreCible;
    //    }

    /**
     * Renvoi la valeur par défaut de cette option. l.
     * @return string a valeur pour les options de type text et un id option value pour les options de type select
     */
    //    public function defaultValue($idProduct, $idHost)
    //    {
    //        // si on a une option de type texte
    //        if($this->getOptTypeOption() == TOption::TYPE_OPTION_TEXT)
    //        {
    //            // on récupére le produit option
    //            $productOption = TAProduitOption::findById(array($idProduct, $this->getIdOption(), $idHost));
    //
    //            // on renvoi la valeur par défaut
    //            return $productOption->getProOptDefaultValue();
    //        }
    //
    //        // récupération de l'option value par défaut
    //        $aOptionValue = TOptionValue::findAllActifByIdOptionIdProductIdHost($this->getIdOption(), $idProduct, $idHost, 1);
    //
    //        // si on n'a pas trouvé d'option (ca ne devrait pas arriver)
    //        if(count($aOptionValue) < 1)
    //        {
    //            return FALSE;
    //        }
    //
    //        // on supprime les clef du tableau pour récupérer notre option
    //        $aOptionValueNoKey = array_values($aOptionValue);
    //
    //        // on renvoi l'id de l'option value
    //        return $aOptionValueNoKey[0]->getIdOptionValue();
    //    }

    /**
     * purge les lignes dans la base qui n'ont plus de raison d'être.
     */
    //    public static function purge(TLockProcess $lockProcess)
    //    {
    //        $lockProcess->updateStage('Recherche des options sans produit option');
    //
    //        // recherche des idOption relié à aucun produit (on recherche les id sinon on a des soucis à cause de la localisation et autre)
    //        $sql = 'SELECT id_option
    //			FROM ' . self::$_SQL_TABLE_NAME . '
    //			WHERE opt_type_option IN (' . TOption::TYPE_OPTION_SELECT . ', ' . TOption::TYPE_OPTION_CHECKBOX . ')
    //			AND id_option NOT IN (
    //			SELECT id_option
    //			FROM ' . TAProduitOption::$_SQL_TABLE_NAME . '
    //			GROUP BY id_option)';
    //        $rqt = DB::req($sql);
    //
    //        // pour chaque id option
    //        while($rslt = $rqt->fetch_assoc())
    //        {
    //            $lockProcess->updateStage('Suppression option ' . $rslt['id_option']);
    //
    //            // on récupére l'option
    //            $option = TOption::findById($rslt['id_option']);
    //
    //            // on la supprime
    //            $option->delete();
    //        }
    //
    //        $lockProcess->updateStage('Recherche des options de type select sans option value');
    //
    //        // recherche des idOption de type select relié à aucune option value
    //        $sql2	 = 'SELECT id_option
    //			FROM ' . self::$_SQL_TABLE_NAME . '
    //			WHERE opt_type_option IN (' . TOption::TYPE_OPTION_SELECT . ', ' . TOption::TYPE_OPTION_CHECKBOX . ')
    //			AND id_option NOT IN (
    //			SELECT id_option
    //			FROM ' . TOptionValue::$_SQL_TABLE_NAME . '
    //			GROUP BY id_option)';
    //        $rqt2	 = DB::req($sql2);
    //
    //        // pour chaque id option
    //        while($rslt2 = $rqt2->fetch_assoc())
    //        {
    //            $lockProcess->updateStage('Suppression option ' . $rslt2['id_option']);
    //
    //            // on récupére l'option
    //            $option = TOption::findById($rslt2['id_option']);
    //
    //            // on la supprime
    //            $option->delete();
    //        }
    //    }
    // TODO Service

    /**
     * indique si cette option est une option de delay.
     * @return bool TRUE si c'est une option de délai et FALSE sinon
     */
    //    public function isDelay()
    //    {
    //        // si il s'agit d'une option de quantité
    //        if($this->getOptSpecialOption() == TOption::SPECIAL_OPTION_DELAY)
    //        {
    //            return TRUE;
    //        }
    //
    //        return FALSE;
    //    }

    /**
     * indique si cette option est une option de format.
     * @return bool TRUE si c'est une option de délai et FALSE sinon
     */
    //    public function isFormat()
    //    {
    //        // si il s'agit d'une option de quantité
    //        if($this->getOptSpecialOption() == TOption::SPECIAL_OPTION_FORMAT)
    //        {
    //            return TRUE;
    //        }
    //
    //        return FALSE;
    //    }

    /**
     * indique si cette option est une option de quantité.
     * @return bool TRUE si c'est une option de quantité et FALSE sinon
     */
    //    public function isQuantity()
    //    {
    //        // si il s'agit d'une option de quantité
    //        if($this->getOptSpecialOption() == TOption::SPECIAL_OPTION_QUANTITY)
    //        {
    //            return TRUE;
    //        }
    //
    //        return FALSE;
    //    }
    /**
     * Retourne un boolean qui indique si cette option existe en fonction de l'id du fournissseur et de l'id de l'option chez le fournisseur. Certains paramétres supplémentaires existent pour certains fournisseurs.
     * @return bool
     */
    //    public static function existByIdOptionSrc($idOptionFourSrc, $idFour, $idProduit = 0)
    //    {
    //        return TAOptionFournisseur::existByIdOptionSrc($idOptionFourSrc, $idFour, $idProduit);
    //    }

    /**
     * @return Collection<int, TAOptionValueProvider>
     */
    public function getTaOptionValueProviders(): Collection
    {
        return $this->taOptionValueProviders;
    }

    public function addTaOptionValueProvider(TAOptionValueProvider $taOptionValueProvider): static
    {
        if (!$this->taOptionValueProviders->contains($taOptionValueProvider)) {
            $this->taOptionValueProviders->add($taOptionValueProvider);
            $taOptionValueProvider->setTOption($this);
        }

        return $this;
    }

    public function removeTaOptionValueProvider(TAOptionValueProvider $taOptionValueProvider): static
    {
        if ($this->taOptionValueProviders->removeElement($taOptionValueProvider)) {
            // set the owning side to null (unless already changed)
            if ($taOptionValueProvider->getTOption() === $this) {
                $taOptionValueProvider->setTOption(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TOptionValue>
     */
    public function getTOptionValues(): Collection
    {
        return $this->tOptionValues;
    }

    public function addTOptionValue(TOptionValue $tOptionValue): static
    {
        if (!$this->tOptionValues->contains($tOptionValue)) {
            $this->tOptionValues->add($tOptionValue);
            $tOptionValue->setTOption($this);
        }

        return $this;
    }

    public function removeTOptionValue(TOptionValue $tOptionValue): static
    {
        if ($this->tOptionValues->removeElement($tOptionValue)) {
            // set the owning side to null (unless already changed)
            if ($tOptionValue->getTOption() === $this) {
                $tOptionValue->setTOption(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TAProductOption>
     */
    public function getTAProductOptions(): Collection
    {
        return $this->tAProductOptions;
    }

    public function addTAProductOption(TAProductOption $tAProductOption): static
    {
        if (!$this->tAProductOptions->contains($tAProductOption)) {
            $this->tAProductOptions->add($tAProductOption);
            $tAProductOption->setTOption($this);
        }

        return $this;
    }

    public function removeTAProductOption(TAProductOption $tAProductOption): static
    {
        if ($this->tAProductOptions->removeElement($tAProductOption)) {
            // set the owning side to null (unless already changed)
            if ($tAProductOption->getTOption() === $this) {
                $tAProductOption->setTOption(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TAOptionProvider>
     */
    public function getTAOptionProviders(): Collection
    {
        return $this->tAOptionProviders;
    }

    public function addTAOptionProvider(TAOptionProvider $tAOptionProvider): static
    {
        if (!$this->tAOptionProviders->contains($tAOptionProvider)) {
            $this->tAOptionProviders->add($tAOptionProvider);
            $tAOptionProvider->setTOption($this);
        }

        return $this;
    }

    public function removeTAOptionProvider(TAOptionProvider $tAOptionProvider): static
    {
        if ($this->tAOptionProviders->removeElement($tAOptionProvider)) {
            // set the owning side to null (unless already changed)
            if ($tAOptionProvider->getTOption() === $this) {
                $tAOptionProvider->setTOption(null);
            }
        }

        return $this;
    }

}
