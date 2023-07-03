<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAProductOptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAProductOptionRepository::class)]
class TAProductOption
{
     /*
	 * *************************************************************************
	 * CONSTANT
	 * **************************************************************************
	 */

    /**
	 * Statut de cette valeur d'option : inactif
	 */
	// const STATUS_INACTIF = 0;

	/**
	 * Statut de cette valeur d'option : actif
	 */
	// const STATUS_ACTIF = 1;

    /**
	 * nom du ou des clés primaires OBLIGATOIREMENT un Array
	 * @var array
	 */
	// public static $_SQL_PK = array('id_produit', 'id_option', 'id_host');

    /**
	 * les champs qui se trouve dans la table localisé
	 * @var array
	 */
	public static $_SQL_LOCALIZATION_FIELDS = array('pro_opt_libelle');


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idOption = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?int $isActif = null;

    #[ORM\Column(length: 255)]
    private ?string $defaultValue = null;

    #[ORM\Column(length: 255)]
    private ?string $optionMinValue = "";

    #[ORM\Column(length: 255)]
    private ?string $optionMaxValue = "";

    #[ORM\Column(length: 255)]
    private ?string $idHost = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateHourLastSeen = null;

    #[ORM\ManyToOne(inversedBy: 'tAProductOptions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TProduct $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?TProduct
    {
        return $this->product;
    }

