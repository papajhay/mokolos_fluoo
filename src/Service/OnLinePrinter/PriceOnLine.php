<?php declare(strict_types=1);

namespace App\Service\OnLinePrinter;

use App\Entity\Hosts;
use App\Entity\TOptionValue;
use App\Entity\TProduct;
use App\Repository\ProviderRepository;
use App\Repository\TOptionRepository;
use App\Repository\TOptionValueRepository;
use App\Service\TAProductOptionService;
use App\Service\TOptionService;

class PriceOnLine extends ProviderOnLine
{
    public function __construct(
        ProviderRepository $providerRepository,
        TOptionService $toptionService,
        TAProductOptionService $tAProductOptionService,
        private TOptionRepository $tOptionRepository,
        private TOptionValueRepository $toptionValueRepository,)
    {
        parent::__construct($providerRepository, $toptionService, $tAProductOptionService);
    }
    /**
     * Recupere les prix pour les afficher
     * Insere les options/optionsValues qui n'existent pas en base
     * @param TProduct $product L'objet de produit
     * @param array $data Les donnée nécessaire (provenant de la variable post)
     * @param Hosts $host Id du site
     * @return array un tableau avec beaucoup de trucs dedans
     */
//    old: extractPrix
    public function extractPrice(TProduct $product, array $data, Hosts $host): array
    {
        $this->_dataPost = $data;

        // on reinitialise le curl
//        $this->initCurl();

        // on récupére la selection ordonné
        $tabSelectionUsers = $this->generateOrdenedSelectionByPostData($data);

        return $this->extractDelayOrPrice($product, $tabSelectionUsers, $host);
    }

    /**
     * renvoi une selection ordonné sous forme de tableau à partir de donnée provenant du post du menu déroulant
     * @param array $data le tableau provenant du post
     * @return array le tableau de la selection dans le bon ordre
     */
    public function generateOrdenedSelectionByPostData(array $data): array
    {
        $orderSelection	 = [];
        $return			 = [];

        // on va traité chaque ligne d'option
        foreach($data AS $idOption => $idOptionValue)
        {
            // si il s'agit des délais ou des quantités
            if($idOption == 'idDelay' || $idOption == 'idQuantity')
            {
                // on récupére l'option value
                $optionValue = $this->toptionValueRepository->find([$idOptionValue]);

                // on met l'id option value dans notre tableau à ordonner.
                // on utilise 2 niveau pour le cas ou plusieur option aurais le même ordre
                $orderSelection[$optionValue->getTOption()->getOptionOrder()][$idOption] = $idOptionValue;

                // on passe à la donnée suivante
                continue;
            }

            // si il ne s'agit pas d'une option
            if(!is_numeric($idOption))
            {
                // on passe à la donnée suivante
                continue;
            }

            // on récupére l'option correspondante
            $option = $this->tOptionRepository->find($idOption);;

            // si on a bien récupéré l'option et qu'elle a un ordre
            if($option->getOptionOrder() !== NULL)
            {
                // on met l'id option value dans notre tableau à ordonner.
                // on utilise 2 niveau pour le cas ou plusieur option aurais le même ordre
                $orderSelection[$option->getOptionOrder()][$idOption] = $idOptionValue;
            }
        }

        // on parcour notre tableau à ordonner
        foreach($orderSelection AS $orderSelection1)
        {
            // pour chaque option de cette ordre
            foreach($orderSelection1 AS $idOption => $idOptionValue)
            {
                // on ajoute à notre tableau de retour
                $return[$idOption] = $idOptionValue;
            }
        }

        return $return;
    }

    /**
     * Methode commune a extractDelaiPrix et extractPrix
     * @param TProduct $product				Produit
     * @param array $tabSelectionUsers	Tableau de la selction
     * @param Hosts $host					Id du site
     * @return type
     */
//    extractDelaiOuPrix
    public function extractDelayOrPrice(TProduct $product, array $tabSelectionUsers, Hosts $host): array
    {
        // initialisation des tableaux qui contiendront les dépendance et la selection
        $tabDependance = [];

        // récupération du code dde la page
        $idOptionValueOnline = $product->getProduitFournisseur()->getProFouIdSource();

        // récupération de la premiére catégorie
//        $tabOptionValueDerniereCategorie = $this->recuperationCategorie($product, $idOptionValueOnline, $host, $tabDependance, 2);

        // pour chaque élément de la séléction
        foreach($tabSelectionUsers AS $idOptionValue)
        {
            // si on a rien récupéré c'est qu'il s'agit probablement de la page des produit
            if(empty($tabOptionValueDerniereCategorie))
            {
                // récupération de la page online pour le produit
                return $this->recuperationProduit($product, $idOptionValueOnline, $host, $tabDependance, $tabSelectionUsers);
            }

            // on récupére l'option value
            $optionValue = TOptionValue::findById($idOptionValue);

            // on corrige l'option value si besoin
            $OptionValueOk = $this->checkOptionValueValide($optionValue, $tabDependance, $tabOptionValueDerniereCategorie);

            // on est en train de traité les premiéres pages (picto et tableaux
            if($OptionValueOk->getOption()->getOptOrdre() < 2000)
            {
                // on récupére l'id option value du fournisseur
                $idOptionValueOnline = $OptionValueOk->getIdOptionValueSrc($this->getIdFour());

                // on rajoute cette option value dans la selection
                $this->tabSelection[] = $OptionValueOk->getIdOptionValue();
                $this->_addAProductSelection($OptionValueOk->getIdOptionValue());

                // récupération de la page online pour les catégories
//                $tabOptionValueDerniereCategorie = $this->recuperationCategorie($product, $idOptionValueOnline, $host, $tabDependance, 3);
            }
        }

        // récupération de la page online des produit si on est arrivé à la fin de la boucle ce qui arrive quand on a un nouveau produit
        return $this->recuperationProduit($product, $idOptionValueOnline, $host, $tabDependance, $tabSelectionUsers);
    }

}