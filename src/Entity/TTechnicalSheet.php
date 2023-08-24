<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TTechnicalSheetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TTechnicalSheetRepository::class)]
//TFicheTechnique
class TTechnicalSheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $technicalSheetParent = null;

    #[ORM\Column(length: 255)]
//    $produitOptionValuelig
    private ?string $productOptionValueLig = null;

    #[ORM\ManyToOne(inversedBy: 'tTechnicalSheets')]
    private ?TProduct $product = null;

    #[ORM\ManyToOne(inversedBy: 'tTechnicalSheets')]
    private ?TOptionValue $optionValue = null;

//    #[ORM\Column(length: 255)]
//    $ficheTechParent
//    private ?string $TechnicalSheetParent = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTechnicalSheetParent(): ?string
    {
        return $this->technicalSheetParent;
    }

    public function setTechnicalSheetParent(string $technicalSheetParent): static
    {
        $this->technicalSheetParent = $technicalSheetParent;

        return $this;
    }

    /**
     * Retourne l'objet Produit Option Value lié à cet objet
     * @return TAProduitOptionValue
     */
    public function getProductOptionValueLig()
    {
        if (!isset($this->productOptionValueLig)) {
            $this->productOptionValueLig = TAProductOptionValue::findById(array($this->getIdProduct(), $this->getIdOptionValue(), 'lig'));
        }
        return $this->produitOptionValueLig;
    }


    public function getProduct(): ?TProduct
    {
        return $this->product;
    }

    public function setProduct(?TProduct $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getOptionValue(): ?TOptionValue
    {
        return $this->optionValue;
    }

    public function setOptionValue(?TOptionValue $optionValue): static
    {
        $this->optionValue = $optionValue;

        return $this;
    }

    /**
     * Retourne l'objet fiche technique parent de cette fiche technique
     * @return TFicheTechnique
     */
//    public function getTechnicalSheetParent()
//    {
//        if(!isset($this->technicalSheetParent))
//        {
//            $this->technicalSheetParent = TTechnicalSheet::findById($this->getId());
//        }
//
//        return $this->technicalSheetParent;
//    }

    /**
     * renvoi tous les objets TFicheTechnique de permier niveau lié à un produit
     * @param int $idProduit id du produit
     * @return TFicheTechnique[]
     */
    /*static public function findAllRootForIdProduit($idProduit)
    {
        $whereField = array('t.id_produit', 't.id_fiche_technique_parent', 'pov.id_code_pays', 'pov.id_host');
        $whereValue = array($idProduit, 0, 'fr_FR', 'lig');
        $join = array(array('table' => TAProduitOptionValue::$_SQL_LOCALIZATION_TABLE_NAME , 'alias' => 'pov', 'joinCondition' => 't.id_produit = pov.id_produit AND t.id_option_value = pov.id_option_value'));

        return TFicheTechnique::findAllBy($whereField, $whereValue, array('pov.pro_opt_val_libelle'), 0, $join);
    }*/

//    Todo : repository
    /**
     * renvoi tous les objet TFicheTechnique fils de notre objet
     * @return TFicheTechnique
     */
//    public function findAllChilds()
//    {
//        $whereField = array('t.id_produit', 't.id_fiche_technique_parent', 'pov.id_code_pays', 'pov.id_host');
//        $whereValue = array($this->getIdProduit(), $this->getIdFicheTechnique(), 'fr_FR', 'lig');
//        $join = array(array('table' => TAProduitOptionValue::$_SQL_LOCALIZATION_TABLE_NAME , 'alias' => 'pov', 'joinCondition' => 't.id_produit = pov.id_produit AND t.id_option_value = pov.id_option_value'));
//
//        return TTechnicalSheet::findAllBy($whereField, $whereValue, array('pov.pro_opt_val_libelle'), 0, $join);
//    }


    /**
     * renvoi toutes les fiche tecnique parraport à un id de parent et un id de produit
     * @param int $idParent id de la fiche technique parente
     * @param int $idProduct id du produit
     * @return TFicheTechnique[]
     */
//    static public function findAllByParentAndProduct($idParent, $idProduct)
//    {
//        return TFicheTechnique::findAllBy(array('id_fiche_technique_parent', 'id_produit'), array($idParent, $idProduct));
//    }

//     Todo : service
    /**
     * renvoi TRUE si la maquette en volume existe
     * @return boolean
     */
//    public function maquetteEnVolumeExiste()
//    {
    // si on a pas de fiche technique
//        if($this->getFicTecDescription() == '')
//        {
//            return FALSE;
//        }

    // création d'un nouvel objet fichier
//        $fichier = new Fichier();
//        $fichier->setCheminComplet('/home/limprime/' . SUB_DOMAIN . '/assets/data/techniques/maquettes/' . $this->getFicTecDescription());

    // on renvoi TRUE si le fichier exist
//        return $fichier->exist();
//    }


    /**
     * renvoi TRUE si la maquette en volume existe
     * @return boolean
     */
//    public function gabaritsExiste()
//    {
    // si on a pas de fiche technique
//        if($this->getFicTecDescription() == '')
////        {
////            return FALSE;
////        }

    // création d'un nouvel objet fichier
//        $fichier = new Fichier();
//        $fichier->setCheminComplet('/home/limprime/' . SUB_DOMAIN . '/assets/data/techniques/gabarits/' . str_replace('.jpg', '.zip', $this->getFicTecDescription()));

    // on renvoi TRUE si le fichier exist
//        return $fichier->exist();
//    }


    /**
     * renvoi un tableau des produitOptionValues qui sont présent dans $tabProduitOptionValue et pas présent dans $tabFicheTechnique
     * les seuls produitOptionValues renvoyé sont ceux correspondant à l'option des $tabFicheTechnique
     * @param TFicheTechnique $tabFicheTechnique les fiche technique
     * @param TAProduitOptionValue $tabProduitOptionValue les optionsValue
     * @return TAProduitOptionValue
     */
//    static public function OptionsValuesAAjouter($tabFicheTechnique, $tabProduitOptionValue)
//    {
    // initialisation des tableaux de retour et d'id
//        $retour = array();
//        $tabIdOptionValueDansFicheTechnique = array();

    // pour chaque fiche technique
//        foreach($tabFicheTechnique AS $ficheTech)
//        {
    // on récupére sont idOptionValue
//            $tabIdOptionValueDansFicheTechnique[] = $ficheTech->getProductOptionValueLig()->getIdOptionValue();
//        }

    // pour chaque produit option value
//        foreach($tabProductOptionValue AS $productOptionValue)
//        {
    // si cette option n'est pas déjà présente dans la fiche technique et si on est dans l'option correspondant aux fiche technique
//            if(!in_array($productOptionValue->getIdOptionValue(), $tabIdOptionValueDansFicheTechnique) && $productOptionValue->getOptionValue()->getIdOption() == $technicalSheet->getProduitOptionValueLig()->getOptionValue()->getIdOption())
//            {
    // on ajoute au tableau de retour
//                $retour[] = $productOptionValue;
//            }
//        }
//
//        return $retour;
//    }


    /**
     * renvoi la liste des produits options que l'on peux ajouter au niveau suivant de l'arbre
     * @return TAProduitOption
     */
//    public function OptionsAAjouter()
//    {
//        $tabOptionUtilise = array();

    // on met notre objet dans une nouvelle variable pour un traitement récursif
//        $technicalSheetParent = $this;

    // on remonte notre arbre
//        while($$technicalSheetParent->getId() !== NULL)
//        {
    // on ajoute dans le tableau des options utilisé l'id d'option de cette banche
//            $tabOptionUtilise[] = $technicalSheetParent->getProductOptionValueLig()->getOptionValue()->getIdOption();

    // pour traitement récursif
//            $TechnicalSheetParent = $technicalSheetParent->getTechnicalSheetParent();
//        }
//
//        return TAProductOption::findAllForFicheTech($this->getIdProduct(), $tabOptionUtilise);
//    }
}
