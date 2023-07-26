<?php

namespace App\Entity;

use App\Repository\TOnlineProductRuleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TOnlineProductRuleRepository::class)]
class TOnlineProductRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter pour l'attribut $onlProRulProdIndex  (JSON : liste des id produit online sur lesquelles s'applique la régle)
     * Le getteur décode le json et renvoi un tableau
     * @return varchar[]
     */
    public function getListProductOnLine()
    {
        return json_decode($this->listProductOnLine, TRUE);
    }


    /**
     * Setter pour l'attribut $onlProRulProdIndex  (JSON : liste des id produit online sur lesquelles s'applique la régle)
     * @param varchar $onlProRulProdIndex
     * @return TOnlineProductRule notre objet
     */
    public function setListProductOnLine($listProductOnLine)
    {
        $this->listProductOnLine = $listProductOnLine;

        return $this;
    }

    /**
     * Getter pour l'attribut $onlProRulHideRow  (JSON : liste des ligne à cacher)
     * Le getteur décode le json et renvoi un tableau
     * @return varchar[]
     */
    public function getListHideRow()
    {
        return json_decode($this->listHideRow, TRUE);
    }


    /**
     * Setter pour l'attribut $onlProRulHideRow  (JSON : liste des ligne à cacher)
     * @param varchar $onlProRulHideRow
     * @return TOnlineProductRule notre objet
     */
    public function setListHideRow($listHideRow)
    {
        $this->listHideRow = $listHideRow;

        return $this;
    }

    /**
     * Getter pour l'attribut $onlProRulIfEveryIsSelected  (JSON : liste des option qui doivent être séléctionné pour appliquer la régle)
     * Le getteur décode le json et renvoi un tableau
     * @return varchar[]
     */
    public function getListOptionIsSelected()
    {
        return json_decode($this->listOptionIsSelected, TRUE);
    }


    /**
     * Setter pour l'attribut $onlProRulIfEveryIsSelected  (JSON : liste des option qui doivent être séléctionné pour appliquer la régle)
     * @param varchar $onlProRulIfEveryIsSelected
     * @return TOnlineProductRule notre objet
     */
    public function setListOptionIsSelected($listOptionIsSelected)
    {
        $this->listOptionIsSelected = $listOptionIsSelected;

        return $this;
    }

//    Todo : service
    /**
     * Cré un nouvel objet "TOnlineProductRule" et le retourne
     * @param varchar[] $onlProRulProdIndex [optional=''] liste des id produit online sur lesquelles s'applique la régle en JSON
     * @param varchar[] $onlProRulHideRow [optional=''] liste des ligne à cacher en JSON
     * @param varchar[] $onlProRulIfEveryIsSelected [optional=''] liste des option qui doivent être séléctionné pour appliquer la régle en JSON
     * @return TOnlineProductRule Nouvel Objet inseré en base
     */
//    public static function createNew($onlProRulProdIndex = '', $onlProRulHideRow = '', $onlProRulIfEveryIsSelected = '')
//    {
//        $onlineProductRule = new self();
//        $onlineProductRule->setOnlProRulProdIndex($onlProRulProdIndex)
//            ->setOnlProRulHideRow($onlProRulHideRow)
//            ->setOnlProRulIfEveryIsSelected($onlProRulIfEveryIsSelected)
//            ->save();
//
//        return $onlineProductRule;
//    }


    /**
     * indique si notre régle s'applique à un produit
     * @param string $prodIndex index du produit chez le fournisseur
     * @return boolean TRUE si la régle s'applique et FALSE sinon
     */
//    public function isApplicableToProduct($prodIndex)
//    {
        // pour chaque index de produit de notre régle
//        foreach($this->getOnlProRulProdIndex() as $productRuleProdIndex)
//        {
            // si la régle s'applique à notre produit
//            if(strpos($prodIndex, $productRuleProdIndex) !== FALSE)
//            {
                // on appliquera la régle
//                return TRUE;
//            }
//        }

        // régle non applicable
//        return FALSE;
//    }

    /**
     * indique si notre régle s'applique à un produit
     * @param string $prodIndex index du produit chez le fournisseur
     * @return boolean TRUE si la régle s'applique et FALSE sinon
     */
//    public function isApplicableToProduct($prodIndex)
//    {
        // pour chaque index de produit de notre régle
//        foreach($this->getOnlProRulProdIndex() as $productRuleProdIndex)
//        {
            // si la régle s'applique à notre produit
//            if(strpos($prodIndex, $productRuleProdIndex) !== FALSE)
//            {
                // on appliquera la régle
//                return TRUE;
//            }
//        }

        // régle non applicable
//        return FALSE;
//    }

    /**
     * indique si notre régle s'applique à des options value donnée
     * @param string[] $aOptionValueOnline tableau des options value chez le fournisseur
     * @return boolean TRUE si la régle s'applique et FALSE sinon
     */
//    public function isApplicableToOptionsValues($aOptionValueOnline)
//    {
        // pour chaque id d'option value de notre régle
//        foreach($this->getOnlProRulIfEveryIsSelected() as $optionValueToSearch)
//        {
            // si cette option value n'est pas dans la liste des options value actuellement séléctionné
//            if(!$this->_optionValueInArray($optionValueToSearch, $aOptionValueOnline))
//            {
                // cette régle ne s'appliquera pas
//                return FALSE;
//            }
//        }

        // régle applicable
//        return TRUE;
//    }


    /**
     * indique si une option value à chercher se trouve dans un tableau d'option value
     * @param string $optionValueToSearch l'options value chez le fournisseur
     * @return boolean TRUE si la régle s'applique et FALSE sinon
     */
//    private function _optionValueInArray($optionValueToSearch, $aOptionValues)
//    {
        // pour chaque option value dans laquelle on doit chercher
//        foreach($aOptionValues as $optionValue)
//        {
            // si on a trouvé
//            if(strpos($optionValue, $optionValueToSearch) !== FALSE)
//            {
//                return TRUE;
//            }
//        }

        // option value non trouvé
//        return FALSE;
//    }


    /**
     * indique si notre régle doit cacher cette ligne
     * @param string $index index à vérifier
     * @return boolean TRUE si la régle doit cacher cette ligne et FALSE sinon
     */
//    public function isHiddedRow($index)
//    {
        // pour chaque ligne à cacher
//        foreach($this->getOnlProRulHideRow() as $productRuleHideRow)
//        {
            // si la régle s'applique à notre index
//            if(strpos($index, $productRuleHideRow) !== FALSE)
//            {
                // on appliquera la régle
//                return TRUE;
//            }
//        }

        // régle non applicable
//        return FALSE;
//    }
}
