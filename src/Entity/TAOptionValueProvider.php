<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAOptionValueProviderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAOptionValueProviderRepository::class)]
class TAOptionValueProvider
{
    /**
     * valeur d'option spécial : option des format personnalisé
     */
    //const OPTION_VALUE_SPECIAL_FORMAT_CODE = 'fluoo_special_format';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idOptionValue = null;

    #[ORM\Column]
    private ?int $idSource = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    //product_alias pour les sous produit de p24
    private ?string $productAlias = null;

    #[ORM\Column(length: 255)]
    //element_id pour les sous produit de p24
    private ?string $elementId = null;

    #[ORM\ManyToOne(inversedBy: 'taOptionValueProviders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $provider = null;

    #[ORM\ManyToOne(inversedBy: 'taOptionValueProviders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TOption $tOption = null;

    //TODO Relation
    /**
     * Option Value correspondante
     * @var TOptionValue
     */
    //private $_optionValue = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getIdOptionValue(): ?int
    {
        return $this->idOptionValue;
    }

    public function setIdOptionValue(int $idOptionValue): static
    {
        $this->idOptionValue = $idOptionValue;

        return $this;
    }


    public function getIdSource(): ?int
    {
        return $this->idSource;
    }

    public function setIdSource(int $idSource): static
    {
        $this->idSource = $idSource;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getProductAlias(): ?string
    {
        return $this->productAlias;
    }

    public function setProductAlias(string $productAlias): static
    {
        $this->productAlias = $productAlias;

        return $this;
    }

    public function getElementId(): ?string
    {
        return $this->elementId;
    }

    public function setElementId(string $elementId): static
    {
        $this->elementId = $elementId;

        return $this;
    }

    //TODO Getter Option Value
    /**
     * Retourne OptionValue Correspondante
     * @return TOptionValue
     */
//    public function getOptionValue()
//    {
//        if($this->_optionValue == null)
//        {
//            $this->_optionValue = TOptionValue::findById($this->getIdOptionValue());
//        }
//
//        return $this->_optionValue;
//    }

    //TODO Repository

    /**
     * créé un nouvel objet et le renvoi
     * @param int $idOptionValue id de l'option value chez nous
     * @param int $idFournisseur id du fournisseur
     * @param string $idOptionValueSource id de l'optionValue chez le fournisseur
     * @param string $nomOptionValue nom de l'optionValue chez le fournisseur
     * @param int $idOption id de l'option à laquelle est rattaché cette option
     * @param string $productAlias product alias de l'optionvalue pour le fournisseur si applicable
     * @param string $elementId elementId de l'optionvalue pour le fournisseur si applicable
     * @return TAOptionValueFournisseur le nouvel objet
     */
//    public static function createNew($idOptionValue, $idFournisseur, $idOptionValueSource, $nomOptionValue, $idOption, $productAlias = '', $elementId = '')
//    {
//        $ovf = new TAOptionValueFournisseur;
//        $ovf->setIdOptionValue($idOptionValue)
//            ->setIdFournisseur($idFournisseur)
//            ->setOptValFouIdSource($idOptionValueSource)
//            ->setOptValFouDescription($nomOptionValue)
//            ->setIdOption($idOption)
//            ->setOptValFouProductAlias($productAlias)
//            ->setOptValFouElementId($elementId)
//            ->save();
//
//        return $ovf;
//    }


    /**
     * renvoi un optionValueFournieur à partir d'un idOption du fournisseur et un id fournisseur
     * @param string $idOptionValueFourSrc idOption du fournisseur
     * @param int $idFour id du fournisseur
     * @param int|NULL $idOption id de l'option ou NULL si on recherche sans id option
     * @return TAOptionValueFournisseur
     */
//    public static function findByIdOptionValueSrc($idOptionValueFourSrc, $idFour, $idOption = NULL)
//    {
//        $champs	 = array('opt_val_fou_id_source', 'id_fournisseur');
//        $valeurs = array($idOptionValueFourSrc, $idFour);
//
//        // si on cherche avec une id option
//        if($idOption !== NULL)
//        {
//            $champs[]	 = 'id_option';
//            $valeurs[]	 = $idOption;
//        }
//
//        return self::findBy($champs, $valeurs);
//    }


    /**
     * supprime tous les enregistrement en base lié à un idOptionValue
     * @param int $idOptionValue
     */
//    public static function deleteByIdOptionValue($idOptionValue)
//    {
//        DB::prepareDeleteAndExecute(self::$_SQL_TABLE_NAME, array(array('id_option_value', $idOptionValue, 'i')));
//    }


    /**
     * purge les lignes dans la base qui n'ont plus de raison d'être
     * @param TLockProcess $lockProcess objet de lockprocess pour mettre à jour les étapes
     */
//    public static function purge(TLockProcess $lockProcess)
//    {
//        $lockProcess->updateStage('Recherche des options values fournisseur LGI n\'existant plus');
//
//        // on recherche toutes les valeur de fournisseur lié à LGI et n'apparaissant plus dans lgi
//        $sql = 'SELECT ovf.*
//			FROM ' . self::$_SQL_TABLE_NAME . ' ovf
//			LEFT JOIN ' . Categories::$_SQL_TABLE_NAME . ' c
//			ON ovf.opt_val_fou_id_source = c.cat_name
//			WHERE ovf.id_fournisseur = ' . FournisseurLgi::ID_FOUR . '
//			AND c.cat_name IS NULL
//			AND ovf.opt_val_fou_id_source NOT LIKE "%exemplaire%"';
//
//        // pour chaque TAOptionValueFournisseur trouvé
//        foreach(self::findAllSql($sql) AS $optionValueFournisseurLGIASupprimer)
//        {
//            $lockProcess->updateStage('Suppression option value fournisseur ' . $optionValueFournisseurLGIASupprimer->getIdOptionValue() . ' pour fournisseur ' . $optionValueFournisseurLGIASupprimer->getIdFournisseur());
//
//            // on la supprime
//            $optionValueFournisseurLGIASupprimer->delete();
//        }
//    }

    //TODO Service
    /**
     * extrait la valeur numérique d'une quantité enregistré dans optValFouIdSource
     * @return String la valeur numérique (ex : "10" pour un optValFouIdSource contenant "10 exemplaires")
     */
//    public function getQuantiteFromIdSource()
//    {
//        $resultat = array();
//        if(preg_match('#^\D*(\d+)\D+$#', $this->optValFouIdSource, $resultat))
//        {
//            return $resultat[1];
//        }
//        else
//        {
//            return '';
//        }
//    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    public function getTOption(): ?TOption
    {
        return $this->tOption;
    }

    public function setTOption(?TOption $tOption): static
    {
        $this->tOption = $tOption;

        return $this;
    }
}
