<?php declare(strict_types=1);

namespace App\Service\Provider\RealisaPrint;

use App\Entity\Provider;
use App\Entity\TProduct;
use App\Helper\Supplier\Dependency;
use App\Helper\Supplier\Message;
use App\Repository\TAOptionValueProviderRepository;
use App\Repository\TAProductOptionValueRepository;
use App\Service\TAProductOptionService;
use App\Service\TAProductOptionValueService;
use App\Service\TOptionService;
use App\Service\TOptionValueService;
use App\Service\TProductHostService;
use App\Service\TProductService;
use Doctrine\ORM\EntityManagerInterface;

class ExctractPrice extends BaseRealisaPrint
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TAProductOptionValueRepository $productOptionValueRepository,
        private TAOptionValueProviderRepository $optionValueProviderRepository,
        private TOptionService $optionService,
        private TAProductOptionService $productOptionService,
        private TOptionValueService $optionValueService,
        private TAProductOptionValueService $productOptionValueService,
        private TProductHostService $productHostService,
        private TProductService $productService
    ) {
    }

    /**
     * Renvoi l'ordre par défaut pour une option value.
     * @param int $idOptionValueSource        id de l'option value chez le fournisseur
     * @param int $idOptionValueSourceDefault id de l'option value par défaut chez le fournisseur
     */
    private function _defaultOrder(int $idOptionValueSource, int $idOptionValueSourceDefault): int
    {
        // si notre option correspond à l'option par défaut
        if ($idOptionValueSource === $idOptionValueSourceDefault) {
            // on la met en premier pour la séléctionner par défaut
            return 10;
        }

        // ordre standard
        return 100;
    }

    /**
     * Gestion des dépendance par rapport aux options value.
     * @param TOptionValue $optionValue         la valueur de l'option
     * @param int          $idOptionSource      id de l'option du fournisseur
     * @param int          $idOptionValueSource id de la valeur de l'option du fournisseur
     */
    private function _manageDependencyForOptionValue(TOptionValue $optionValue, int $idOptionSource, int $idOptionValueSource): void
    {
        // ajout à notre tableau pour gérer les dépendance
        $this->getDependency()->addOptionValueByIdSource($optionValue, $idOptionSource, $idOptionValueSource);

        // si on est sur les délai
        if ($optionValue->getOption()->isDelay()) {
            // On forcera ces options dans les dépendances car elle n'apparaisse pas dans showvariable
            $this->getDependency()->addDependency($optionValue->getIdOptionValue());
        }
    }

    /**
     * créé et met à jour les options et optionsValues en faisant un appel à l'API du fournisseur.
     * @param Provider $provider
     * @param TProductHost $productHost
     * @param string $idHost Id du site
     * @return array|false  le tableau des id des options values pour les dépendance ou false en cas de probléme
     */
    private function _createAndUpdateOptionValue(Provider $provider, TProductHost $productHost, string $idHost): bool|array
    {
        $return = [];

        // appel de l'api pour récupéré les configuration
        $jsonConfiguration = $this->_apiConfigurations($productHost);

        // si on a un probléme ou si il nous manque les variables
        if (false === $jsonConfiguration || !isset($jsonConfiguration['variables'])) {
            // on quitte la fonction
            return false;
        }

        // pour chaque option du produit
        foreach ($jsonConfiguration['variables'] as $idOptionSource => $optionData) {
            // si on a un champ en lecture seul
            if (true === $optionData['readonly']) {
                // option de type select
                $typeOption = TOption::TYPE_OPTION_READONLY;
                $defaultValue = null;
            }
            // si on a une checkbox
            elseif ('checkbox' === $optionData['type']) {
                // option de type select
                $typeOption = TOption::TYPE_OPTION_CHECKBOX;
                $defaultValue = null;
            }
            // si on a un menu déroulant
            elseif ('select' === $optionData['type']) {
                // option de type select
                $typeOption = TOption::TYPE_OPTION_SELECT;
                $defaultValue = null;
            } else {
                // option de type texte
                $typeOption = TOption::TYPE_OPTION_TEXT;
                $defaultValue = $optionData['default'];
            }

            // si nous sommes sur les quantité
            if (true === $optionData['quantity']) {
                // on va indiqué que nous sommes sur les quantités
                $optSpecialOption = TOption::SPECIAL_OPTION_QUANTITY;
                $ordre = 300;
            }
            // si nous sommes sur les délai
            elseif (true === $optionData['production_time']) {
                // on va indiqué que nous sommes sur les délai
                $optSpecialOption = TOption::SPECIAL_OPTION_DELAY;
                $ordre = 250;
            }
            // autre option
            else {
                // on va indiqué que nous sommes sur une option standard
                $optSpecialOption = TOption::SPECIAL_OPTION_STANDARD;
                $ordre = 100;
            }

            // on créé l'option et le produit option si elles n'existe pas
            $option = $this->optionService->createIfNotExist($idOptionSource, $provider->getId(), $optionData['name'], $ordre, 0, $typeOption, $optSpecialOption);
            $this->productOptionService->createIfNotExist($productHost->getId(), $option, $idHost, $defaultValue, TAProduitOption::STATUS_ACTIF, '', '');

            // on ajoute à notre tableau de traduction des id option source en id option
            Dependency::addIdOptionByIdOptionSource($option->getId(), $idOptionSource);

            // on ajoute notre option à notre tableau de retour
            $return[$option->getId()]['option'] = $option;
            $return[$option->getId()]['optionIdSource'] = $idOptionSource;

            // En cas de changement de type d'option
            if (!$this->productHostService->isVariant($productHost) && $option->getTypeOption() !== $typeOption) {
                // on envoi un message d'erreur
                //                $this->getLog()->Erreur('Le type d\'une option à changer');
                //                $this->getLog()->addLogContent('Option  : '.var_export($option, true));
                //                $this->getLog()->addLogContent('Produit  : '.var_export($product, true));
                //                $this->getLog()->addLogContent('nouveau type : '.$typeOption);

                // on change le type d'option
                $option->setTypeOption($typeOption);
                $this->entityManager->persist($option);
                $this->entityManager->flush();
            }

            // si on n'a pas de valeur pour cette option
            if (false === $optionData['values']) {
                // on passe à l'option suivante
                continue;
            }

            // pour chaque option value
            foreach ($optionData['values'] as $idOptionValueSource => $nameOptionValue) {
                // si cette option à un id supérieur à 1000
                if ($idOptionValueSource >= 1000) {
                    // on ne la traite pas, il s'agit d'une fausse option comme les papier de type "classique" ou "texturé"
                    continue;
                }

                // création de l'option value si elle n'existe pas
                $optionValue = $this->optionValueService->createIfNotExist($idOptionValueSource, $provider->getId(), $option, $this->optionValueNameFromSupplier($nameOptionValue));

                // on récupére l'ordre par défaut
                $order = $this->_defaultOrder($idOptionValueSource, $optionData['default']);

                // liaison de l'option value avec le produit
                $productOptionValue = $this->productOptionValueService->createIfNotExist($productHost->getId(), $optionValue, $idHost, $order);

                // on ajoute notre option value à la variable de retour
                $return[$option->getId()]['optionValues'][$optionValue->getId()]['optionValue'] = $optionValue;
                $return[$option->getId()]['optionValues'][$optionValue->getId()]['optionValueIdSource'] = $idOptionValueSource;

                // gestion des dépendances
                $this->_manageDependencyForOptionValue($optionValue, $idOptionSource, $idOptionValueSource);
            }
        }

        // récupération de la liste des pays
        $allCountries = $this->_apiGetIsoCountries();

        // si on a un probléme ou si il nous manque les variables
        if (false === $allCountries || !isset($allCountries['iso_countries'])) {
            // on quitte la fonction
            return false;
        }

        // on créé l'option et le produit option si elles n'existe pas
        $option = $this->optionService->createIfNotExist(BaseRealisaPrint::CONFIGURATION_COUNTRY, $provider->getId(), 'Pays de livraison', 200, 0, TOption::TYPE_OPTION_SELECT, TOption::SPECIAL_OPTION_DELIVERY_COUNTRY);
        $this->productOptionService->createIfNotExist($productHost->getIdProduit(), $option, $idHost, null, TAProduitOption::STATUS_ACTIF, '', '');

        // on ajoute notre option à notre tableau de retour
        $return[$option->getId()]['option'] = $option;
        $return[$option->getId()]['optionIdSource'] = FournisseurRealisaprint::CONFIGURATION_COUNTRY;

        // pour chaque pay
        foreach ($allCountries['iso_countries'] as $countryName => $idCountry) {
            // création de l'option value si elle n'existe pas
            $optionValue = $this->optionValueService->createIfNotExist($idCountry, $provider->getId(), $option, $countryName);

            // si notre option correspond à l'option par défaut
            if (BaseRealisaPrint::COUNTRY_DEFAULT_ID === $idCountry) {
                // on la met en premier pour séléctionner par défaut
                $order = 10;
            }
            // pas une option par défaut
            else {
                // ordre standard
                $order = 100;
            }

            // liaison de l'option value avec le produit
            $produitOptionValue = $this->productOptionValueService->createIfNotExist($productHost->getId(), $optionValue, $idHost, $order);

            // on ajoute notre option value à la variable de retour
            $return[$option->getId()]['optionValues'][$optionValue->getId()]['optionValue'] = $optionValue;
            $return[$option->getId()]['optionValues'][$optionValue->getId()]['optionValueIdSource'] = $idCountry;

            // les pays n'étant pas géré par l'API show variable, on les ajoute directement en tant que dépendance
            $this->getDependency()->addDependency($optionValue->getId());
        }

        // on renvoi le tableau des dépendance
        return $return;
    }

    /**
     * Recupere les prix pour les afficher
     * Insere les options/optionsValues qui n'existent pas en base.
     * @param  TProduitHost $product L'objet de produit
     * @param  array        $data    Les donnée nécessaire (provenant de la variable post)
     * @param  string       $idHost  Id du site
     * @return bool|array   un tableau avec beaucoup de trucs dedans
     */
    public function price(Provider $provider, TProduitHost $productHost, array $data, string $idHost): bool|array
    {
        $return = [];
        $aSelectionSourceForShowVariable = [];
        $aSelectionSource = [];
        $aSelection = [];
        $aSelectionText = [];
        $aSelectionTextByIdOptionSource = [];
        $IdOptionSourceQuantity = null;
        $matches = [];

        if (Provider::ID_SUPPLIER_REALISAPRINT !== $productHost->getTAProductProvider()->getProvider()->getId()) {
            // on renvoi un log d'erreur
            //            $this->getLog()->Error('Fournisseur Produit non Realisaprint');
            //            $this->getLog()->Error($product->getProduitFournisseur()->getFournisseur()->getIdFour());
            //            $this->getLog()->Error($product->getIdProduitSrc());
            //            $this->getLog()->Error(var_export($product, true));

            return false;
        }

        // on commence par vérifier que le produit est correctement configuré pour les quantité
        if (TProduct::ID_SPECIAL_QUANTITY_IN_OPTION !== $this->productHostService->getSpecialQuantity($productHost)) {
            // on modifie le produit
            $productHost->getProduct()->setSpecialQuantity(TProduct::ID_SPECIAL_QUANTITY_IN_OPTION);
            $this->entityManager->persist($productHost);
            $this->entityManager->flush();
        }

        // On commence par récupéré et mettre à jour les option et option value
        $aOptions = $this->_createAndUpdateOptionValue($productHost, $idHost);

        // si on a un soucis avec les dépendance
        if (false === $aOptions) {
            // on quitte la fonction
            return false;
        }

        // pour chaque option qu'on a récupérer
        foreach ($aOptions as $idOption => $optionData) {
            // si on a l'option des quantité
            if ($this->optionService->isQuantity($optionData['option'])) {
                // on récupére l'id option source des quantité
                $IdOptionSourceQuantity = $optionData['optionIdSource'];

                // on récupére l'id de cette option
                $idOptionQuantity = $optionData['option']->getIdOption();
            }
            // Si on n'est sur l'option des délai et qu'on a une valeur
            elseif ($this->optionService->isDelay($optionData['option']->isDelay) && isset($optionData['optionValues']) && isset($data[$idOption])) {
                // on ajoute à la selection fournisseur tout de suite car l'option n'apparait pas dans show variable
                $aSelectionSource[$optionData['optionIdSource']] = $optionData['optionValues'][$data[$idOption]]['optionValueIdSource'];

                // on ajoute à notre selection pour le retour tout de suite car l'option n'apparait pas dans show variable
                $aSelection[$optionData['optionIdSource']] = $data[$idOption];
            }
            // Si on n'est sur l'option des délai et qu'il nous manque la valeur qu'on a choisi
            elseif ($this->optionService->isDelay($optionData['option']) && isset($optionData['optionValues'])) {
                // on prend le premier délai
                $temp = array_keys($optionData['optionValues']);
                $data[$idOption] = $temp[0];

                // on ajoute à la selection fournisseur tout de suite car l'option n'apparait pas dans show variable
                $aSelectionSource[$optionData['optionIdSource']] = $optionData['optionValues'][$data[$idOption]]['optionValueIdSource'];

                // on ajoute à notre selection pour le retour tout de suite car l'option n'apparait pas dans show variable
                $aSelection[$optionData['optionIdSource']] = $data[$idOption];
            }
            // probable bug de délai de type texte
            elseif ($this->optionService->isDelay($optionData['option'])) {
                // on ajoute une erreur
                //                $this->getLog()->Erreur('Ce produit semble avoir un délai de type texte.');
                //                $this->getLog()->Erreur(var_export($product, true));
            }

            // si on a une option en lecture seule
            if (TOption::TYPE_OPTION_READONLY === $optionData['option']->getTypeOption()) {
                // on passe à l'option suivante
                continue;
            }

            // si on a cette option de type select ou checkbox et la valeur dans la selection
            if (isset($data[$idOption]) && (TOption::TYPE_OPTION_SELECT === $optionData['option']->getTypeOption() || TOption::TYPE_OPTION_CHECKBOX === $optionData['option']->getTypeOption()) && isset($optionData['optionValues'][$data[$idOption]])) {
                // on ajoute à la selection fournisseur
                $aSelectionSourceForShowVariable[$optionData['optionIdSource']] = $optionData['optionValues'][$data[$idOption]]['optionValueIdSource'];

                // on passe à l'option suivante
                continue;
            }

            //  si on a cette option de type text et la valeur dans la selection
            if (isset($data[$idOption]) && TOption::TYPE_OPTION_TEXT === $optionData['option']->getTypeOption()) {
                // ajout a la selection du fournisseur
                $aSelectionSourceForShowVariable[$optionData['optionIdSource']] = $data[$idOption];

                // on passe à l'option suivante
                continue;
            }

            // on récupére la valeur par défaut de l'option
            $defaultValue = $optionData['option']->defaultValue($productHost->getId(), $idHost);

            // si on a une option de type texte
            if (TOption::TYPE_OPTION_TEXT === $optionData['option']->getTypeOption()) {
                // ajout a la selection du fournisseur
                $aSelectionSourceForShowVariable[$optionData['optionIdSource']] = $defaultValue;

                // on passe à l'option suivante
                continue;
            }

            // si on a un probléme
            if (!isset($optionData['optionValues'][$defaultValue])) {
                //                $this->getLog()->Erreur('Erreur lors de la récupération de la valeur par défaut.');
                //                $this->getLog()->Erreur('Option : '.$idOption);
                //                $this->getLog()->Erreur('OptionValue : '.$defaultValue);
                continue;
            }

            // on ajoute à la selection fournisseur
            $aSelectionSourceForShowVariable[$optionData['optionIdSource']] = $optionData['optionValues'][$defaultValue]['optionValueIdSource'];
        }

        // récupération des variables à afficher
        $aShowVariable = $this->_apiShowVariables($productHost, $aSelectionSourceForShowVariable);

        // si on a un soucis avec la récupération des variables à afficher
        if (false === $aShowVariable) {
            // on quitte la fonction
            return false;
        }

        // si on a les valeurs des variables
        if (isset($aShowVariable['current_values'])) {
            // on sauvegarde les variables dans notre objet et pour les messages
            Message::setAVariableValues($aShowVariable['current_values']);

            // pour chaque option qu'on a récupérer
            foreach ($aOptions as $idOption => $optionData) {
                // si on est sur une option de type select ou checkbox
                if (TOption::TYPE_OPTION_SELECT === $optionData['option']->getTypeOption() || TOption::TYPE_OPTION_CHECKBOX === $optionData['option']->getTypeOption()) {
                    // on passe à la suivante
                    continue;
                }

                // ajout a la selection du fournisseur
                $aSelectionSource[$optionData['optionIdSource']] = $aShowVariable['current_values'][$optionData['optionIdSource']];

                // ajout a la selection des option de type texte
                $aSelectionTextByIdOptionSource[$optionData['optionIdSource']]['idOption'] = $idOption;
                $aSelectionTextByIdOptionSource[$optionData['optionIdSource']]['value'] = $aShowVariable['current_values'][$optionData['optionIdSource']];
            }
        }

        // pour chaque variables à afficher
        foreach ($aShowVariable as $idOptionSource => $showVariable) {
            // si on est sur la ligne qui gére les valeur des variables ou les images
            if ('variable_values' === $idOptionSource || 'current_values' === $idOptionSource || 'pictures' === $idOptionSource || 'variables_positions' === $idOptionSource || 'variables_areas' === $idOptionSource) {
                // on passe à la suivante
                continue;
            }

            // si on ne doit pas afficher la variable
            if (false === $showVariable) {
                // on la supprime de la selection fournisseur
                unset($aSelectionSource[$idOptionSource]);

                // on supprime ces options de la selection des champs texte
                unset($aSelectionTextByIdOptionSource[$idOptionSource]);

                // on passe à la suivante
                continue;
            }

            // si on n'a pas trouvé l'option correspondante
            if (false === Dependency::idOptionByIdOptionSource($idOptionSource)) {
                //                $this->getLog()->Erreur('Option introuvable dans "ShowVariable", peut-être une nouvelle fonction de l\'API');
                //                $this->getLog()->Erreur($idOptionSource);
                //                $this->getLog()->Erreur(var_export($showVariable, true));

                // on passe à la suivante
                continue;
            }

            // si on a une option de type checkbox active
            if (TOption::TYPE_OPTION_CHECKBOX === $aOptions[Dependency::idOptionByIdOptionSource($idOptionSource)]['option']->getOptTypeOption()) {
                // comme les checkbox n'apparaisse pas dans les variable_values on va les rajouter. seul les clef sont imprtantes.
                $aShowVariable['variable_values'][$idOptionSource][0] = 'non';
                $aShowVariable['variable_values'][$idOptionSource][1] = 'oui';
            }
        }

        // pour chaque option contenant des valeur de variable
        foreach ($aShowVariable['variable_values'] as $idOptionSource => $aVariableValues) {
            // si on ne dois pas afficher cette option
            if (!isset($aShowVariable[$idOptionSource]) || false === $aShowVariable[$idOptionSource]) {
                // on passe à la suivante
                continue;
            }

            // pour chaque id d'option value fournisseur disponible
            foreach (array_keys($aVariableValues) as $idOptionValueSource) {
                // on ajoute au tableau des dépendance
                $this->getDependency()->addDependencyByIdSources($idOptionSource, $idOptionValueSource);

                // récupération de nos id
                $idOption = Dependency::idOptionByIdOptionSource($idOptionSource);
                $idOptionValue = Dependency::idOptionValueByIdSources($idOptionSource, $idOptionValueSource);

                // récupération de la produit option vvalue
                $productOptionValue = $this->productOptionValueRepository->findById($productHost->getId(), $idOptionValue, $idHost);

                // si c'est une vrai valeur d'option active et si on n'a pas encore cette valeur d'option dans le selection ou si cette valeur est selectionné
                if ($idOptionValueSource <= 1000 && $productOptionValue->exist() && TAProduitOptionValue::STATUS_ACTIF === $productOptionValue->getIsActif() && (!isset($aSelection[$idOptionSource]) || isset($data[$idOption]) && $data[$idOption] === $idOptionValue)) {
                    // on ajoute à la selection fournisseur
                    $aSelectionSource[$idOptionSource] = $idOptionValueSource;

                    // on ajoute à notre selection pour le retour
                    $aSelection[$idOptionSource] = $idOptionValue;
                }
            }
        }

        // pour chaque option avec des selection texte
        foreach ($aSelectionTextByIdOptionSource as $selectionTextByIdOptionSource) {
            // on ajoute au tableau des sélection de type text
            $aSelectionText[$selectionTextByIdOptionSource['idOption']] = $selectionTextByIdOptionSource['value'];
        }

        // enregistrement de la configuration
        $configuration = $this->_apiSaveConfiguration($productHost, $aSelectionSource);

        // si on a un soucis avec la configuration
        if (false === $configuration) {
            // on quitte la fonction
            return false;
        }

        // si on a les valeurs des variables
        if (isset($configuration['current_values'])) {
            // on écrase celle qu'on avait déjà dans show variable car certaines ne sont pas à jour
            Message::setAVariableValues($configuration['current_values']);
        }

        // si on a des alerts
        if (isset($configuration['variable_alerts'])) {
            // ajout des alertes
            Message::addArrayError($this->_changeKeyIdOptionSourceToIdOption($configuration['variable_alerts']));
        }

        // si on a des infos
        if (isset($configuration['variable_infos'])) {
            // ajout des alertes
            Message::addArrayInfo($this->_changeKeyIdOptionSourceToIdOption($configuration['variable_infos']));
        }

        // si on a des descriptions
        if (isset($configuration['variable_descriptions'])) {
            // ajout des alertes
            Message::addArrayInfo($this->_changeKeyIdOptionSourceToIdOption($configuration['variable_descriptions']));
        }

        // ajout du message pour les quantité min
        $this->_informationMinMaxQuantity($configuration, $idOptionQuantity, true);

        // ajout du message pour les quantité max
        $maxQuantity = $this->_informationMinMaxQuantity($configuration, $idOptionQuantity, false);

        // si on n'a pas réussi à avoir un prix
        if (!isset($configuration['code'])) {
            // on quitte la fonction
            return false;
        }

        // gestion des délai (on rajoute une journée car la livraison est en 48h)
        $return['tabDelay'][0]['fabrication'] = $configuration['delai_fab'] + 1;
        $return['tabDelay'][0]['idDelayProduct'] = Products::ID_DELAI_STANDARD;
        $return['tabDelay'][0]['price'] = null;
        $return['idDelaySelected'] = 0;

        // gestion des séléction
        $return['selection'] = implode('-', $aSelection);
        $return['selectionText'] = json_encode($aSelectionText);

        // gestion des dépendance
        $return['dependance'] = $this->getDependency()->dependencyString();

        // gestion des quantité max
        $return['maxQuantity'] = $maxQuantity;

        // si on a récupéré les quantité dans une option de type menu déroulant
        if (isset($aSelection[$IdOptionSourceQuantity])) {
            // on récupére les quantités
            $idOptionValueQuantitySelected = $aSelection[$IdOptionSourceQuantity];

            // on récupére l'option value fournisseur
            $optionValueProviderQuantitySelected = $this->optionValueProviderRepository->findByIdProviderAndIdOptionAndIdSource($provider->getId(), $idOptionValueQuantitySelected);

            // on récupére la quantité
            $quantity = $optionValueProviderQuantitySelected->getIdSource();
        }
        // si on a récupéré les quantité dans une option de type texte
        elseif (isset($aSelectionTextByIdOptionSource[$IdOptionSourceQuantity]['value'])) {
            // on récupére les quantités
            $quantity = Tools::numberFormat2Number($aSelectionTextByIdOptionSource[$IdOptionSourceQuantity]['value']);
        }
        // pas de quantité
        else {
            // on envoi un message d'erreur
            // $this->getLog()->Erreur('Impossible de trouver la quantité.');

            // on prend une quantité par défaut
            $quantity = 100;
        }

        // si on a un pays de livraison
        if (isset($aSelectionSource[$this->CONFIGURATION_COUNTRY])) {
            // on va prendre ce pays pour la livraison
            $idCountry = $aSelectionSource[$this->CONFIGURATION_COUNTRY];
        }
        // aucun pays
        else {
            // on prend le pays par défaut
            $idCountry = $this->COUNTRY_DEFAULT_ID;
        }

        // appel de l'API de récupération du prix
        $price = $this->_apiGetPrice($configuration['code'], $quantity, $idCountry);

        // si on a un soucis avec la récupération du prix
        if (false === $price || !isset($price['price'])) {
            // on quitte la fonction
            return false;
        }

        // si on a un gabarit
        if (isset($price['template']) && preg_match($this->_pcreTemplateCodeFromeUrl(), $price['template'], $matches)) {
            // ajout du gabarit au retour
            $return['templateUrl'] = System::urlTo('produits_menu_deroulant', 'template', 'id_produit_host='.$productHost->getIdProduitHost().'&template_code='.$matches[1]);
        }
        // pas de gabarit
        else {
            $return['templateUrl'] = false;
        }

        // ajout des prix à notre retour
        $return['tabPrice'][$quantity] = ['prix' => new Prix($price['price']), 'quantite' => $quantity];
        $return['idQuantitySelected'] = $quantity;

        // sauvegarde de la selection fournisseur
        MSelectionToSelectionSupplier::createOrUpdate($productHost->getIdProduitHost(), $return['selection'].'-'.$return['idQuantitySelected'], $return['selectionText'], $idHost, $this->_supplierSelectionToJson($configuration['code'], $return['idQuantitySelected'], $configuration['files']), $this->getIdFour());

        return $return;
    }

    /**
     * change les clef d'un tableau pour mettre les id option à la place des id option source.
     * @param  array $array le tableau à modifier
     * @return array le tableau modifié
     */
    private function _changeKeyIdOptionSourceToIdOption(array $array): array
    {
        // si on a un tableau vide
        if ([] === $array) {
            // on renvoi un tableau vide car on n'a rien à faire
            return [];
        }

        $return = [];

        // pour chaque élément de notre tableau
        foreach ($array as $idOptionSource => $value) {
            // on récupére l'id option par rapport à l'id option source
            $idOption = Dependency::idOptionByIdOptionSource($idOptionSource);

            // si on a bien l'id option correspondante
            if (false !== $idOption) {
                // on prend cette id
                $newKey = $idOption;
            }
            // option inconnu
            else {
                // on renverra 0
                $newKey = 0;
            }

            // on met dans le tableau de retour avec la bonne clef
            $return[$newKey] = $value;
        }

        return $return;
    }

    /**
     * Renvoi le texte de l'information pour la quantité mini ou maxi. renvoi false si on n'a pas d'information.
     * @param  array    $configuration    le retour de l'API configuration
     * @param  int      $idOptionQuantity id de l'option des quantité
     * @param  bool     $isMin            [=true] mettre true si on veux les quantité min et false pour les quantité max
     * @return bool|int la quantité (min ou max) standard ou false si on n'a pas l'info
     */
    private function _informationMinMaxQuantity(array $configuration, int $idOptionQuantity, bool $isMin = true): bool|int
    {
        // si on est sur les quantité minimum
        if ($isMin) {
            // on indique le type de quantité
            $quantityType = 'min';
        }
        // on est sur les quantité max
        else {
            // on indique le type de quantité
            $quantityType = 'max';
        }

        // si on n'a pas les information nécessaire
        if (!isset($configuration['quantities']) || !isset($configuration['quantities'][$quantityType]) || !isset($configuration['quantities'][$quantityType][$this->DELAY_TEXT_SHORT_STANDARD]) || !is_int($configuration['quantities'][$quantityType][FournisseurRealisaprint::DELAY_TEXT_SHORT_STANDARD])) {
            // pas de message
            return false;
        }

        // récupération de la quantité minimum standard
        $quantityStandard = $configuration['quantities'][$quantityType][$this->DELAY_TEXT_SHORT_STANDARD];

        // ajout du texte
        $message = 'Quantité '.$quantityType.' : '.$quantityStandard.' exemplaire';

        // si on a plus d'un exemplaire
        if ($quantityStandard > 1) {
            // on ajoute le s
            $message .= 's';
        }

        $message .= '. ';

        // pour chaque délai
        foreach ($configuration['quantities'][$quantityType] as $idDelay => $quantity) {
            // si on est sur la quantité standard
            if ($this->DELAY_TEXT_SHORT_STANDARD === $idDelay) {
                // on passe au délai suivant car on a déjà traité
                continue;
            }

            // si on a un délai inconnu
            if ($this->DELAY_TEXT_SHORT_EXPRESS !== $idDelay && $this->DELAY_TEXT_SHORT_EMERGENCY !== $idDelay) {
                // on ajoute une erreur dans le log
                //                $this->getLog()->Erreur('id de delay inconnu dans les quantité min/max.');
                //                $this->getLog()->Erreur(var_export($idDelay, true));

                // on passe au délai suivant
                continue;
            }

            // si on n'a pas de valeur ou si c'est la même valeur que la quantité standard
            if (!is_int($quantity) || $quantity === $quantityStandard) {
                // on ne traite pas cette quantité
                continue;
            }

            // ajout du texte
            $message .= 'Pour le délai ';

            // suivant le délai on ajoute le bon texte
            switch ($idDelay) {
                case $this->DELAY_TEXT_SHORT_EXPRESS:
                    $message .= 'Express';
                    break;
                case $this->DELAY_TEXT_SHORT_EMERGENCY:
                    $message .= 'Urgence';
                    break;
                default:
                    $message .= '??????';
                    break;
            }

            // ajout du texte
            $message .= ' : '.$quantity.' exemplaire';

            // si on a plus d'un exemplaire
            if ($quantity > 1) {
                // on ajoute le s
                $message .= 's';
            }

            $message .= '. ';
        }

        // on ajoute le message d'information
        Message::addInfo($message, $idOptionQuantity);

        // on renvoi la quantité standard
        return $quantityStandard;
    }
}
