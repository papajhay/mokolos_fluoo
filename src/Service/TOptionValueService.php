<?php declare(strict_types=1);

namespace App\Service;

use App\Repository\TAOptionValueProviderRepository;
use App\Repository\TOptionValueRepository;
use App\Service\Provider\TAOptionValueProviderService;

class TOptionValueService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TOptionValueRepository $optionValueRepository,
        private TAOptionValueProviderRepository $optionValueProviderRepository,
        private TAOptionValueProviderService $optionValueProviderService
    ) {
    }

    /**
     * créé un optionValue et le optionvaluefournisseur associé si il n'existe pas.
     * @param string  $idOptionValueSource id de l'optionValue chez le fournisseur
     * @param TOption $option              option
     * @param string  $nomOptionValue      libelle de l'option
     * @param string  $productAlias        product alias de l'optionvalue pour le fournisseur si applicable
     * @param string  $elementId           elementId de l'optionvalue pour le fournisseur si applicable
     */
    public function createIfNotExist(string $idOptionValueSource, int $idProvider, TOption $option, string $nomOptionValue, string $productAlias = '', string $elementId = '')
    {
        // on fait un trim sur l'id option value source pour éviter des bugs avec des espaces qui pourrait être ajouter
        $idOptionValueSourceTrim = trim($idOptionValueSource);

        // on recherche l'option value fournisseur
        $optionValueProvider = $this->optionValueProviderService->findByIdOptionValueSrc($idOptionValueSourceTrim, $idProvider, $option->getIdOption());

        // on verifie si cette option value de fournisseur existe chez nous
        if (null !== $optionValueProvider && null !== $optionValueProvider->getIdOptionValue()) {
            // on récupére l'option value correspondante
            $optionValue = $optionValueProvider->getOptionValue();

            // si on a pas de localisation
            if (null === $optionValue->getIdOptionValue()) {
                // on sauvegarde la localisation
                $optionValue = new TOptionValue();
                $optionValue->setLibelle($nomOptionValue)
                    ->setIdOptionValue($optionValueProvider->getIdOptionValue())
                    ->saveJustLocalization();
            }

            // si la description à changer
            if ($optionValueProvider->getDescription() !== $nomOptionValue) {
                // on met à jour la description fournisseur
                $optionValueProvider->setDescription($nomOptionValue);
                $this->optionValueProviderRepository->save($optionValueProvider);
            }

            // si on a un product alias ou un elementid et qu'il ont changé
            if ('' !== $productAlias && '' !== $elementId & ($optionValueProvider->getProductAlias() !== $productAlias || $optionValueProvider->getElementId() !== $elementId)) {
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
            $optionValue->setLibelle($nomOptionValue)
                ->setIdOption($option->getIdOption());
            $this->optionValueRepository->save($optionValue);

            // création de la liaison entre le fournisseur et l'option value
            $this->optionValueProviderService->createNew($optionValue->getIdOptionValue(), $idProvider, $idOptionValueSourceTrim, $nomOptionValue, $option->getIdOption(), $productAlias, $elementId);
        }

        // on renvoi l'optionValue
        return $optionValue;
    }
}
