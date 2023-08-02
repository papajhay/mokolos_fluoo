<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAOptionProviderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAOptionProviderRepository::class)]
class TAOptionProvider
{
    /**
     * option spécial : option de hauteur.
     */
    // const OPTION_SPECIAL_WIDTH = 'fluoo_width';

    /**
     * option spécial : option de laargeur.
     */
    // const OPTION_SPECIAL_HEIGHT = 'fluoo_height';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $optIdSource = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionSource = null;

    /**
     * id du fournisseur
     * @var Provider|null
     */
    #[ORM\ManyToOne(inversedBy: 'tAOptionProviders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $provider = null;

    /**
     * opion à laquelle est lié notre option fournisseur
     * @var TOption|null
     */
    //private $_option = null;
    #[ORM\ManyToOne(inversedBy: 'tAOptionProviders')]
    private ?TOption $tOption = null;

    #[ORM\ManyToOne(inversedBy: 'tAOptionProviders')]
    private ?TProduct $tProduct = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getOptIdSource(): ?string
    {
        return $this->optIdSource;
    }

    public function setOptIdSource(string $optIdSource): static
    {
        $this->optIdSource = $optIdSource;

        return $this;
    }

    public function getDescriptionSource(): ?string
    {
        return $this->descriptionSource;
    }

    public function setDescriptionSource($descriptionSource): static
    {
        $this->descriptionSource = $descriptionSource;

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

    public function getTProduct(): ?TProduct
    {
        return $this->tProduct;
    }

    public function setTProduct(?TProduct $tProduct): static
    {
        $this->tProduct = $tProduct;

        return $this;
    }

    // TODO : REPOSITORY
    /*
     * Cré un nouvel objet "TAOptionFournisseur" et le retourne
     * @param int unsigned $idFournisseur id du fournisseur
     * @param int unsigned $idOption id de l'option
     * @param string $optFouIdSource id de l'option chez le fournisseur
     * @param string $optFouDescriptionSource description de l'option chez le fournisseur
     * @param int $idProduit [optional=0] id du produit si applicable ou 0 sinon
     * @return TAOptionFournisseur Nouvel Objet inseré en base
     */
    // public static function createNew($idFournisseur, $idOption, $optFouIdSource, $optFouDescriptionSource, $idProduit = 0)
    // {
    // 	$optionFournisseur = new TAOptionFournisseur();
    // 	$optionFournisseur->setIdFournisseur($idFournisseur)
    // 			->setIdOption($idOption)
    // 			->setOptFouIdSource($optFouIdSource)
    // 			->setOptFouDescriptionSource($optFouDescriptionSource)
    // 			->setIdProduit($idProduit)
    // 			->save();

    // 	return $optionFournisseur;
    // }

    /*
     * Retourne un TAOptionFournisseur en fonction de l'id du fournissseur et de l'id de l'option chez le fournisseur ou null si rien n'a était trouvé. Certains paramétres supplémentaires existent pour certains fournisseurs.
     * @param string $idOptionFourSrc  id de l'option chez le fournisseur
     * @param int $idFour id du fournisseur
     * @param int|null $idProduct [=null] id du porduit ou null si non applicable
     * @param bool $likeSearch [=false] mettre TRUE si on veux chercher le opt_fou_id_source avec un like
     *
     * @return \TAOptionFournisseur|null
     */
    // public static function findByIdOptionSrc($idOptionFourSrc, $idFour, $idProduct = null, $likeSearch = false)
    // {
    // 	// paramétre de base de la requête
    // 	$aField	 = array('id_fournisseur', 'opt_fou_id_source');
    // 	$aValue	 = array($idFour);

    // 	// si on a une recherche classique
    // 	if(!$likeSearch)
    // 	{
    // 		$aValue[] = $idOptionFourSrc;
    // 	}
    // 	// on fait une recherche like
    // 	else
    // 	{
    // 		$aValue[] = array($idOptionFourSrc, 'LIKE');
    // 	}

    // 	// si on a id de produit
    // 	if($idProduct !== null)
    // 	{
    // 		// on ajoute les paramétre
    // 		$aField[]	 = 'id_produit';
    // 		$aValue[]	 = $idProduct;
    // 	}

    // 	// on renvoi le résultat du findBy
    // 	return self::findBy($aField, $aValue);
    // }

    /*
     * Retourne un TAOptionFournisseur en fonction de l'id du fournissseur et de l'id de l'option. Certains paramétres supplémentaires existent pour certains fournisseurs.
     * @param int $idFour id du fournisseur
     * @param int $idOption id de l'option
     * @param int|null $idProduit [=null] id du porduit
     * @return \TAOptionFournisseur|null
     */
    // public static function findByIdOptionAndFour($idFour, $idOption, $idProduit = 0)
    // {
    // 	return self::findBy(array('id_fournisseur', 'id_option', 'id_produit'), array($idFour, $idOption, $idProduit));
    // }

    /*
     * Retourne TRUE si ce TAOptionFournisseur existe. Certains paramétres supplémentaires existent pour certains fournisseurs.
     * @param string $idOptionFourSrc  id de l'option chez le fournisseur
     * @param int $idFour id du fournisseur
     * @param int|null $idProduit [=null] id du porduit
     * @return bool
     */
    // public static function existByIdOptionSrc($idOptionFourSrc, $idFour, $idProduit = 0)
    // {
    // 	return self::existBy(array('id_fournisseur', 'opt_fou_id_source', 'id_produit'), array($idFour, $idOptionFourSrc, $idProduit));
    // }

    /*
     * supprime tous les enregistrement en base lié à un idOption
     * @param int $idOption
     */
    // public static function deleteByIdOption($idOption)
    // {
    // 	DB::prepareDeleteAndExecute(self::$_SQL_TABLE_NAME, array(array('id_option', $idOption, 'i')));
    // }
}
