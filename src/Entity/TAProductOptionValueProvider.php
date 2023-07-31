<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAProductOptionValueProviderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAProductOptionValueProviderRepository::class)]
class TAProductOptionValueProvider
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    //private ?int $idProvider = null;

    #[ORM\Column]
    //private ?int $idOptionValue = null;

    /**
     * Option Value correspondante
     * @var TOptionValue
     */
    //private $_optionValue = null;

    #[ORM\Column]
    //private ?int $idProduct = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    //Todo :relation
//    public function getIdProvider(): ?int
//    {
//        return $this->idProvider;
//    }
//
//    public function setIdProvider(int $idProvider): static
//    {
//        $this->idProvider = $idProvider;
//
//        return $this;
//    }
//
//    public function getIdOptionValue(): ?int
//    {
//        return $this->idOptionValue;
//    }
//
//    public function setIdOptionValue(int $idOptionValue): static
//    {
//        $this->idOptionValue = $idOptionValue;
//
//        return $this;
//    }
//
//    public function getIdProduct(): ?int
//    {
//        return $this->idProduct;
//    }
//
//    public function setIdProduct(int $idProduct): static
//    {
//        $this->idProduct = $idProduct;
//
//        return $this;
//    }

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

    //Todo : repository
    /**
     * créé un TAProductOptionValueFournisseur si il n'existe pas et le met à jour si besoin
     * @param int $idFournisseur id du fournisseur
     * @param int $idOptionValue id de l'option value
     * @param int $idProduct id du produit
     * @param string $proOptValFouIdSource id de l'option value chez le fournisseur
     * @return TAProductOptionValueFournisseur le nouvel objet
     */
//    public static function createIfNotExist($idFournisseur, $idOptionValue, $idProduct, $proOptValFouIdSource)
//    {
        // on recherche notre TAProduitOptionValue
//        $productOptionValueFournisseur = TAProductOptionValueFournisseur::findById(array($idFournisseur, $idOptionValue, $idProduct));

        // si notre produit option n'existe pas encore
//        if($productOptionValueFournisseur->getIdFournisseur() == NULL)
//        {
            // on va donc créé ce produit optionValue fournisseur
//            $productOptionValueFournisseur->setIdFournisseur($idFournisseur)
//                ->setIdOptionValue($idOptionValue)
//                ->setIdProduct($idProduct)
//                ->setProOptValFouIdSource($proOptValFouIdSource)
//                ->save();
//        }
        // si on a déjà ce produit option value fournisseur et qu'e la derniere vu et plus ancienne que le jour meme'il faut changer le code
//        elseif($productOptionValueFournisseur->getProOptValFouIdSource() != $proOptValFouIdSource)
//        {
            // on créé un log
//            $log = TLog::initLog('Changement de code fournisseur pour un produit option value fournisseur');
//            $log->Erreur($productOptionValueFournisseur->getProOptValFouIdSource() . ' -> ' . $proOptValFouIdSource);

            // on met à jour la date de derniére vu
//            $productOptionValueFournisseur->setProOptValFouIdSource($proOptValFouIdSource)
//                ->save();
//        }
//
//        return $productOptionValueFournisseur;
//    }


    /**
     * renvoi tous les objets lié à un id option value et un id de produit
     * @param string $idOptionValueSupplier id de l'option value chez le fournisseur
     * @param int $idProduct id du produit chez nous
     * @param int $idSupplier id du fournisseur
     * @return TAProductOptionValueFournisseur|null l'objet ou null si rien ne correspond
     */
//    public static function findByIdOptionValueSupplierAndProduct($idOptionValueSupplier, $idProduct, $idSupplier)
//    {
//        return self::findBy(array('pro_opt_val_fou_id_Source', 'id_product', 'id_fournisseur'), array($idOptionValueSupplier, $idProduct, $idSupplier));
//    }


    /**
     * renvoi tous les objets lié à un id option value et un id de produit
     * @param int $idProduct
     * @param int $idOptionValue
     * @return TAProductOptionValueFournisseur
     */
//    public static function findAllByIdProductAndOptionValue($idProduct, $idOptionValue)
//    {
//        return self::findAllBy(array('id_product', 'id_option_value'), array($idProduct, $idOptionValue));
//    }
}
