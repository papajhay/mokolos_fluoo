<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAHostCmsBlocRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAHostCmsBlocRepository::class)]
class TAHostCmsBloc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
    * Identifiant du site
    * @var Hosts|null
     */
    // private $idHost;
    #[ORM\ManyToOne(inversedBy: 'tAHostCmsBlocs')]
    private ?Hosts $host = null;

    /**
     * Sous-objet du bloc CMS
     * @var TCmsBloc|null
     */
    // private $_tCmsBloc = NULL;
    #[ORM\ManyToOne(inversedBy: 'tAHostCmsBlocs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TCmsBloc $tcmsBloc = null;

    /**
     * Sous-objet du diaporama du bloc CMS
     * @var TCmsDiapo|null
     */
    // private $_tCmsDiapo = NULL;
    #[ORM\ManyToOne(inversedBy: 'tAHostCmsBlocs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TCmsDiapo $tcmsDiapo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTcmsBloc(): ?TCmsBloc
    {
        return $this->tcmsBloc;
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


    public function setTcmsBloc(TCmsBloc $tCmsBloc): static
    {
        $this->tcmsBloc = $tCmsBloc;

        return $this;
    }

    public function getTcmsDiapo(): ?TCmsDiapo
    {
        return $this->tcmsDiapo;
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

    public function setTcmsDiapo(TCmsDiapo $tCmsDiapo): static
    {
        $this->tcmsBloc = $tCmsDiapo;

        return $this;
    }

    /**
     * Getter pour l'attribut de l'identifiant du site
     * @return Hosts|null
     */
    public function getHost(): ?Hosts
    {
        return $this->host;
    }

    /**
     * Setter pour l'attribut de l'identifiant du site
     * @param hosts|null $host
     * @return TAHostCmsBloc
     */
    public function setHost(?hosts $host): static
    {
        $this->host = $host;

        return $this;
    }

    //   Todo : repository

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
