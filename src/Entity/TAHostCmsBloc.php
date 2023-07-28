<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAHostCmsBlocRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAHostCmsBlocRepository::class)]
class TAHostCmsBloc
{
    /**
     * Identifiant du site
     * @var string
     */
//    private $idHost;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
//     Todo : relation
    private ?string $tCmsBloc = null;

    #[ORM\Column(length: 255)]
//    Todo : relation
    private ?string $tCmsDiapo = null;

    /**
     * Getter pour l'attribut de l'identifiant du site
     * @return int
     */
//    public function getIdHost()
//    {
//        return $this->idHost;
//    }


    /**
     * Setter pour l'attribut de l'identifiant du site
     * @param int $idHost       Identifiant du site
     * @return TAHostCmsBloc
     */
//    public function setIdHost($idHost)
//    {
//        $this->idHost = $idHost;
//        return $this;
//    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTCmsBloc(): ?string
    {
        return $this->tCmsBloc;
    }

//    public function getTCmsBloc()
//    {
//        if($this->_tCmsBloc === NULL)
//        {
//            $this->_tCmsBloc = TCmsBloc::findById(array($this->getIdCmsBloc()));
//        }
//
//        return $this->_tCmsBloc;
//    }


    public function setTCmsBloc(string $tCmsBloc): static
    {
        $this->tCmsBloc = $tCmsBloc;

        return $this;
    }

    public function getTCmsDiapo(): ?string
    {
        return $this->tCmsDiapo;
    }

//    public function getTCmsDiapo()
//    {
//        if($this->_tCmsDiapo === NULL)
//        {
//            $this->_tCmsDiapo = TCmsDiapo::findById(array($this->getTCmsBloc()->getIdCmsDiapo()));
//        }
//
//        return $this->_tCmsDiapo;
//    }

    public function setTCmsDiapo(string $tCmsDiapo): static
    {
        $this->tCmsDiapo = $tCmsDiapo;

        return $this;
    }

//    Todo : repository

    // =================== Methodes de recherche (find) ===================

    /**
     * Rechercher un bloc CMS d'un site a partir de son type
     * @param string $idHost            Identifiant du site
     * @param int $idCmsBlocType        Identifiant du type de bloc CMS
     * @return TCmsBloc|NULL
     */
//    public static function findByIdHostAndIdCmsBlocType($idHost, $idCmsBlocType)
//    {
//        $joinList = array(
//            'cb' => array('table' => TCmsBloc::$_SQL_TABLE_NAME, 'alias' => 'cb', 'joinCondition' => 't.id_cms_bloc = cb.id_cms_bloc', 'subObjectClass' => 'TCmsBloc'),
//            'cd' => array('join' => 'LEFT JOIN', 'table' => TCmsDiapo::$_SQL_TABLE_NAME, 'alias' => 'cd', 'joinCondition' => 'cb.id_cms_diapo = cd.id_cms_diapo', 'subObjectClass' => 'TCmsDiapo')
//        );
//
//        $taHostCmsBloc = TAHostCmsBloc::findBy(array('t.id_host', 'cb.id_cms_bloc_type'), array($idHost, $idCmsBlocType), $joinList);
//
//        return $taHostCmsBloc;
//    }


    /**
     * Rechercher un bloc CMS (information colonne droite) d'un site
     * @param string $idHost            Identifiant du site
     * @return TAHostCmsBloc|NULL
     */
//    public static function findByIdHostForInfoColD($idHost)
//    {
//        return self::findByIdHostAndIdCmsBlocType($idHost, TCmsBloc::ID_CMS_BLOC_TYPE_INFCOLD);
//    }

    /**
     * Rechercher un bloc CMS (reference entreprise) d'un site
     * @param string $idHost            Identifiant du site
     * @return TAHostCmsBloc|NULL
     */
//    public static function findByIdHostForRefEntr($idHost)
//    {
//        return self::findByIdHostAndIdCmsBlocType($idHost, TCmsBloc::ID_CMS_BLOC_TYPE_REFENTR);
//    }
}
