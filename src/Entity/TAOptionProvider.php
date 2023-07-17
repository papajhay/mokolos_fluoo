<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAOptionProviderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAOptionProviderRepository::class)]
class TAOptionProvider
{
     /*
	 * *************************************************************************
	 * CONSTANT
	 * *************************************************************************
	 */ 

    /**
	 * option spécial : option de hauteur
	 */
	// const OPTION_SPECIAL_WIDTH = 'fluoo_width';

	/**
	 * option spécial : option de laargeur
	 */
	// const OPTION_SPECIAL_HEIGHT = 'fluoo_height';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idOption = 0;

    #[ORM\Column(length: 255)]
    private ?string $optIdSource = null;

    #[ORM\Column(length: 255)]
    private ?string $descriptionSource = null;

    #[ORM\Column]
    private ?int $idProduct = null;

    #[ORM\ManyToOne(inversedBy: 'tAOptionProviders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $provider = null;


     /**
	 * nom du ou des clés primaires OBLIGATOIREMENT un Array
	 * @var array
	 */
	// public static $_SQL_PK = array('id_fournisseur', 'id_option', 'id_produit');

    /*
	 * *************************************************************************
	 * TO DO : RELATION
	 * *************************************************************************
	 */
    /**
	 * option à laquelle est lié notre option fournisseur
	 * @var \TOption
	 */
	// private $_option = null;

    public function getIdOption(): ?int
    {
        return $this->idOption;
    }

    public function setIdOption(int $idOption): static
    {
        $this->idOption = $idOption;

        return $this;
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

    public function getIdProduct(): ?int
    {
        return $this->idProduct;
    }

    public function setIdProduct(int $idProduct): static
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    public function getDescriptionSource()
    {
        return $this->descriptionSource;
    }

    public function setDescriptionSource($descriptionSource)
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

   
     /*
	 * *************************************************************************
	 * TO DO : REPOSITORY
	 * *************************************************************************
	 */ 
    /**
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


	/**
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


	/**
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


	/**
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


	/**
	 * supprime tous les enregistrement en base lié à un idOption
	 * @param int $idOption
	 */
	// public static function deleteByIdOption($idOption)
	// {
	// 	DB::prepareDeleteAndExecute(self::$_SQL_TABLE_NAME, array(array('id_option', $idOption, 'i')));
	// }

}
