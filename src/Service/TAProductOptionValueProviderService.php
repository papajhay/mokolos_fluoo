<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Provider;
use App\Entity\TAProductOptionValueProvider;
use App\Entity\TOptionValue;
use App\Entity\TProduct;
use App\Repository\TAProductOptionValueProviderRepository;
use Doctrine\ORM\EntityManagerInterface;

class TAProductOptionValueProviderService
{
    public function __construct(
        private TAProductOptionValueProviderRepository $productOptionValueProviderRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Cré un nouvel objet "TAProductOptionValueFournisseur" et le retourne.
     * @param  Provider                     $provider    id du fournisseur
     * @param  TOptionValue                 $optionValue id de l'option value
     * @param  TProduct                     $tProduct    id du produit
     * @param  string                       $idSource    id de l'option value chez le fournisseur
     * @return TAProductOptionValueProvider Nouvel Objet inseré en base
     */
    public function createNewTAProductOptionValueProvider(Provider $provider, TOptionValue $optionValue, TProduct $tProduct, string $idSource): TAProductOptionValueProvider
    {
        $tAProductOptionValueProvider = new TAProductOptionValueProvider();

        $tAProductOptionValueProvider->setProvider($provider)
                                    ->setTOptionValue($optionValue)
                                    ->setTProduct($tProduct)
                                    ->setIdSource($idSource);

        $this->entityManager->persist($tAProductOptionValueProvider);
        $this->entityManager->flush();

        return $tAProductOptionValueProvider;
    }
}
