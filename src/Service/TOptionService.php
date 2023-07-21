<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TOption;

class TOptionService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TOptionRepository $OptionRepository,
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
     * @return TOption|int|null
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
    protected function createIfNotExist(string $idOptionSource, int $idProvider, string $nomOption, int $ordre = 100, $idProduct = 0, int $typeOption = TOption::TYPE_OPTION_SELECT, $optSpecialOption = TOption::SPECIAL_OPTION_STANDARD)
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
}