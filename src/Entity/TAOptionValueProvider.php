<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAOptionValueProviderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAOptionValueProviderRepository::class)]
class TAOptionValueProvider
{
    /**
     * valeur d'option spécial : option des format personnalisé.
     */
    // const OPTION_VALUE_SPECIAL_FORMAT_CODE = 'fluoo_special_format';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $sourceKey = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    // product_alias pour les sous produit de p24
    private ?string $productAlias = null;

    #[ORM\Column(type: 'integer')]
    // element_id pour les sous produit de p24
    private ?int $elementId = null;

    #[ORM\ManyToOne(inversedBy: 'taOptionValueProviders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $provider = null;

    #[ORM\ManyToOne(inversedBy: 'taOptionValueProviders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TOption $tOption = null;

    /**
     * Option Value correspondante.
     */
    // private $_optionValue = null;
    #[ORM\ManyToOne(targetEntity: TOptionValue::class, inversedBy: 'tAOptionValueProviders')]
    private ?TOptionValue $tOptionValue = null;

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

    public function getProductAlias(): ?string
    {
        return $this->productAlias;
    }

    public function setProductAlias(string $productAlias): static
    {
        $this->productAlias = $productAlias;

        return $this;
    }

    public function getElementId(): ?int
    {
        return $this->elementId;
    }

    public function setElementId(int $elementId): self
    {
        $this->elementId = $elementId;

        return $this;
    }

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

    public function getTOptionValue(): ?TOptionValue
    {
        return $this->tOptionValue;
    }

    public function setTOptionValue(?TOptionValue $tOptionValue): static
    {
        $this->tOptionValue = $tOptionValue;

        return $this;
    }

    public function getSourceKey(): ?string
    {
        return $this->sourceKey;
    }

    public function setSourceKey(?string $sourceKey): self
    {
        $this->sourceKey = $sourceKey;

        return $this;
    }

    // TODO Repository

    /*
     * renvoi un optionValueFournieur à partir d'un idOption du fournisseur et un id fournisseur.
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

    /*
     * supprime tous les enregistrement en base lié à un idOptionValue.
     */
    //    public static function deleteByIdOptionValue($idOptionValue)
    //    {
    //        DB::prepareDeleteAndExecute(self::$_SQL_TABLE_NAME, array(array('id_option_value', $idOptionValue, 'i')));
    //    }

    /*
     * purge les lignes dans la base qui n'ont plus de raison d'être.
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

    // TODO Service
    /*
     * extrait la valeur numérique d'une quantité enregistré dans optValFouIdSource.
     * @return string la valeur numérique (ex : "10" pour un optValFouIdSource contenant "10 exemplaires")
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
}
