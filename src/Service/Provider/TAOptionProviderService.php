<?php declare(strict_types=1);

namespace App\Service\Provider;

use App\Repository\TAOptionProviderRepository;
use Doctrine\ORM\EntityManagerInterface;

class TAOptionProviderService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TAOptionProviderRepository $optionProviderRepository
    ) {
    }

    /** Retourne TRUE si ce TAOptionFournisseur existe. Certains paramétres supplémentaires existent pour certains fournisseurs.
     * @param int $idOptionFourSrc id de l'option chez le fournisseur
     * @param int $idProvider id du fournisseur
     * @param int|null $idProduct [=null] id du porduit
     * @return bool
     */
    public function existByIdOptionSrc(string $idOptionFourSrc, int $idProvider, ?int $idProduct = 0): bool
    {
        return $this->optionProviderRepository->existBy($idOptionFourSrc,$idProvider, $idProduct);
    }

    /** Retourne un TAOptionFournisseur en fonction de l'id du fournissseur et de l'id de l'option chez le fournisseur ou null si rien n'a était trouvé. Certains paramétres supplémentaires existent pour certains fournisseurs.
     * @param string   $idOptionProviderSrc id de l'option chez le fournisseur
     * @param int|null $idProduct           [=null] id du porduit ou null si non applicable
     * @param bool     $likeSearch          [=false] mettre TRUE si on veux chercher le opt_fou_id_source avec un like
     */
    public function findByIdOptionSrc(int $idOptionProviderSrc, int $idProvider, int $idProduct = null, bool $likeSearch = false): ?\TAOptionFournisseur
    {
        // paramétre de base de la requête
        $aField = ['id_fournisseur', 'opt_fou_id_source'];
        $aValue = [$idProvider];

        // si on a une recherche classique
        if (!$likeSearch) {
            $aValue[] = $idOptionProviderSrc;
        }
        // on fait une recherche like
        else {
            $aValue[] = [$idOptionProviderSrc, 'LIKE'];
        }

        // si on a id de produit
        if (null !== $idProduct) {
            // on ajoute les paramétre
            $aField[] = 'id_produit';
            $aValue[] = $idProduct;
        }

        // on renvoi le résultat du findBy
        return self::findBy($aField, $aValue);
    }

    /** Cré un nouvel objet "TAOptionFournisseur" et le retourne.
     * @param  int                 $idProvider id du fournisseur
     * @param  int                 $idOption   unsigned $idOption id de l'option
     * @return TAOptionFournisseur Nouvel Objet inseré en base
     */
    public function createNew(int $idProvider, int $idOption, string $idSource, string $descriptionSource, int $idProduct = 0): TAOptionFournisseur
    {
        $optionProvider = new TAOptionFournisseur();
        $optionProvider->setIdFournisseur($idProvider)
                ->setIdOption($idOption)
                ->setIdSource($idSource)
                ->setDescriptionSource($descriptionSource)
                ->setIdProduit($idProduct);
        $this->optionProviderREpository->save($optionProvider);

        return $optionProvider;
    }
}
