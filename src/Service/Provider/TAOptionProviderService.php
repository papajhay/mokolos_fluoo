<?php declare(strict_types=1);

namespace App\Service\Provider;

use App\Entity\Provider;
use App\Entity\TAOptionProvider;
use App\Entity\TOption;
use App\Entity\TProduct;
use App\Repository\TAOptionProviderRepository;
use Doctrine\ORM\EntityManagerInterface;

class TAOptionProviderService
{
    public function __construct(
        private TAOptionProviderRepository $optionProviderRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    /** Retourne TRUE si ce TAOptionFournisseur existe. Certains paramétres supplémentaires existent pour certains fournisseurs.
     * @param string $idOptionFourSrc id de l'option chez le fournisseur
     * @param Provider $provider id du fournisseur
     * @param TProduct|null $tProduct [=null] id du porduit
     * @return bool
     */
    public function existByIdOptionSrc(string $idOptionFourSrc, Provider $provider, ?TProduct $tProduct = null): bool
    {
        return $this->optionProviderRepository->existsBy($idOptionFourSrc, $provider, $tProduct);
    }


    /** Cré un nouvel objet "TAOptionFournisseur" et le retourne.
     * @param Provider $provider le fournisseur
     * @param TOption $option unsigned $idOption id de l'option
     * @param string $idSource
     * @param TProduct $tProduct
     * @return TAOptionProvider Nouvel Objet inseré en base
     */
    public function createNewTAOptionProvider(Provider $provider, TOption $option, string $idSource, TProduct $tProduct): TAOptionProvider
    {
        $tAOptionProvider = new TAOptionProvider();
        $tAOptionProvider->setProvider($provider)
                        ->setTOption($option)
                        ->setSourceKey($idSource)
                        ->setTProduct($tProduct);

        $this->entityManager->persist($tAOptionProvider);
        $this->entityManager->flush();

        return $tAOptionProvider;
    }
}
