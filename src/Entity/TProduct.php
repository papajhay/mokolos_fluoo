<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\TProduct\SpecialQuantityEnum;
use App\Repository\TProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TProductRepository::class)]
class TProduct
{
//    Les constants sont deplacés vers SpecialQuantityEnum
    /**
     * id pour les quantité personnalisé quand on a que les quantité standard.
     */
//     const ID_SPECIAL_QUANTITY_ONLY_STANDARD = 0;

    /**
     * id pour les quantité personnalisé quand la quantité est géré en tant qu'option standard.
     */
//    const ID_SPECIAL_QUANTITY_IN_OPTION = 3;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    /* clef primaire de la table */
    private ?int $id = null;

    /* libellé du produit */
    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /* indique si le produit autorise les format personnalisé. (utilisé par l'API p24) */
    #[ORM\Column(nullable: true)]
    private ?int $specialFormat = null;

    /* indique si le produit autorise les quantité personnalisé. (utilisé par l'API smartlabel) */

    #[ORM\Column(type:"integer")]
    private $specialQuantity = SpecialQuantityEnum::ID_SPECIAL_QUANTITY_IN_OPTION;

    #[ORM\OneToMany(mappedBy: 'tProduct', targetEntity: TAOptionProvider::class)]
    private Collection $tAOptionProviders;

    #[ORM\ManyToMany(targetEntity: TAProductOption::class, mappedBy: 'tProduct')]
    private Collection $tAProductOptions;

    #[ORM\OneToMany(mappedBy: 'tProduct', targetEntity: TAProductOptionValueProvider::class, orphanRemoval: true)]
    private Collection $tAProductOptionValueProviders;

    #[ORM\OneToMany(mappedBy: 'tProduct', targetEntity: TCombinaison::class, orphanRemoval: true)]
    private Collection $tCombinaisons;

    #[ORM\OneToOne(mappedBy: 'tProduct', cascade: ['persist', 'remove'])]
    private ?TProductHost $tProductHost = null;

    #[ORM\ManyToMany(targetEntity: TAProductProvider::class, mappedBy: 'tProduct')]
    private Collection $tAProductProviders;


    /* Id du produit auquel ce produit est rattaché */
    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'tProducts')]
    #[ORM\Column(type: "integer", nullable: true)]
