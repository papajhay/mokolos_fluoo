<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Provider;
use App\Entity\TAOptionProvider;
use App\Entity\TOption;
use App\Entity\TProduct;
use App\Enum\RealisaPrint\SpecialOptionEnum;
use App\Enum\RealisaPrint\TypeOptionEnum;
use App\Repository\TAProductOptionRepository;
use App\Repository\TOptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Provider\TAOptionProviderService;

class TOptionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TOptionRepository $optionRepository,
        private TAProductOptionRepository $productOptionRepository,
        private TAOptionProviderService $optionProviderService
    ) {
    }

    /**
     * Retourne un boolean qui indique si cette option existe en fonction de l'id du fournissseur et de l'id de l'option chez le fournisseur. Certains paramétres supplémentaires existent pour certains fournisseurs.
     */
    public function existByIdOptionSrc(string $idOptionProviderSrc, Provider $provider, TProduct $tProduct = null): bool
    {
        return $this->optionProviderService->existByIdOptionSrc($idOptionProviderSrc, $provider, $tProduct);
    }

    /**
     * renvoi le prochain ordre disponible à inséré en base.
     * @return int l'ordre à inséré en base (il aura était multiplié par 100)
     */
    public function orderForNewOption(int $order = 100): float|int
    {
        // si notre ordre n'est pas numerique
        if (!is_numeric($order)) {
            // on prend notre ordre par défaut
            $order = 100;
        }
        // on commence par vérifier si notre ordre à une valeur trop grande
        elseif ($order > 300) {
            // on le fixe à une valeur max acceptable
            $order = 300;
        }
        // on commence par vérifier si notre ordre à une valeur trop petite
        elseif ($order < -300) {
            // on le fixe à une valeur max acceptable
            $order = -300;
        }

        // on multiplie notre ordre par 100 car en base on stock des valeurs bien plus grande
        $orderCible = $order * 100;

        // on récupére en base tous les ordres utilisé
        // TODO create function prepareSelectAndExecuteAndFetchAll
        $allOrderDB = $this->optionRepository->prepareSelectAndExecuteAndFetchAll(TOption::class, ['option_order'], [['option_order', 2, 'OR', '='], ['option_order', 1, 'OR', '>']], 0, ['option_order']);
        /* dump($allOrderDB); die; */
        $allOrder = [];
        // on va en faire un tableau avec uniquement les ordres
        foreach ($allOrderDB as $orderDB) {
            $allOrder[$orderDB['option_order']] = $orderDB['option_order'];
        }

        // tant que notre ordre cible est déjà utilisé en base
        while (in_array($orderCible, $allOrder, true)) {
            // on va chercher le suivant
            ++$orderCible;
        }

        // on renvoi notre ordre
        return $orderCible;
    }

    /**
     * Retourne un objet TOption via l'id src et le fournisseur retourne l'id de l'option value si elle n'a pas était trouvé ou NULL si on a pas de source.
     */
    public function findByIdOptionSrc(string $idOptionProviderSrc, Provider $provider, TProduct $tProduct = null): TOption|int|null
    {
        // on récupére l'option fournisseur
        /** @var TAOptionProvider $optionProvider */
        $optionProvider = $this->entityManager->getRepository(TAOptionProvider::class)->findByIdOptionSrc($idOptionProviderSrc, $provider, $tProduct);

        // si on n'a pas trouvé d'option fournisseur
        if (!isset($optionProvider)) {
            // on quitte la fonction
            return null;
        }

        // on récupére l'option value correspondante
        $option = $this->optionRepository->find($optionProvider->getTOption()->getId());

        // si elle n'a pas était récupéré (localisation manquante)
        if (!isset($option)) {
            // on renverra l'id
            $option = $optionProvider->getId();
        }

        return $option;
    }

    /**
     * créé un option et le optionfournisseur associé si il n'existe pas
     * @param string $idOptionSource id de l'option chez le fournisseur
     * @param Provider $provider id du fournisseur
     * @param string $nameOption libelle de l'option
     * @param int|NULL $order ordre de l'option
     * @param TProduct $tProduct [optional=0] id du produit si applicable
     * @param TypeOptionEnum $typeOption [optional=TOption::TYPE_OPTION_SELECT] Type d'option (menu déroulant ou texte)
     * @param SpecialOptionEnum $optSpecialOption [optional=TOption::SPECIAL_OPTION_STANDARD] indique si il s'agit d'une option spécial (quantité, délai, pays de livraison, ...). Voir constante de classe
     */
    public function createIfNotExist(string $idOptionSource, Provider $provider, string $nameOption, $order = 100, TProduct $tProduct,  int $typeOption = TOption::TYPE_OPTION_SELECT, SpecialOptionEnum $optSpecialOption = SpecialOptionEnum::SPECIAL_OPTION_STANDARD): TOption|int|null
    {
        // on fait un trim sur l'id option value source pour éviter des bugs avec des espaces qui pourrait être ajouter
        $idOptionSourceTrim = trim($idOptionSource);

        // on va chercher si cette option existe dans la base par rapport au fournisseur
        if (null !== $tOption = $this->findByIdOptionSrc($idOptionSourceTrim, $provider, $tProduct)) {
            // stocké si localisation
            return $tOption;
        }
        // si l'option n'existe pas encore
        else {
            // on récupére l'ordre qu'on va assigné à l'option
            $newOrder = $this->orderForNewOption($order);

            $tOption = new TOption();
            $tOption->setLabel($nameOption)
                     ->setOptionOrder($newOrder)
                     ->setTypeOption($typeOption)
                     ->setSpecialOption(SpecialOptionEnum::SPECIAL_OPTION_STANDARD);

            $this->entityManager->persist($tOption);
            $this->entityManager->flush();

            $this->optionProviderService->createNewTAOptionProvider($provider, $tOption, $idOptionSourceTrim, $tProduct);

            return $tOption;
        }
    }

    /**
     * indique si cette option est une option de quantité.
     * @return bool TRUE si c'est une option de quantité et FALSE sinon
     */
    public function isQuantity(TOption $option): bool
    {
        // si il s'agit d'une option de quantité
        if (SpecialOptionEnum::SPECIAL_OPTION_QUANTITY === $option->getOptSpecialOption()) {
            return true;
        }

        return false;
    }

    /**
     * indique si cette option est une option de delay.
     * @return bool TRUE si c'est une option de délai et FALSE sinon
     */
    public function isDelay(TOption $option): bool
    {
        // si il s'agit d'une option de quantité
        if (SpecialOptionEnum::SPECIAL_OPTION_DELAY === $option->getSpecialOption()) {
            return true;
        }

        return false;
    }

    /**
     * Renvoi la valeur par défaut de cette option. l.
     * @param  int         $idProduct id du produit
     * @param  string      $idHost    id du site
     * @return bool|string a valeur pour les options de type text et un id option value pour les options de type select
     */
    public function defaultValue(TOption $option, int $idProduct, string $idHost): bool|string
    {
        // si on a une option de type texte
        if (TypeOptionEnum::TYPE_OPTION_TEXT === $this->getTypeOption()) {
            // on récupére le produit option
            $productOption = $this->productOptionRepository->findById($idProduct, $option->getId(), $idHost);

            // on renvoi la valeur par défaut
            return $productOption->getDefaultValue();
        }

        // TODO Repository findAllActifByIdOptionIdProductIdHost
        // récupération de l'option value par défaut
        $aOptionValue = TOptionValue::findAllActifByIdOptionIdProductIdHost($this->getIdOption(), $idProduct, $idHost, 1);

        // si on n'a pas trouvé d'option (ca ne devrait pas arriver)
        if (count($aOptionValue) < 1) {
            return false;
        }

        // on supprime les clef du tableau pour récupérer notre option
        $aOptionValueNoKey = array_values($aOptionValue);

        // on renvoi l'id de l'option value
        return $aOptionValueNoKey[0]->getIdOptionValue();
    }
}
