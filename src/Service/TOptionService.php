<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TOption;
use App\Repository\TAProductOptionRepository;

class TOptionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TOptionRepository $OptionRepository,
        private TAProductOptionRepository $productOptionRepository,
        private TAOptionProviderService $optionProviderService
    ) {
    }

    /**
     * Retourne un boolean qui indique si cette option existe en fonction de l'id du fournissseur et de l'id de l'option chez le fournisseur. Certains paramétres supplémentaires existent pour certains fournisseurs.
     */
    public function existByIdOptionSrc($idOptionFourSrc, $idFour, $idProduct = 0): bool
    {
        return $this->optionProviderService->existByIdOptionSrc($idOptionFourSrc, $idFour, $idProduct);
    }

    /**
     * renvoi le prochain ordre disponible à inséré en base.
     * @return int l'ordre à inséré en base (il aura était multiplié par 100)
     */
    public static function ordreForNewOption(int $ordre = 100): float|int
    {
        // si notre ordre n'est pas numerique
        if (!is_numeric($ordre)) {
            // on prend notre ordre par défaut
            $ordre = 100;
        }
        // on commence par vérifier si notre ordre à une valeur trop grande
        elseif ($ordre > 300) {
            // on le fixe à une valeur max acceptable
            $ordre = 300;
        }
        // on commence par vérifier si notre ordre à une valeur trop petite
        elseif ($ordre < -300) {
            // on le fixe à une valeur max acceptable
            $ordre = -300;
        }

        // on multiplie notre ordre par 100 car en base on stock des valeurs bien plus grande
        $ordreCible = $ordre * 100;

        // on récupére en base tous les ordres utilisé
        $allOrdreDB = DB::prepareSelectAndExecuteAndFetchAll(self::$_SQL_TABLE_NAME, ['opt_ordre'], [], 0, ['opt_ordre']);

        $allOrdre = [];
        // on va en faire un tableau avec uniquement les ordres
        foreach ($allOrdreDB as $ordreDB) {
            $allOrdre[$ordreDB['opt_ordre']] = $ordreDB['opt_ordre'];
        }

        // tant que notre ordre cible est déjà utilisé en base
        while (in_array($ordreCible, $allOrdre, true)) {
            // on va chercher le suivant
            ++$ordreCible;
        }

        // on renvoi notre ordre
        return $ordreCible;
    }

    /**
     * Retourne un objet TOption via l'id src et le fournisseur retourne l'id de l'option value si elle n'a pas était trouvé ou NULL si on a pas de source.
     */
    public function findByIdOptionSrc(string $idOptionProviderSrc, int $idProvider, int $idProduct = null): TOption|int|null
    {
        // on récupére l'option fournisseur
        $optionFournisseur = $this->optionProviderService->findByIdOptionSrc($idOptionProviderSrc, $idProvider, $idProduct);

        // si on n'a pas trouvé d'option fournisseur
        if (null === $optionFournisseur || null === $optionFournisseur->getIdOption()) {
            // on quitte la fonction
            return null;
        }

        // on récupére l'option value correspondante
        $option = self::findById($optionFournisseur->getIdOption());

        // si elle n'a pas était récupéré (localisation manquante)
        if (null === $option->getIdOption()) {
            // on renverra l'id
            $option = $optionFournisseur->getIdOption();
        }

        return $option;
    }

    /**
     * créé un option et le optionfournisseur associé si il n'existe pas.
     * @return TOption
     */
    public function createIfNotExist(string $idOptionSource, int $idProvider, string $nomOption, int $ordre = 100, $idProduct = 0, int $typeOption = TOption::TYPE_OPTION_SELECT, $optSpecialOption = TOption::SPECIAL_OPTION_STANDARD): TOption|int|null
    {
        // on fait un trim sur l'id option value source pour éviter des bugs avec des espaces qui pourrait être ajouter
        $idOptionSourceTrim = trim($idOptionSource);

        // on va chercher si cette option existe dans la base par rapport au fournisseur
        if ($this->existByIdOptionSrc($idOptionSource, $idProvider, $idProduct)) {
            // récupération de l'option value
            $o = $this->findByIdOptionSrc($idOptionSourceTrim, $idProvider, $idProduct);
            $option = $o;

            // si on a pas de localisation
            if (is_numeric($o)) {
                // on sauvegarde la localisation
                $option = new TOption();
                $option->setOptLibelle($nomOption)
                    ->setIdOption($o)
                    ->setOptTypeOption($typeOption)
                    ->saveJustLocalization();
            }
        }
        // si l'option n'existe pas encore
        else {
            // on récupére l'ordre qu'on va assigné à l'option
            $newOrdre = $this->ordreForNewOption($ordre);

            // création de l'option
            $option = new TOption();
            $option->setLibelle($nomOption)
                ->setOptionOrder($newOrdre)
                ->setTypeOption($typeOption)
                ->setSpecialOption($optSpecialOption);
            $this->OptionRepository->save($option);

            // création de l'objet option fournisseur
            $this->optionProviderService->createNew($idProvider, $option->getIdOption(), $idOptionSourceTrim, '', $idProduct);
        }

        // on renvoi l'optionValue
        return $option;
    }

    /**
     * indique si cette option est une option de quantité.
     * @return bool TRUE si c'est une option de quantité et FALSE sinon
     */
    public function isQuantity(TOption $option): bool
    {
        // si il s'agit d'une option de quantité
        if (TOption::SPECIAL_OPTION_QUANTITY === $option->getOptSpecialOption()) {
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
        if (TOption::SPECIAL_OPTION_DELAY === $option->getSpecialOption()) {
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
        if (TOption::TYPE_OPTION_TEXT === $this->getTypeOption()) {
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