    public function setProduct(?TProduct $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getIdOption(): ?int
    {
        return $this->idOption;
    }

    public function setIdOption(int $idOption): static
    {
        $this->idOption = $idOption;

        return $this;
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

    public function getIsActif(): ?int
    {
        return $this->isActif;
    }

    public function setIsActif(int $isActif): static
    {
        $this->isActif = $isActif;

        return $this;
    }

    public function getDefaultValue(): ?string
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(string $defaultValue): static
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    public function getOptionMinValue(): ?string
    {
        return $this->optionMinValue;
    }

    public function setMinValue(string $optionMinValue): static
    {
        $this->optionMinValue = $optionMinValue;

        return $this;
    }

    public function getOptionMaxValue(): ?string
    {
        return $this->optionMaxValue;
    }

    public function setOptionMaxValue(string $optionMaxValue): static
    {
        $this->optionMaxValue = $optionMaxValue;

        return $this;
    }

    public function getIdHost(): ?string
    {
        return $this->idHost;
    }

    public function setIdHost(string $idHost): static
    {
        $this->idHost = $idHost;

        return $this;
    }

    public function getDateHourLastSeen(): ?\DateTimeImmutable
    {
        return $this->dateHourLastSeen;
    }

    public function setDateHourLastSeen(\DateTimeImmutable $dateHourLastSeen): static
    {
        $this->dateHourLastSeen = $dateHourLastSeen;

        return $this;
    }

    /**
     * *******************************************************************************
     * TO DO : RELATION
     * ********************************************************************************
     */

    /**
	 * Retourne le produit correspondante
	 * @return TProduit 
	 */
	// public function getProduit()
	// {
		// si on n'a pas encore récupéré notre sous objet
		// if($this->_produit == NULL)
		// {
			// on la récupére
	// 		$this->_produit = TProduit::findById($this->getIdProduit());
	// 	}

	// 	return $this->_produit;
	// }


	/**
	 * Retourne l'objet Produit Option lié à cet objet
	 * @return TProduitOption
	 */
	// public function getDateHeureLastSeen()
	// {
		// si on n'a pas encore cherché
		// if($this->_dateHeureLastSeen === NULL)
		// {
		 	// si on n'a pas de date
			// if($this->getProOptDateLastSeen() == NULL)
			// {
				// on renverra FALSE
			// 	$this->_dateHeureLastSeen = FALSE;
			// }
			// on a une date
			// else
			// {
				// on créé notre dateheure
	// 			$this->_dateHeureLastSeen = new DateHeure($this->getProOptDateLastSeen());
	// 		}
	// 	}
	// 	return $this->_dateHeureLastSeen;
	// }
    

    /**
     **********************************************************************
     * TO DO : SERVICE
     **********************************************************************
     */

    /**
	 * renvoi un string de la date de dernier vu
	 * @return string
	 */
	// public function lastSeenString()
	// {
		// si on n'a pas de date
		// if($this->getDateHeureLastSeen() == FALSE)
		// {
		// 	return '??/??/????';
		// }

		// on renvoi la date
	// 	return $this->getDateHeureLastSeen()->format(DateHeure::DATEFR);
	// }


	/**
	 * renvoi une version HTML de la date de derniére vu
	 * @return type
	 */
	// public function lastSeenStringHtml()
	// {
	// 	$class = 'last-seen-error';

		// si on n'a pas de date de derniére vu
		// if($this->getDateHeureLastSeen() == FALSE)
		// {
		// 	$class = 'last-seen-error';
		// }
		// else
		// {
			// calcul de la durée entre maintenant et la derniére vu
			// $duree = Duree::dureeEntreDate($this->getDateHeureLastSeen(), System::today());

			// on attribue la classe suivant le délai
	// 		if($duree->getJour() >= 30)
	// 		{
	// 			$class = 'last-seen-error';
	// 		}
	// 		elseif($duree->getJour() >= 15)
	// 		{
	// 			$class = 'last-seen-warning';
	// 		}
	// 		elseif($duree->getJour() >= 7)
	// 		{
	// 			$class = 'last-seen-late';
	// 		}
	// 		else
	// 		{
	// 			$class = 'last-seen-ok';
	// 		}
	// 	}

	// 	return '<span class="' . $class . '">' . $this->lastSeenString() . '</span>';
	// }

    /*
     **************************************************************************
     * TO DO : REPOSITORY 
     * *************************************************************************
     */

    /**
	 * créé un produitOption si il n'existe pas
	 * @param int $idProduit id du produit
	 * @param TOption $option option
	 * @param string $idHost id du site
	 * @param string|NULL $defaultValue [=NULL] valeur par défaut utilisé dans les option de type text
	 * @param int $proOptIsActif [=TAProduitOption::STATUS_ACTIF] indique si cette option est active ou non
	 * @param string $minValue [=''] Valeur minimum pour les options de type texte si applicable
	 * @param string $maxValue [=''] Valeur maximum pour les options de type texte si applicable
	 */
	// public static function createIfNotExist($idProduit, $option, $idHost, $defaultValue = NULL, $proOptIsActif = TAProduitOption::STATUS_ACTIF, $minValue = '', $maxValue = '')
	// {
		// on recherche notre TAProduitOption
		// $produitOption = TAProduitOption::findById(array($idProduit, $option->getIdOption(), $idHost));

		// si notre produit option n'existe pas encore
		// if($produitOption->getIdProduit() == NULL)
		// {
			// on va donc créé ce produit option
			// $produitOption = new TAProduitOption();
			// $produitOption->setIdProduit($idProduit)
			// 		->setIdOption($option->getIdOption())
			// 		->setIdHost($idHost)
			// 		->setProOptLibelle($option->getOptLibelle())
			// 		->setProOptDefaultValue($defaultValue)
			// 		->setProOptMinValue($minValue)
			// 		->setProOptMaxValue($maxValue)
			// 		->setProOptIsActif($proOptIsActif)
			// 		->setProOptDateLastSeen(System::today()->format(DateHeure::DATEMYSQL))
			// 		->reloadPrimaryValue();

			// si une ligne existe dans la table hors localisation
			// if($produitOption->existRow())
			// {
				// on sauvegarde uniquement la localisation
			// 	$produitOption->saveJustLocalization();
			// }
			// ce produit option value n'existe pas du tout
			// else
			// {
				// on le créé
		// 		$produitOption->save();
		// 	}
		// }
		// si on a déjà ce produit option et que la derniere vu et plus ancienne que le jour meme
		// elseif($produitOption->lastSeenString() != System::today()->format(DateHeure::DATEFR))
		// {
			// on met à jour la date de derniére vu
	// 		$produitOption->setProOptDateLastSeen(System::today()->format(DateHeure::DATEMYSQL))
	// 				->save();
	// 	}
	// }


	/**
	 * renvoi tous les TAProduitOption en fonction d'un idProduit et d'un id de site
	 * @param int $idProduit id du produit et pas de produit host
	 * @param string $idHost = lig id du site
	 * @param bool $ordered =FALSE mettre TRUE si on veux dans le bon ordre
	 * @param int|NULL $optionType =NULL valeur de type d'option que l'on souhaite ou NULL pour avoir toutes les options
	 * @param bool $getOnLyActif =TRUE veux-on uniquement les produit option active ?
	 * @return TAProduitOption[]
	 */
	// public static function findAllActifByIdProduitidHost($idProduit, $idHost = 'lig', $ordered = FALSE, $optionType = NULL, $getOnLyActif = TRUE)
	// {
		// paramétre de la requête
		// $aChamp	 = array('id_produit', 'id_host');
		// $aValue	 = array($idProduit, $idHost);

		// si on ne veux que les option active
		// if($getOnLyActif)
		// {
			// ajout des paramétres à la fonction
		// 	$aChamp[]	 = 'pro_opt_is_actif';
		// 	$aValue[]	 = 1;
		// }

		// si on veux triéer dans le bon ordre ou si on a un type d'option
		// if($ordered || $optionType !== NULL)
		// {
			// ajout de la jointure
		// 	$joinList = array(array('table' => TOption::$_SQL_TABLE_NAME, 'alias' => 'o', 'joinCondition' => 't.id_option = o.id_option'));
		// }
		// else
		// {
			// pas de jointure
		// 	$joinList = array();
		// }


		// si on veux trier dans le bon ordre
		// if($ordered)
		// {
			// tri dans le bon ordre
		// 	$order = array('opt_ordre');
		// }
		// pas d'ordre
		// else
		// {

			// pas d'ordre
		// 	$order = array();
		// }

		// si on a un type d'option
		// if($optionType !== NULL)
		// {
			// ajout du paramétre
		// 	$aChamp[]	 = 'opt_type_option';
		// 	$aValue[]	 = $optionType;
		// }

		// renvoi le résultat de la requête
	// 	return self::findAllBy($aChamp, $aValue, $order, 0, $joinList);
	// }


	/**
	 * find all spécifique pour les fiche technique
	 * @param type $idProduit
	 * @param type $tabIdOption
	 * @param type $idHost
	 * @return type
	 */
	// public static function findAllForFicheTech($idProduit, $tabIdOption, $idHost = 'lig')
	// {
	// 	$sql = 'SELECT *
	// 		FROM ' . self::$_SQL_TABLE_NAME . '
	// 		WHERE id_option NOT IN ( ' . implode(',', $tabIdOption) . ' )
	// 		AND id_produit = ' . $idProduit . '
	// 		AND pro_opt_is_actif = 1
	// 		AND id_host = "' . $idHost . '"';

	// 	return self::findAllSql($sql, TRUE);
	// }


	/**
	 * renvoi un tableau de TAProduitOption suivant les paramétres
	 * @param int $idOption id de l'option
	 * @return TAProduitOption
	 */
	// public static function findAllByIdOption($idOption)
	// {
	// 	return self::findAllBy(array('id_option'), array($idOption));
	// }


	/**
	 * avant de supprimé
	 * on supprimé les produit option value lié à cette option value
	 */
	// protected function _preDelete()
	// {
	// 	parent::_preDelete();

		// récupération des produit option value lié
		// $allOptionValue = TAProduitOptionValue::findAllByIdOptionProduitHost($this->getIdOption(), $this->getIdProduit(), $this->getIdHost());

		// pour chaque produit option value
		// foreach($allOptionValue AS $optionValue)
		// {
			// on la supprime
	// 		$optionValue->delete();
	// 	}
	// }

    /**
	 * retourn les produits selon l'id du Produit et l'id du l'option
	 * @param int $idProduit
	 * @param strinh $cmhId  (id de site host)
	 * @param strinh $idCodePays
	 * @return list of TAProduitOptionValue
	 */
	// public static function getProduitOptionByIdProduitIdOption($idProduit, $cmhId, $idCodePays = 'fr_FR')
	// {
	// 	$rep			 = array();
	// 	$calledClassName = get_class();
	// 	$object			 = new $calledClassName();

	// 	$sql = 'SELECT ' . $object->getSelectEtoile();
	// 	$sql .= ' FROM  ' . self::$_SQL_TABLE_NAME . ' t
	// 			  JOIN ' . self::$_SQL_LOCALIZATION_TABLE_NAME . ' tl ON ';
	// 	$sql .= $object->getJoinCondition();

	// 	$sql .= ' WHERE tl.id_code_pays = "' . $idCodePays . '"    AND
	// 				   t.id_host		= "' . $cmhId . '"		   AND 
	// 				   t.id_produit	= ' . $idProduit . '  
	// 			ORDER BY tl.pro_opt_libelle';

	// 	$req = DB::req($sql);

	// 	while($r = $req->fetch_object())
	// 	{
	// 		$id			 = $r->id_produit . '-' . $r->id_option . '-' . $r->id_host;
	// 		$rep[$id]	 = $r;
	// 	}

	// 	return $rep;
	// }


	/**
	 * Met à jour les valeur mini et maxi de notre objet ci besoin
	 * @param type $minValue
	 * @param type $maxValue
	 */
	// public function updateMinMaxIfNeeded($minValue, $maxValue)
	// {
		// si la valeur min ou max a changé
		// if($this->getProOptMinValue() != $minValue || $this->getProOptMaxValue() != $maxValue)
		// {
		// 	$log = TLog::initLog('Modification min max d\'une option texte');
		// 	$log->Erreur(var_export($minValue, TRUE));
		// 	$log->Erreur(var_export($maxValue, TRUE));
		// 	$log->Erreur(var_export($this, TRUE));

			// on les met à jour dans notre objet
	// 		$this->setProOptMinValue($minValue)
	// 				->setProOptMaxValue($maxValue)
	// 				->save();
	// 	}
	// }


	/**
	 * purge les lignes dans la base qui n'ont plus de raison d'être
	 * @param TLockProcess $lockProcess objet de lockprocess pour mettre à jour les étapes
	 */
	// public static function purge(TLockProcess $lockProcess)
	// {
	// 	$lockProcess->updateStage('Recherche des produit options sans produit option value');

		// recherche des produit options dont le produit n'existe plus
		// $sql = 'SELECT *
		// 	FROM ' . self::$_SQL_TABLE_NAME . '
		// 	WHERE id_produit NOT IN (
		// 	SELECT id_produit
		// 	FROM ' . TProduit::$_SQL_TABLE_NAME . ')';
		// $rqt = DB::req($sql);

		// pour chaque id option value
		// while($rslt = $rqt->fetch_assoc())
		// {
		// 	$lockProcess->updateStage('Suppression produit option ' . $rslt['id_host'] . ', produit ' . $rslt['id_produit'] . ', option ' . $rslt['id_option']);

			// on récupére la produit option value
			// $produitOption = TAProduitOption::findById(array($rslt['id_produit'], $rslt['id_option'], $rslt['id_host']));

			//si on a un probléme
			// if($produitOption->getIdProduit() == NULL)
			// {
			 	// on ajoute une erreur
				// $lockProcess->getLog()->Erreur('impossible de trouver ce produit option.');
				// $lockProcess->getLog()->Erreur('Localisation manquante probable.');

				// on passe au suivant
			// 	continue;
			// }

			// on la supprime
		// 	$produitOption->delete();
		// }

		// paramétre de la requête qui va récupérer toutes les produit option de type select avec le nombre de produit option value associé
		// $aTable			 = array(array('table' => TAProduitOption::$_SQL_TABLE_NAME, 'alias' => 't'),
		// 	array('table' => TOption::$_SQL_TABLE_NAME, 'alias' => 'o'),
		// 	array('table' => TOptionValue::$_SQL_TABLE_NAME, 'alias' => 'ov', 'join' => 'LEFT JOIN'),
		// 	array('table' => TAProduitOptionValue::$_SQL_TABLE_NAME, 'alias' => 'pov', 'join' => 'LEFT JOIN'));
		// $champs			 = array('t.id_produit', 't.id_option', 't.id_host', 'count(pov.id_option_value) AS count');
		// $where			 = array(array('o.opt_type_option', array(TOption::TYPE_OPTION_SELECT, TOption::TYPE_OPTION_CHECKBOX), 's'));
		// $joinCondition	 = array('o.id_option = t.id_option', 'ov.id_option = t.id_option', 'ov.id_option_value = pov.id_option_value AND pov.id_produit = t.id_produit AND pov.id_host = t.id_host');
		// $groupByList	 = array('t.id_produit', 't.id_option', 't.id_host');

		// execution de la requete
		// $allProductOption = db::prepareSelectAndExecuteAndFetchAll($aTable, $champs, $where, 0, array(), $joinCondition, $groupByList);

		// pour chaque produit option
		// foreach($allProductOption as $productOption)
		// {
			// si on a des produit option value lié
			// if($productOption['count'] > 0)
			// {
				// on passe à la suivante
			// 	continue;
			// }

			// on récupére la produit option value
			// $produitOption = TAProduitOption::findById(array($productOption['id_produit'], $productOption['id_option'], $productOption['id_host']));

			//si on a un probléme
			// if($produitOption->getIdProduit() == NULL)
			// {
				// on ajoute une erreur
				// $lockProcess->getLog()->Erreur('impossible de trouver ce produit option.');
				// $lockProcess->getLog()->Erreur('Localisation manquante probable.');

				// on passe au suivant
			// 	continue;
			// }

			// on la supprime
	// 		$produitOption->delete();
	// 	}
	// }


	/**
	 * Récupére le produit option des quantités
	 * @param int $idProduit id du produit
	 * @param string $idHost id du site
	 * @return TAProduitOption|false l'option des quantité ou false si on n'a pas trouvé
	 */
	// public static function findQuantityByProductAndHost($idProduit, $idHost)
	// {
		// pour chaque produit option
		// foreach(TAProduitOption::findAllActifByIdProduitidHost($idProduit, $idHost) as $produitOption)
		// {
			// si on est sur l'option des quantité
			// if($produitOption->getOption()->isQuantity())
			// {
				// on renvoi l'option des quantité
		// 		return $produitOption;
		// 	}
		// }

		// on n'a pas trouvé
	// 	return false;
	// }
}
