<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TOption;
use App\Entity\TOptionValue;
use App\Repository\ProviderRepository;
use App\Repository\TAOptionValueProviderRepository;
use App\Service\Provider\TAOptionValueProviderService;
use Doctrine\ORM\EntityManagerInterface;

class TOptionValueService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TAOptionValueProviderRepository $optionValueProviderRepository,
        private ProviderRepository $providerRepository,
        private TAOptionValueProviderService $optionValueProviderService
    ) {
    }

    /**
     * créé un optionValue et le optionvaluefournisseur associé si il n'existe pas.
     * @param string  $idOptionValueSource id de l'optionValue chez le fournisseur
     * @param TOption $tOption             tOption
     * @param string  $nameOptionValue     libelle de l'option
     * @param string  $productAlias        product alias de l'optionvalue pour le fournisseur si applicable
     * @param int     $elementId           elementId de l'optionvalue pour le fournisseur si applicable
     */
    public function createIfNotExist(string $idOptionValueSource, int $idProvider, TOption $tOption, string $nameOptionValue, int $elementId, string $productAlias = ''): TOptionValue
    {
        // on fait un trim sur l'id option value source pour éviter des bugs avec des espaces qui pourrait être ajouter
        $idOptionValueSourceTrim = trim($idOptionValueSource);

        // on recherche l'option value fournisseur
        $optionValueProvider = $this->optionValueProviderService->findByIdOptionValueSrc($idOptionValueSourceTrim, $idProvider, $tOption->getId());

        // on verifie si cette option value de fournisseur existe chez nous
        if (empty($optionValueProvider) && empty($optionValueProvider->getIdOptionValue())) {
            // on récupére l'option value correspondante
            $optionValue = $optionValueProvider->getOptionValue();

            // si on a pas de localisation
            if (null === $optionValue->getIdOptionValue()) {
                // on sauvegarde la localisation
                $optionValue = new TOptionValue();
                $optionValue->setLibelle($nameOptionValue)
                    ->setIdOptionValue($optionValueProvider->getIdOptionValue())
                    ->saveJustLocalization();
            }

            // si la description à changer
            if ($optionValueProvider->getDescription() !== $nameOptionValue) {
                // on met à jour la description fournisseur
                $optionValueProvider->setDescription($nameOptionValue);
                $this->optionValueProviderRepository->save($optionValueProvider);
            }

            // si on a un product alias ou un elementid et qu'il ont changé
            if ('' !== $productAlias & ($optionValueProvider->getProductAlias() !== $productAlias || $optionValueProvider->getElementId() !== $elementId)) {
                // on met à jour l element id et le product alias
                $optionValueProvider->setProductAlias($productAlias)
                                    ->setElementId($elementId);
                $this->optionValueProviderRepository->save($optionValueProvider);
            }
        }
        // cette option n'existe pas chez nous dans aucune langue
        else {
            // création de l'option value
            $optionValue = new TOptionValue();
            $optionValue
                ->setLibelle($nameOptionValue)
                ->setTOption($tOption);
            $this->optionValueRepository->save($optionValue);

            // création de la liaison entre le fournisseur et l'option value
            $this->optionValueProviderService->createNewTAOptionValueProvider($optionValue, $this->providerRepository->find($idProvider), $idOptionValueSourceTrim, $nameOptionValue, $tOption, $productAlias, $elementId);
        }

        // on renvoi l'optionValue
        return $optionValue;
    }

    public function createTOptionValue(string $nameOptionValue, TOption $tOption): TOptionValue
    {
        $tOptionValue = new TOptionValue();
        $tOptionValue->setLibelle($nameOptionValue)
                     ->setTOption($tOption);

        $this->entityManager->persist($tOptionValue);
        $this->entityManager->flush();

        return $tOptionValue;
    }
}