//    old-name : $rattachement
    private ?self $attachement = null;

    #[ORM\OneToMany(mappedBy: 'attachement', targetEntity: self::class)]
    private Collection $tProducts;

    #[ORM\ManyToOne(targetEntity: Provider::class, inversedBy: 'tProduct')]
    private ?Provider $provider = null;

    public function __construct()
    {
        $this->tAProductOptions = new ArrayCollection();
        $this->tAOptionProviders = new ArrayCollection();
        $this->tAProductOptionValueProviders = new ArrayCollection();
        $this->tCombinaisons = new ArrayCollection();
        $this->tAProductProviders = new ArrayCollection();
        $this->tProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getSpecialQuantity(): SpecialQuantityEnum
    {
        return $this->specialQuantity;
    }

    public function setSpecialQuantity(int $specialQuantity): static
    {
        $this->specialQuantity = $specialQuantity;

        return $this;
    }

    /**
     * @return Collection<int, TAProductOption>
     */
    public function getTAProductOptions(): Collection
    {
        return $this->tAProductOptions;
    }

    public function addTAProductOption(TAProductOption $tAProductOption): static
    {
        if (!$this->tAProductOptions->contains($tAProductOption)) {
            $this->tAProductOptions->add($tAProductOption);
            $tAProductOption->setTProduct($this);
        }

        return $this;
    }

    public function removeTAProductOption(TAProductOption $tAProductOption): static
    {
        if ($this->tAProductOptions->removeElement($tAProductOption)) {
            // set the owning side to null (unless already changed)
            if ($tAProductOption->getTProduct() === $this) {
                $tAProductOption->setTProduct(null);
            }
        }

        return $this;
    }

    // TODO Repository

    /*
     * Retourne la requete sql tout les produits ordonnées par le champs ordres
     * @param string $idHhost Id du site
     * @param boolean $withInactif Est ce que l'on prend les produits inactifs?
     * @param int $satellite -1 pas de produit satelltie et pas de produit dont l'ordre est inférieur à 0 ,0 pas de produit satelltie, 1 les produit satellites sans ceux avec ordre négatif, 2 tous les produit satellites
     * @param int $idProduit id du produit d'origine valable uniquement si $satellite = true
     * @param boolean $withoutrattachment true si on veux uniquement les produit qui ne sont pas rattacher
     * @param string|null $idHostForACL Id du site si l'on souhaite vérifier les acl
     * @param bool $proHosAclInactif si on veut les produits acl actifs ou inactifs
     * @return Produit[]
     */
    /*static public function getSqlForGetAll($idHhost, $withInactif = false, $satellite = 0, $idProduit = 0, $withoutrattachment = false, $idHostForACL = null, $proHosAclInactif = false)
    {

        //Debut de la requete
        $sql = '';
        $sql .= 'SELECT ';
        $sql .= self::$_SQL_TABLE_NAME . '.*, ';

        $sqlProduiHost	 = TProduitHost::getSelectEtoileAndJoinCondition();
        $sql			 .= $sqlProduiHost['SELECT'] . ', ';

        if(!is_null($idHostForACL))
        {
            $sql .= TProduitHostAcl::$_SQL_TABLE_NAME . '.*, ';
        }

        $sql .= self::$_SQL_TABLE_NAME . '.id_produit AS id_produit_tp, ';

        $sql .= 't.id_produit AS id_produit_tph, ';

        $sql .= 't.id_produit_host AS id_produit_host_tph, ';

        $sql .= 't.id_host AS id_host_tph ';

        $sql .= 'FROM ' . self::$_SQL_TABLE_NAME . ' ';

        $sql .= 'JOIN ' . TProduitHost::$_SQL_TABLE_NAME . ' t ON ' .
            self::$_SQL_TABLE_NAME . '.id_produit = t.id_produit AND
                        (t.id_host = "' . $idHhost . '" OR t.id_host is null) ';

        $sql .= 'JOIN ' . TProduitHost::$_SQL_LOCALIZATION_TABLE_NAME . ' tl ON ' . $sqlProduiHost['JoinCondition'];

        // si on recherche les acl d'un host
        if(!is_null($idHostForACL))
        {
            $sql .= 'LEFT JOIN ' . TProduitHostAcl::$_SQL_TABLE_NAME;
            $sql .= ' ON t.id_produit_host = ' . TProduitHostAcl::$_SQL_TABLE_NAME . '.id_produit_host ';
            $sql .= ' AND ' . TProduitHostAcl::$_SQL_TABLE_NAME . '.id_host = "' . $idHostForACL . '" ';
        }

        $sql .= 'WHERE 1=1 ';

        if(!$withInactif)
        { // on prend les produits actifs
            $sql .= 'AND t.pro_hos_is_actif = 1 ';
            if(!is_null($idHostForACL) && !($proHosAclInactif))
            { // on prend les produits acl actifs
                $sql .= 'AND (' . TProduitHostAcl::$_SQL_TABLE_NAME . '.pro_hos_acl_actif = 1
                OR ' . TProduitHostAcl::$_SQL_TABLE_NAME . '.pro_hos_acl_actif IS null) ';
            }
        }

        //si on veut les produits satellites et qu'on a produit de base
        if($satellite > 0 && $idProduit <> 0)
        {
            $sql .= 'AND (t.pro_hos_satellite_id_parent = ' . $idProduit . ') ';
        }
        //si on veut les produits satellites et qu'on a pas de produit de base, on les prends tous
        elseif($satellite > 0 && $idProduit == 0)
        {
            $sql .= 'AND (t.pro_hos_satellite_id_parent <> 0) ';
        }
        else
        {
            $sql .= 'AND t.pro_hos_satellite_id_parent = 0 ';
        }

        // si on ne veux que les produits avec ordre supérieur à 0
        if($satellite == 1 || $satellite == -1)
        {
            $sql .= 'AND t.pro_hos_show_on_home_top = 1 ';
        }

        // si on veux exclure les produits rattaché
        if($withoutrattachment)
        {
            $sql .= 'AND pro_rattachement = 0 ';
            $sql .= 'AND (t.pro_hos_rattachement = 0 ';
            $sql .= 'OR t.pro_hos_rattachement IS null) ';
        }

        $sql .= ' AND tl.id_code_pays = "' . DBTable::getLangDefaut() . '"';

        $sql .= 'ORDER BY t.pro_hos_ordre, tl.pro_hos_libelle ';

        return $sql;
    }*/

    /*
     * Retourne un tableau avec toutes les options et les valeurs dispo pour celle ci en fonction de l'objet en cour
     * @param string $idHost id du site
     * @param type $dependance
     * @param bool $getOnLyActif =true veux-on uniquement les produit option value active ?
     * @param bool $standardDelivryCountry =true mettre false pour forcer le pays de livraison
     * @param bool $withFournisseurData =false mettre true pour avoir les information de fournisseur
     * @param bool $idProduitHostForVariant =null id de produit host si il s'agit d'un variant
     * @return String[]
     *
     * @category Alias la requete Magique
     */
    /*public function getOptionsAndValues($idHost, $dependance = null, $getOnLyActif = true, $standardDelivryCountry = true, $withFournisseurData = false, $idProduitHostForVariant = null)
    {
        $opt					 = array();
        $idOptionDeliveryCountry = null;

        // on commence par récupérer toutes les options de notre produit afin que les options de type texte et menu déroulant soit bien trié
        $allOption = TAProduitOption::findAllActifByIdProduitidHost($this->getIdProduit(), $idHost, true, null, $getOnLyActif);

        // pour chaque option
        foreach($allOption as $option)
        {
            // on met l'option dans notre tableau de retour afin d'avoir un minimum d'info si on a aucune option value lié a cette option
            $opt[$option->getIdOption()]['id_option']				 = $option->getIdOption();
            $opt[$option->getIdOption()]['pro_opt_libelle']			 = $option->getProOptLibelle();
            $opt[$option->getIdOption()]['pro_opt_is_actif']		 = $option->getProOptIsActif();
            $opt[$option->getIdOption()]['pro_opt_default_value']	 = $option->getProOptDefaultValue();
            $opt[$option->getIdOption()]['pro_opt_min_value']		 = $option->getProOptMinValue();
            $opt[$option->getIdOption()]['pro_opt_max_value']		 = $option->getProOptMaxValue();
            $opt[$option->getIdOption()]['id_host']					 = $option->getIdHost();
            $opt[$option->getIdOption()]['opt_type_option']			 = $option->getOption()->getOptTypeOption();
            $opt[$option->getIdOption()]['opt_ordre']				 = $option->getOption()->getOptOrdre();
            $opt[$option->getIdOption()]['opt_special_option']		 = $option->getOption()->getOptSpecialOption();

            // on prend le libellé dispo. il sera écrasé par le bon plus tard
            $opt[$option->getIdOption()]['libelleOptionOK'] = $option->getProOptLibelle();

            // si on veux les informations fournisseur
            if($withFournisseurData)
            {
                $opt[$option->getIdOption()]['lastSeenStringHtml'] = $option->lastSeenStringHtml();
            }
        }

        // toutes les tables nécessaire
        $aTable		 = array(array('table' => TOption::$_SQL_TABLE_NAME, 'alias' => 'o'));
        $aTable[]	 = array('table' => TOption::$_SQL_LOCALIZATION_TABLE_NAME, 'alias' => 'ol');
        $aTable[]	 = array('table' => TOptionValue::$_SQL_TABLE_NAME, 'alias' => 'ov');
        $aTable[]	 = array('table' => TOptionValue::$_SQL_LOCALIZATION_TABLE_NAME, 'alias' => 'ovl');
        $aTable[]	 = array('table' => TAProduitOptionValue::$_SQL_TABLE_NAME, 'alias' => 'pov');
        $aTable[]	 = array('table' => TAProduitOptionValue::$_SQL_LOCALIZATION_TABLE_NAME, 'alias' => 'povl');
        $aTable[]	 = array('table' => TAProduitOption::$_SQL_TABLE_NAME, 'alias' => 'po');
        $aTable[]	 = array('table' => TAProduitOption::$_SQL_LOCALIZATION_TABLE_NAME, 'alias' => 'pol');

        // si on a un variant
        if($idProduitHostForVariant != null)
        {
            $aTable[] = array('table' => TAVariantOptionValue::$_SQL_TABLE_NAME, 'alias' => 'vov', 'join' => 'LEFT JOIN');
        }

        // tous les champs dont on a besoin
        $champs		 = array();
        $champs[]	 = 'o.id_option';

        $champs[]	 = 'ov.id_option_value';
        $champs[]	 = 'ovl.opt_val_libelle';

        $champs[]	 = 'pov.id_produit';
        $champs[]	 = 'pov.id_option_value';
        $champs[]	 = 'pov.pro_opt_val_is_actif';
        $champs[]	 = 'pov.pro_opt_val_ordre';
        $champs[]	 = 'pov.id_host';
        $champs[]	 = 'pov.pro_opt_date_last_seen';
        $champs[]	 = 'povl.id_code_pays';
        $champs[]	 = 'povl.pro_opt_val_libelle';

        $champs[]	 = 'po.pro_opt_is_actif';
        $champs[]	 = 'pol.pro_opt_libelle';

        $champs[]	 = 'if(pol.pro_opt_libelle <> "", pol.pro_opt_libelle, ol.opt_libelle) as libelleOptionOK';
        $champs[]	 = 'if(povl.pro_opt_val_libelle <> "", povl.pro_opt_val_libelle, ovl.opt_val_libelle) as libelleOptionValueOK';

        // si on a un variant
        if($idProduitHostForVariant != null)
        {
            // on surcharge les champs nécessaire
            $proOptValIsActifField = 'if(vov.var_opt_val_is_actif IS null, pov.pro_opt_val_is_actif, vov.var_opt_val_is_actif)';
        }
        // pas de variant
        else
        {
            // champ d'origine
            $proOptValIsActifField = 'pov.pro_opt_val_is_actif';
        }

        // paramétre de la requête
        $where	 = array();
        $where[] = array('o.opt_type_option', array(TOption::TYPE_OPTION_SELECT, TOption::TYPE_OPTION_CHECKBOX), 'i');
        $where[] = array('po.id_produit', $this->getIdProduit(), 'i');
        $where[] = array('po.id_host', $idHost, 's');
        $where[] = array('pol.id_produit', $this->getIdProduit(), 'i');
        $where[] = array('pol.id_host', $idHost, 's');
        $where[] = array('pov.id_produit', $this->getIdProduit(), 'i');
        $where[] = array('pov.id_host', $idHost, 's');
        $where[] = array('povl.id_produit', $this->getIdProduit(), 'i');
        $where[] = array('povl.id_host', $idHost, 's');
        $where[] = array('ol.id_code_pays', DBTable::getLangDefaut(), 's');
        $where[] = array('povl.id_code_pays', DBTable::getLangDefaut(), 's');
        $where[] = array('ovl.id_code_pays', DBTable::getLangDefaut(), 's');
        $where[] = array('pol.id_code_pays', DBTable::getLangDefaut(), 's');

        // condition de jointure
        $joinCondition	 = array();
        $joinCondition[] = 'ol.id_option =  o.id_option';
        $joinCondition[] = 'ov.id_option = o.id_option';
        $joinCondition[] = 'ovl.id_option_value = ov.id_option_value';
        $joinCondition[] = 'pov.id_option_value = ov.id_option_value';
        $joinCondition[] = 'povl.id_option_value = pov.id_option_value';
        $joinCondition[] = 'po.id_option = o.id_option';
        $joinCondition[] = 'pol.id_option = po.id_option';

        // si on a un variant
        if($idProduitHostForVariant != null)
        {
            // on ajoute la jointure
            $joinCondition[] = 'vov.id_option_value = ov.id_option_value AND vov.id_host = pov.id_host AND vov.id_produit_host = ' . $idProduitHostForVariant;
        }

        // si on veux les informations fournisseur
        if($withFournisseurData)
        {
            // ajout de la table
            $aTable[]	 = array('table' => TAOptionValueFournisseur::$_SQL_TABLE_NAME, 'alias' => 'ovf', 'join' => 'LEFT JOIN');
            $aTable[]	 = array('table' => TAOptionFournisseur::$_SQL_TABLE_NAME, 'alias' => 'tof', 'join' => 'LEFT JOIN');

            // on ajoute les champs dans le select
            $champs[]	 = 'ovf.id_fournisseur';
            $champs[]	 = 'ovf.opt_val_fou_id_source';
            $champs[]	 = 'ovf.opt_val_fou_description';
            $champs[]	 = 'ovf.opt_val_fou_product_alias';
            $champs[]	 = 'ovf.opt_val_fou_element_id';
            $champs[]	 = 'tof.opt_fou_id_source';

            // ajout de la jointure
            $joinCondition[] = 'ovf.id_option_value = ov.id_option_value';
            $joinCondition[] = 'tof.id_option = o.id_option';

            // ajout de condition
            $where[] = array('tof.id_produit', array(0, $this->getIdProduit()), 'i', 'IN');
            $where[] = array('tof.id_fournisseur', $this->getProduitFournisseur()->getIdFournisseur(), 'i');
            $where[] = array('ovf.id_fournisseur', $this->getProduitFournisseur()->getIdFournisseur(), 'i');
        }

        // si on ne veux que les produit option value active et qu'on est sur un site maitre ou dans l'admin
        if($getOnLyActif && (System::isAdminContext() || $idHost == System::getCurrentHost()->getHostId()))
        {
            // ajout du paramétre dans la requête
            $where[] = array($proOptValIsActifField, 1, 'i', '>=');
            $where[] = array('pro_opt_is_actif', 1, 'i');
        }
        // si on ne veux que les produit option value active et qu'on n'est pas sur un site maitre
        elseif($getOnLyActif)
        {
            // ajout du paramétre dans la requête
            $where[] = array($proOptValIsActifField, 1, 'i');
            $where[] = array('pro_opt_is_actif', 1, 'i');
        }

        // si on a des dépendance
        if($dependance != null)
        {
            // si nos dépendance ne sont pas sous forme de tableau=
            if(!is_array($dependance))
            {
                // on transforme en tableau
                $dependance = explode('-', $dependance);
            }

            // ajout du paramétre dans la requête pour limitté au dépendance
            $where[] = array('ov.id_option_value', $dependance, 'i', 'IN');
        }

        // tri
        $order = array();

        // si on a un variant
        if($idProduitHostForVariant != null)
        {
            // on surcharge le tri par ordre les options values (les options sont déjà trié)
            $order[] = 'if(vov.var_opt_val_ordre IS null, pov.pro_opt_val_ordre, vov.var_opt_val_ordre)';
        }
        // pas de variant
        else
        {
            // on tri par ordre les options values (les options sont déjà trié)
            $order[] = 'pov.pro_opt_val_ordre';
        }

        // on tri par ordre alphabétiques les options values
        $order[] = 'povl.pro_opt_val_libelle';

        // execution de la requête
        $allOptValData = Db::prepareSelectAndExecuteAndFetchAll($aTable, $champs, $where, 0, $order, $joinCondition);

        // pour chaque option value
        foreach($allOptValData as $optValData)
        {
            //Syntese libelle
            $opt[$optValData['id_option']]['libelleOptionOK'] = $optValData['libelleOptionOK'];

            // Objet produit option value
            $opt[$optValData['id_option']]['paramProduitOptionValue'][$optValData['id_option_value']] = TAProduitOptionValue::loadObjectFromRowSqlInArray($optValData);

            // si on veux les informations fournisseur
            if($withFournisseurData)
            {
                // option furnisseur
                $opt[$optValData['id_option']]['opt_fou_id_source'] = $optValData['opt_fou_id_source'];

                // objet option value fournisseur
                $opt[$optValData['id_option']]['paramOptionValueFournisseur'][$optValData['id_option_value']] = TAOptionValueFournisseur::loadObjectFromRowSqlInArray($optValData);
            }

            // si on est sur l'option des pays e livraison
            if($opt[$optValData['id_option']]['opt_special_option'] == \TOption::SPECIAL_OPTION_DELIVERY_COUNTRY)
            {
                // on récupére l'id de loption
                $idOptionDeliveryCountry = $optValData['id_option'];
            }
        }

        // si on veux forcer le pays de livraison et qu'on a un pays de livraison
        if(!$standardDelivryCountry && $idOptionDeliveryCountry != null)
        {
            // on corrige le tableau des pays pour mettre en premier celui correspondant à notre site
            $opt[$idOptionDeliveryCountry]['paramProduitOptionValue'] = $this->correctCountryOrder($opt[$idOptionDeliveryCountry]['paramProduitOptionValue']);
        }

        // on ajoute les option de type texte
        return $this->getOptionsText($idHost, $withFournisseurData, $opt, $getOnLyActif);
    }*/
    /*
     * Retourne un tableau avec toutes les options de type text
     * @param string $idHost id du site
     * @param bool $withFournisseurData =false mettre true pour avoir les information de fournisseur
     * @param array $opt le tableau des options auxquelles ont va ajouter nos options text
     * @param bool $getOnLyActif =true veux-on uniquement les produit option value active ?
     * @return String[]
     *
     * @category Alias la requete Magique 2
     */
    /*public function getOptionsText($idHost, $withFournisseurData = false, $opt = array(), $getOnLyActif = true)
    {
        // toutes les tables nécessaire
        $aTable		 = array(array('table' => TOption::$_SQL_TABLE_NAME, 'alias' => 'o'));
        $aTable[]	 = array('table' => TOption::$_SQL_LOCALIZATION_TABLE_NAME, 'alias' => 'ol');
        $aTable[]	 = array('table' => TAProduitOption::$_SQL_TABLE_NAME, 'alias' => 'po');
        $aTable[]	 = array('table' => TAProduitOption::$_SQL_LOCALIZATION_TABLE_NAME, 'alias' => 'pol');

        // tous les champs dont on a besoin
        $champs		 = array();
        $champs[]	 = 'o.id_option';
        $champs[]	 = 'o.opt_type_option';
        $champs[]	 = 'o.opt_ordre';
        $champs[]	 = 'o.opt_special_option';

        $champs[]	 = 'po.pro_opt_is_actif';
        $champs[]	 = 'po.pro_opt_default_value';
        $champs[]	 = 'po.id_host';
        $champs[]	 = 'pol.pro_opt_libelle';

        $champs[] = 'if(pol.pro_opt_libelle <> "", pol.pro_opt_libelle, ol.opt_libelle) as libelleOptionOK';

        // paramétre de la requête
        $where	 = array();
        $where[] = array('o.opt_type_option', array(TOption::TYPE_OPTION_TEXT, TOption::TYPE_OPTION_READONLY), 'i');
        $where[] = array('po.id_produit', $this->getIdProduit(), 'i');
        $where[] = array('po.id_host', $idHost, 's');
        $where[] = array('pol.id_produit', $this->getIdProduit(), 'i');
        $where[] = array('pol.id_host', $idHost, 's');
        $where[] = array('ol.id_code_pays', DBTable::getLangDefaut(), 's');
        $where[] = array('pol.id_code_pays', DBTable::getLangDefaut(), 's');

        // condition de jointure
        $joinCondition	 = array();
        $joinCondition[] = 'ol.id_option =  o.id_option';
        $joinCondition[] = 'po.id_option = o.id_option';
        $joinCondition[] = 'pol.id_option = po.id_option';

        // si on veux les informations fournisseur
        if($withFournisseurData)
        {
            // ajout de la table
            $aTable[] = array('table' => TAOptionFournisseur::$_SQL_TABLE_NAME, 'alias' => 'tof', 'join' => 'LEFT JOIN');

            // on ajoute les champs dans le select
            $champs[] = 'tof.opt_fou_id_source';

            // ajout de la jointure
            $joinCondition[] = 'tof.id_option = o.id_option';

            // ajout de condition
            $where[] = array('tof.id_produit', array(0, $this->getIdProduit()), 'i', 'IN');
        }

        // si on ne veux que les options actives
        if($getOnLyActif)
        {
            // on ajoute la condition
            $where[] = array('po.pro_opt_is_actif', TAProduitOption::STATUS_ACTIF, 's');
        }

        // aucun tri
        $order = array();

        // execution de la requête
        $allOptData = Db::prepareSelectAndExecuteAndFetchAll($aTable, $champs, $where, 0, $order, $joinCondition);

        // pour chaque option value
        foreach($allOptData as $optData)
        {
            //Contenu de la table t_option
            $opt[$optData['id_option']]['opt_type_option']		 = $optData['opt_type_option'];
            $opt[$optData['id_option']]['opt_ordre']			 = $optData['opt_ordre'];
            $opt[$optData['id_option']]['opt_special_option']	 = $optData['opt_special_option'];

            //Contenu de la table ta_produit_option
            $opt[$optData['id_option']]['id_option']			 = $optData['id_option'];
            $opt[$optData['id_option']]['pro_opt_libelle']		 = $optData['pro_opt_libelle'];
            $opt[$optData['id_option']]['pro_opt_is_actif']		 = $optData['pro_opt_is_actif'];
            $opt[$optData['id_option']]['pro_opt_default_value'] = $optData['pro_opt_default_value'];
            $opt[$optData['id_option']]['id_host']				 = $optData['id_host'];

            //Syntese libelle
            $opt[$optData['id_option']]['libelleOptionOK'] = $optData['libelleOptionOK'];

            // si on veux les informations fournisseur
            if($withFournisseurData)
            {
                // option furnisseur
                $opt[$optData['id_option']]['opt_fou_id_source'] = $optData['opt_fou_id_source'];
            }
        }

        return $opt;
    }*/

    /*
     * Retourne une selection par defaut pour le produit en cour (une liste d'id value avec les clée print 24)
     * @param string $idHost id du site
     * @param bool $idProduitHostForVariant =null id de produit host si il s'agit d'un variant
     * @return String : idP24=idOptionValue-.....
     */
    /*public function getDefautSelection($idHost, $idProduitHostForVariant = null)
    {
        $return = array();

        // toutes les tables nécessaire
        $aTable		 = array(array('table' => TOption::$_SQL_TABLE_NAME, 'alias' => 'o'));
        $aTable[]	 = array('table' => TOptionValue::$_SQL_TABLE_NAME, 'alias' => 'ov');
        $aTable[]	 = array('table' => TOptionValue::$_SQL_LOCALIZATION_TABLE_NAME, 'alias' => 'ovl');
        $aTable[]	 = array('table' => TAProduitOptionValue::$_SQL_TABLE_NAME, 'alias' => 'pov');
        $aTable[]	 = array('table' => TAProduitOptionValue::$_SQL_LOCALIZATION_TABLE_NAME, 'alias' => 'povl');
        $aTable[]	 = array('table' => TAProduitOption::$_SQL_TABLE_NAME, 'alias' => 'po');

        // si on a un variant
        if($idProduitHostForVariant != null)
        {
            $aTable[] = array('table' => TAVariantOptionValue::$_SQL_TABLE_NAME, 'alias' => 'vov', 'join' => 'LEFT JOIN');
        }

        // tous les champs dont on a besoin
        $champs		 = array();
        $champs[]	 = 'o.id_option';

        $champs[] = 'ov.id_option_value';

        $champs[] = 'if(povl.pro_opt_val_libelle <> "", povl.pro_opt_val_libelle, ovl.opt_val_libelle) as libelleOptionValueOK';

        // si on a un variant
        if($idProduitHostForVariant != null)
        {
            // on surcharge les champs nécessaire
            $proOptValIsActifField = 'if(vov.var_opt_val_is_actif IS null, pov.pro_opt_val_is_actif, vov.var_opt_val_is_actif)';
        }
        // pas de variant
        else
        {
            // champ d'origine
            $proOptValIsActifField = 'pov.pro_opt_val_is_actif';
        }

        // paramétre de la requête
        $where	 = array();
        $where[] = array('o.opt_type_option', array(TOption::TYPE_OPTION_SELECT, TOption::TYPE_OPTION_CHECKBOX), 'i');
        $where[] = array('po.id_produit', $this->getIdProduit(), 'i');
        $where[] = array('po.id_host', $idHost, 's');
        $where[] = array('pov.id_produit', $this->getIdProduit(), 'i');
        $where[] = array('pov.id_host', $idHost, 's');
        $where[] = array('povl.id_produit', $this->getIdProduit(), 'i');
        $where[] = array('povl.id_host', $idHost, 's');
        $where[] = array('povl.id_code_pays', DBTable::getLangDefaut(), 's');
        $where[] = array('ovl.id_code_pays', DBTable::getLangDefaut(), 's');

        // si on a un variant
        if($idProduitHostForVariant != null)
        {
            // on ajoute les paramétres
            $where[] = array('vov.id_produit_host', array(null, $idProduitHostForVariant), 'i');
        }

        // ajout du paramétre dans la requête
        $where[] = array($proOptValIsActifField, 1, 'i', '>=');
        $where[] = array('pro_opt_is_actif', 1, 'i');

        // condition de jointure
        $joinCondition	 = array();
        $joinCondition[] = 'ov.id_option = o.id_option';
        $joinCondition[] = 'ovl.id_option_value = ov.id_option_value';
        $joinCondition[] = 'pov.id_option_value = ov.id_option_value';
        $joinCondition[] = 'povl.id_option_value = pov.id_option_value';
        $joinCondition[] = 'po.id_option = o.id_option';

        // si on a un variant
        if($idProduitHostForVariant != null)
        {
            // on ajoute la jointure
            $joinCondition[] = 'vov.id_option_value = ov.id_option_value AND vov.id_host = pov.id_host';
        }

        // tri
        $order = array();

        // si on a un variant
        if($idProduitHostForVariant != null)
        {
            // on surcharge le tri par ordre les options values (les options sont déjà trié)
            $order[] = 'if(vov.var_opt_val_ordre IS null, pov.pro_opt_val_ordre, vov.var_opt_val_ordre)';
        }
        // pas de variant
        else
        {
            // on tri par ordre les options values (les options sont déjà trié)
            $order[] = 'pov.pro_opt_val_ordre';
        }

        // on tri par ordre alphabétiques les options values
        $order[] = 'povl.pro_opt_val_libelle';

        // execution de la requête
        $allOptValData = Db::prepareSelectAndExecuteAndFetchAll($aTable, $champs, $where, 0, $order, $joinCondition);

        // pour chaque valeur d'option
        foreach($allOptValData as $optValData)
        {
            // si on n'a pas encore récupéré la valeur de cette option
            if(!isset($return[$optValData['id_option']]))
            {
                // on a notre valeur par défaut
                $return[$optValData['id_option']] = $optValData['id_option_value'];
            }
        }

        return implode('-', $return);
    }*/

    /*
     * Retourne le produit via un id chez le fournisseur et un fournisseur
     * @param string $supplierId id chez le fournisseur
     * @param int $idFour id du fournisseur
     * @return TProduit|null le produit ou null si rien ne correspond
     */
    /*static public function findBySupplierId($supplierId, $idFour)
    {
        // paramétre de la requête
        $aFields	 = array('id_fournisseur', 'pro_fou_id_source');
        $aValue		 = array($idFour, $supplierId);
        $order		 = array('id_produit');
        $joinList	 = array(array('table' => TAProduitFournisseur::$_SQL_TABLE_NAME, 'alias' => 'pf', 'joinCondition' => 't.id_produit = pf.id_produit'));

        // on recherche notre produit
        $allProduct = TProduit::findAllBy($aFields, $aValue, $order, 1, $joinList);

        // si on n'a rien trouvé
        if(count($allProduct) < 1)
        {
            return null;
        }

        // on renvoi le produit
        return array_values($allProduct)[0];
    }*/

    /*
     * renvoi la liste des produits qui posséde des fiches techniques
     * @return TProduit[]
     */
    /*static public function findAllWithFicheTech()
    {
        $sql = 'SELECT p.*
            FROM ' . self::$_SQL_TABLE_NAME . ' p
            JOIN ' . TFicheTechnique::$_SQL_TABLE_NAME . ' ft
            ON p.id_produit = ft.id_produit
            GROUP BY p.id_produit
            ORDER BY pro_libelle';

        return TProduit::findAllSql($sql, true);
    }*/

    /*
     * renvoi la liste des produits qui ne posséde pas de fiche technique
     * @return TProduit[]
     */
    /*static public function findAllWithoutFicheTech()
    {
        $sql = 'SELECT *
            FROM ' . self::$_SQL_TABLE_NAME . ' p
            WHERE p.id_produit NOT IN (
                SELECT id_produit
                FROM ' . TFicheTechnique::$_SQL_TABLE_NAME . ' ft
                GROUP BY ft.id_produit)
            ORDER BY pro_libelle';

        return TProduit::findAllSql($sql, true);
    }*/

    /*
     * renvoi la liste des produits de Produit Option Value selon une Site Hoste
     * @return TProduit[]
     */
    /*static public function findAllProductByHost($idHost)
    {
        $sql = 'SELECT p.*
            FROM ' . self::$_SQL_TABLE_NAME . ' p
            JOIN ' . TAProduitOptionValue::$_SQL_TABLE_NAME . ' pov ON p.id_produit = pov.id_produit
            WHERE pov.id_host =  "' . $idHost . '"
            GROUP BY p.id_produit
            ORDER BY pro_libelle';

        $req = DB::req($sql);

        $rep = array(
        );

        while($r = $req->fetch_assoc())
        {
            $o		 = new TProduit;
            $o->loadByArray($r);
            $rep[]	 = $o;
        }

        return $rep;
    }*/

    /*
     * renvoi la liste des produits de Produit Option selon une Site Hoste
     * @return TProduit[]
     */
    /*static public function findAllProductFromProduitOptionByHost($idHost)
    {
        $sql = 'SELECT p.*
            FROM ' . self::$_SQL_TABLE_NAME . ' p
            JOIN ' . TAProduitOption::$_SQL_TABLE_NAME . ' ov ON p.id_produit = ov.id_produit
            WHERE  	ov.id_host =  "' . $idHost . '"
            GROUP BY p.pro_libelle';

        $req = DB::req($sql);

        $rep = array();

        while($r = $req->fetch_assoc())
        {
            $o		 = new TProduit;
            $o->loadByArray($r);
            $rep[]	 = $o;
        }

        return $rep;
    }*/

    /*
     * supprime tous les vieux produits qui ne sont plus relié à aucun produit host
     * @param TLockProcess $lockProcess		Objet lockProcess pour les etapes
     */
    /*static public function purge(TLockProcess $lockProcess)
    {
        $lockProcess->updateStage('Recherche des produit sans produit host');

        // récupération des produit ne correspondant plus à rien
        $sql = 'SELECT *
            FROM ' . self::$_SQL_TABLE_NAME . '
            WHERE id_produit NOT IN (
            SELECT id_produit
            FROM ' . TProduitHost::$_SQL_TABLE_NAME . '
            GROUP BY id_produit)
            AND id_produit NOT IN (
            SELECT pro_hos_rattachement
            FROM ' . TProduitHost::$_SQL_TABLE_NAME . '
            GROUP BY pro_hos_rattachement)
            AND id_produit NOT IN (
            SELECT pro_rattachement
            FROM ' . self::$_SQL_TABLE_NAME . '
            GROUP BY pro_rattachement)';

        // pour chaque produit
        foreach(self::findAllSql($sql) AS $produit)
        {
            $lockProcess->updateStage('Suppression produt ' . $produit->getIdProduit());

            // on supprime le produit
            $produit->delete();
        }
    }*/

    // todo Service
    /*
     *  renvoi un objet TAProduitFournisseur correspondant à un fournisseur de ce produit
     * @return TAProduitFournisseur
     */
    /*public function getProduitFournisseur()
    {
        // si on a pas encore récupéré le produitFournisseur
        if(!isset($this->_produitFournisseur))
        {
            // on recherche le produit fournisseur source
            $this->_produitFournisseur = TAProduitFournisseur::findByIdProduit($this->getIdProduit());
        }

        return $this->_produitFournisseur;
    }*/

    /*
     * Retourne l'id fournisseur du produit en cour
     *
     * @return int
     */
    /*public function getIdProduitSrc()
    {
        // on recherche le produit fournisseur source
        return $this->getProduitFournisseur()->getProFouIdSource();
    }*/

    /*
     * Retourne l'id Group du fournisseur du produit en cour
     *
     * @return int
     */
    /*public function getIdProduitSrcGroup()
    {
        // on recherche le produit fournisseur source
        return $this->getProduitFournisseur()->getProFouIdGroup();
    }*/

    /*
     * renvoi le nom du fournisseur ou meta produit
     * @return string
     */
    /*public function getNomFourMeta()
    {
        // le produit à un fournisseur
        if($this->getProduitFournisseur() !== null)
        {
            return $this->getProduitFournisseur()->getFournisseur()->getNomFour();
        }
        // le produit n'a pas de fournisseur
        else
        {
            return 'Aucun';
        }
    }*/

    /*
     * Lors de la duplication d'un objet, on detruit son id
     */
    /*function __clone()
    {
        $this->idProduit	 = null;
        // TRES IMPORTANT cela permet de réinitialiser la clef primaire de dbtable pour ne pas avoir une clef à 0
        $this->_primaryValue = array();
    }*/

    /*
     * renvoi le zip contenant les gabarit de ce produit par rapport à la selection ou renvoi null si elle n'xiste pas
     * @param string $selection la selection
     * @param string $idHost id du site pour les urls
     * @return string url de la fiche Technique ou null si on n'a pas de réponse
     */
    /*public function chercheGabarits($selection, $idHost)
    {
        return $this->_chercheFicheTechEtMaquette($selection, 'gabarits', $idHost, 'zip');
    }*/

    /*
     * renvoi la fiche Technique de ce produit par rapport à la selection ou renvoi null si elle n'xiste pas
     * @param string $selection la selection
     * @param string $idHost id du site pour les urls
     * @return string url de la fiche Technique ou null si on n'a pas de réponse
     */
    /*public function chercheFicheTech($selection, $idHost)
    {
        return $this->_chercheFicheTechEtMaquette($selection, 'fiches_techniques', $idHost, 'jpg');
    }*/

    /*
     * renvoi la maquette de ce produit par rapport à la selection ou renvoi null si elle n'xiste pas
     * @param string $selection la selection
     * @param string $idHost id du site pour les urls
     * @return string url de la maquette ou null si on n'a pas de réponse
     */
    /*public function chercheMaquette($selection, $idHost)
    {
        return $this->_chercheFicheTechEtMaquette($selection, 'maquettes', $idHost, 'jpg');
    }*/

    /*
     * renvoi la fiche technique ou la maquette de ce produit par rapport à la selection ou renvoi null si elle n'xiste pas
     * @param string $selection la selection
     * @param string $type fichTech pour la fiche technique et maquette pour la maquette
     * @param string $idHost id du site pour les urls
     * @param string $extention extention du fichier
     * @return string nom du fichier de la fiche technique ou null si on n'a pas de réponse
     */
    /*private function _chercheFicheTechEtMaquette($selection, $type, $idHost, $extention = 'jpg')
    {
        $fichier		 = null;
        $fichierExist	 = false;

        // preimérement on recherche la fiche technique de ce produit
        $ficheTech = $this->chercheFicheTechEtMaquetteAvecIdProduit($selection, $this->getIdProduit(), $extention);

        // si on a quelque chose dans la base on crée l'adresse de l'image
        if($ficheTech != null)
        {
            // on récupére l'adresse de l'image
            $fichier		 = 'assets/data/techniques/' . $type . '/' . $ficheTech;
            $fichierExist	 = true;
        }

        // si le fichier n'existe pas physiquement
        if($fichierExist == true && !is_file('/home/limprime/' . SUB_DOMAIN . '/' . trim($fichier)))
        {
            // en faite le fichier n'existe pas
            $fichierExist = false;
        }

        // si on ne trouve pas le fichier et si le produit est rattaché à un autre produit
        if($fichierExist == false && $this->getProRattachement() != 0)
        {
            // on va chercher pour le produit rattaché
            $ficheTech = $this->chercheFicheTechEtMaquetteAvecIdProduit($selection, $this->getProRattachement(), $extention);

            // si on a quelque chose dans la base
            if($ficheTech != null)
            {
                // on récupére l'adresse de l'image
                $fichier		 = 'assets/data/techniques/' . $type . '/' . $ficheTech;
                $fichierExist	 = true;
            }

            // si le fichier n'existe pas physiquement
            if($fichierExist == true && !is_file(trim($fichier)))
            {
                // en faite le fichier n'existe pas
                $fichierExist = false;
            }
        }

        // si on a bien trouvé un fichier
        if($fichierExist == true)
        {
            // on renvoi l'url du fichier
            $fichier = System::constructHttpServerFromHost($idHost) . $fichier;
        }
        else
        {
            // on renverras null
            $fichier = null;
        }

        // on renvoi la réponse
        return $fichier;
    }*/

    /*
     * renvoi la fiche technique de ce produit par rapport à la selection ou renvoi null si elle n'xiste pas
     * @param string $selection la selection
     * @param int $idProduit id du produit dont on cherche la fiche technique
     * @param string $extention extention du fichier
     * @return string nom du fichier de la fiche technique ou null si on n'a pas de réponse
     */
    /*private function chercheFicheTechEtMaquetteAvecIdProduit($selection, $idProduit, $extention)
    {
        // si l'id de produit n'est pas un nombre on renvoi null
        if(!is_numeric($idProduit))
        {
            return null;
        }

        // on transforme la selection en tableau
        $tabSelection = array_flip(explode('-', $selection));

        // on initialise notre variable qui va récupéré le niveau inférieur de l'abre
        $niveauInferieur = 0;
        // on va faire une boucle pour chaque niveau de notre arbre des fiche technique
        // on boucle un maximum de 10 fois dans ce cas la on aura un problème
        for($i = 1; $i < 10; $i++)
        {
            // initialisation du tableau qui va contenir les choix possible de ce nieau de l'arbre
            $tabFicheTech = array();

            // récupération des fiche technique
            $allFicheTech = TFicheTechnique::findAllByParentAndProduct($niveauInferieur, $idProduit);

            // pour chaque fiche technique
            foreach($allFicheTech AS $ficheTech)
            {
                // construction du tableau des options
                $tabFicheTech[$ficheTech->getIdOptionValue()]['ficheTech']	 = $ficheTech->getFicTecDescription();
                $tabFicheTech[$ficheTech->getIdOptionValue()]['id']			 = $ficheTech->getIdFicheTechnique();
            }

            // on récupére l'intersection entre notre tabeleau des options et les valeurs des selection passé en clef avec array_flip
            $arbreFicheTech = array_intersect_key($tabFicheTech, $tabSelection);

            // en théorie on a un seul résultat si ce n'est pas le cas on a probablement pas la fiche tecnique
            if(count($arbreFicheTech) <> 1)
            {
                // on retourne null
                return null;
            }

            // récupération de la clef du tableau
            $temp	 = array_keys($arbreFicheTech);
            $key	 = $temp[0];

            // si on a trouvé une fiche technique
            if($arbreFicheTech[$key]['ficheTech'] <> '')
            {
                // on retourne la fiche technique en modifiant l'extention
                return str_replace('.jpg', '.' . $extention, $arbreFicheTech[$key]['ficheTech']);
            }
            // si on a pas encore trouvé on va allez un niveau plus loin dans l'arbre
            else
            {
                $niveauInferieur = $arbreFicheTech[$key]['id'];
            }
        }
        return null;
    }*/

    /*
     * force le pays de livraison en ne renvoyant que celui de notre site
     * @param array $optionCountry le tableau des pays de livraison
     * @return array le tableau réordonné
     */
    /*private function correctCountryOrder($optionCountry)
    {
        $return = array();

        // pour chaque pays de livraison
        foreach($optionCountry AS $idOption => $paysLivraison)
        {
            // si on a le bon pays
            if(strtolower($paysLivraison->getProOptValLibelle()) == strtolower(System::getCurrentHost()->getPays()))
            {
                // on met le bon pays dans notre tableau de retour
                $return[$idOption] = $optionCountry[$idOption];
                return $return;
            }
        }

        // on renvoi le tableau sans modification
        return $optionCountry;
    }*/

    /*
     * Ajoute les valeur par défaut des options de type texte à un tableau
     * @param string $idHost id du site
     * @param array $return =array() Le tableau auquel on veux ajouter nos valeurs
     * @return array
     */
    /*public function textOptionDefaultValue($idHost, $return = array())
    {
        // récupération de toutes les options de type texte de notre produit
        $aProductOptionText = TAProduitOption::findAllActifByIdProduitidHost($this->getIdProduit(), $idHost, false, TOption::TYPE_OPTION_TEXT);

        // pour chaque option
        foreach($aProductOptionText as $productOptionText)
        {
            // on ajoute la valeur par défaut a notre tableau de retour
            $return[$productOptionText->getIdOption()] = $productOptionText->getProOptDefaultValue();
        }

        return $return;
    }*/

    /**
     * @return Collection<int, TAProductOptionValueProvider>
     */
    public function getTAProductOptionValueProviders(): Collection
    {
        return $this->tAProductOptionValueProviders;
    }

    public function addTAProductOptionValueProvider(TAProductOptionValueProvider $tAProductOptionValueProvider): static
    {
        if (!$this->tAProductOptionValueProviders->contains($tAProductOptionValueProvider)) {
            $this->tAProductOptionValueProviders->add($tAProductOptionValueProvider);
            $tAProductOptionValueProvider->setTProduct($this);
        }

        return $this;
    }

    public function removeTAProductOptionValueProvider(TAProductOptionValueProvider $tAProductOptionValueProvider): static
    {
        if ($this->tAProductOptionValueProviders->removeElement($tAProductOptionValueProvider)) {
            // set the owning side to null (unless already changed)
            if ($tAProductOptionValueProvider->getTProduct() === $this) {
                $tAProductOptionValueProvider->setTProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TCombinaison>
     */
    public function getTCombinaisons(): Collection
    {
        return $this->tCombinaisons;
    }

    public function addTCombinaison(TCombinaison $tCombinaison): static
    {
        if (!$this->tCombinaisons->contains($tCombinaison)) {
            $this->tCombinaisons->add($tCombinaison);
            $tCombinaison->setTProduct($this);
        }

        return $this;
    }

    public function removeTCombinaison(TCombinaison $tCombinaison): static
    {
        if ($this->tCombinaisons->removeElement($tCombinaison)) {
            // set the owning side to null (unless already changed)
            if ($tCombinaison->getTProduct() === $this) {
                $tCombinaison->setTProduct(null);
            }
        }

        return $this;
    }

    public function getTProductHost(): ?TProductHost
    {
        return $this->tProductHost;
    }

    public function setTProductHost(TProductHost $tProductHost): static
    {
        // set the owning side of the relation if necessary
        if ($tProductHost->getTProduct() !== $this) {
            $tProductHost->setTProduct($this);
        }

        $this->tProductHost = $tProductHost;

        return $this;
    }

    public function getSpecialFormat(): ?int
    {
        return $this->specialFormat;
    }

    public function setSpecialFormat(?int $specialFormat): void
    {
        $this->specialFormat = $specialFormat;
    }

    public function getAttachement(): ?self
    {
        return $this->attachement;
    }

    public function setAttachement(?self $attachement): static
    {
        $this->attachement = $attachement;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getTProducts(): Collection
    {
        return $this->tProducts;
    }

    public function addTProduct(self $tProduct): static
    {
        if (!$this->tProducts->contains($tProduct)) {
            $this->tProducts->add($tProduct);
            $tProduct->setAttachement($this);
        }

        return $this;
    }

    public function removeTProduct(self $tProduct): static
    {
        if ($this->tProducts->removeElement($tProduct)) {
            // set the owning side to null (unless already changed)
            if ($tProduct->getAttachement() === $this) {
                $tProduct->setAttachement(null);
            }
        }

        return $this;
    }

    public function getTAOptionProviders(): Collection
    {
        return $this->tAOptionProviders;
    }

    public function setTAOptionProviders(Collection $tAOptionProviders): void
    {
        $this->tAOptionProviders = $tAOptionProviders;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    public function getTAProductProviders(): Collection
    {
        return $this->tAProductProviders;
    }

    public function setTAProductProviders(Collection $tAProductProviders): void
    {
        $this->tAProductProviders = $tAProductProviders;
    }

}
