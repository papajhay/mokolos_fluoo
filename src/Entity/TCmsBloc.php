<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TCmsBlocRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TCmsBlocRepository::class)]
class TCmsBloc
{
    // =================== Constantes ===================

    /**
     * Identifiant du type de bloc CMS (Information colonne droite)
     */
    const ID_CMS_BLOC_TYPE_INFCOLD = 1;

    /**
     * Identifiant du type de bloc CMS (Reference entreprise)
     */
    const ID_CMS_BLOC_TYPE_REFENTR = 2;

    /**
     * Identifiant du type de bloc CMS (Produits)
     */
    const ID_CMS_BLOC_TYPE_PRODUCT = 3;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    /* Identifiant du type de bloc CMS
      $idCmsBlocType */
    private ?int $idType = null;

    #[ORM\Column]
//    Identifiant du bloc CMS
    private ?int $idCmsDiapo = null;

    #[ORM\Column(type: Types::TEXT)]
    /* Contenu HTML du bloc CMS
      $cmsBloHtml */
    private ?string $content = null;

    #[ORM\OneToMany(mappedBy: 'tcmsBloc', targetEntity: TAHostCmsBloc::class, orphanRemoval: true)]
    private Collection $tAHostCmsBlocs;

    public function __construct()
    {
        $this->tAHostCmsBlocs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdType(): ?int
    {
        return $this->idType;
    }

    public function setIdType(int $idType): static
    {
        $this->idType = $idType;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getIdCmsDiapo(): ?int
    {
        return $this->idCmsDiapo;
    }

    public function setIdCmsDiapo(int $idCmsDiapo): static
    {
        $this->idCmsDiapo = $idCmsDiapo;

        return $this;
    }

//    Todo : Service
    /**
     * Tableau des Url relatives pour le diaporama a partir du type de bloc CMS
     * @var array
     */
//    private static $_aCmsDiaRepUrlFromBlocType = [
//        self::ID_CMS_BLOC_TYPE_REFENTR => "cms/bloc/refentr/",
//        self::ID_CMS_BLOC_TYPE_PRODUCT => "cms/bloc/product/"
//    ];

    // ===================== Autres methodes =====================

    /**
     * Recuperer l'url du repertoire du diaporama selon le type de bloc CMS
     * @param int $idBlocType       Identifiant du type de bloc CMS
     * @return string
     */
//    public static function cmsDiaUrlFromBlocType($idBlocType)
//    {
//        $cmsDiaRepUrl = "";
//
//        if(isset(self::$_aCmsDiaRepUrlFromBlocType[$idBlocType]))
//        {
//            $cmsDiaRepUrl = self::$_aCmsDiaRepUrlFromBlocType[$idBlocType];
//        }
//
//        return $cmsDiaRepUrl;
//    }

//     Todo : repository
    /**
     * Sauvegarder le contenu HTML d'un bloc CMS pour un site
     * @param string $idHost                    Identifiant du site
     * @param int $idCmsBloc                    Identifiant du bloc
     * @param int $idCmsBlocType                Identifiant du type de bloc
     * @param string $cmsBloHtml                Contenu HTML du bloc
     * @return TCmsBloc
     */
//    public static function saveForHostFrom($idHost, $idCmsBloc, $idCmsBlocType, $cmsBloHtml = '')
//    {
//        $tCmsBloc = NULL;
//
//        if(intval($idCmsBloc) > 0)
//        {
            // rechercher le bloc CMS existant
//            $tCmsBloc = TCmsBloc::findById(array($idCmsBloc));
//        }

//        if($tCmsBloc === NULL)
//        {
            // creer un nouveau bloc CMS pour le site selectionne
//            $tCmsBloc = TCmsBloc::createNew($idCmsBlocType, $cmsBloHtml);

            // creer l'association du bloc CMS au site
//            TAHostCmsBloc::createNew($idHost, $tCmsBloc->getIdCmsBloc());
//        }
//        else
//        {
            // modifier le contenu HTML du bloc CMS existant
//            $tCmsBloc->setCmsBloHtml($cmsBloHtml);
//            $tCmsBloc->save();
//        }
//
//        return $tCmsBloc;
//    }


    /**
     * Sauvegarder le diaporama d'un bloc CMS pour un site
     * @param string $idHost                    Identifiant du site
     * @param int $idCmsBloc                    Identifiant du bloc
     * @param int $idCmsBlocType                Identifiant du type de bloc
     * @param string $cmsBloHtml                Contenu HTML du bloc
     * @param int $cmsDiaHeight                 Hauteur du diaporama en px
     * @param int $cmsDiaWidth                  Largeur du diaporama en px
     * @return TCmsBloc
     */
//    public static function saveDiapoForHostFrom($idHost, $idCmsBloc, $idCmsBlocType, $cmsBloHtml, $cmsDiaHeight, $cmsDiaWidth)
//    {
        // sauvegarder le contenu HTML du bloc CMS du diaporama du site
//        $tCmsBloc = self::saveForHostFrom($idHost, $idCmsBloc, $idCmsBlocType, $cmsBloHtml);

        // recuperer l'identifiant du diaporama CMS
//        $idCmsDiapo = $tCmsBloc->getIdCmsDiapo();

        // si aucun diaporama n'est associe a ce bloc CMS
//        if($idCmsDiapo <= 0)
//        {
            // ajouter le diaporama au bloc CMS
//            $tCmsDiapo = TCmsDiapo::createNew(TCmsBloc::cmsDiaUrlFromBlocType($idCmsBlocType), $cmsDiaHeight, $cmsDiaWidth);
//
//            $tCmsBloc->setIdCmsDiapo($tCmsDiapo->getIdCmsDiapo());
//            $tCmsBloc->save();
//        }
//        else
//        {
            // recuperer le diaporama CMS
//            $tCmsDiapo = TCmsDiapo::findById(array($idCmsDiapo));
//            $tCmsDiapo->setCmsDiaHeight($cmsDiaHeight);
//            $tCmsDiapo->setCmsDiaWidth($cmsDiaWidth);
//            $tCmsDiapo->save();
//        }

//        return $tCmsBloc;
//    }

/**
 * @return Collection<int, TAHostCmsBloc>
 */
public function getTcmsDiapo(): Collection
{
    return $this->tcmsDiapo;
}

public function addTcmsDiapo(TAHostCmsBloc $tcmsDiapo): static
{
    if (!$this->tcmsDiapo->contains($tcmsDiapo)) {
        $this->tcmsDiapo->add($tcmsDiapo);
        $tcmsDiapo->setTcmsBloc($this);
    }

    return $this;
}

public function removeTcmsDiapo(TAHostCmsBloc $tcmsDiapo): static
{
    if ($this->tcmsDiapo->removeElement($tcmsDiapo)) {
        // set the owning side to null (unless already changed)
        if ($tcmsDiapo->getTcmsBloc() === $this) {
            $tcmsDiapo->setTcmsBloc(null);
        }
    }

    return $this;
}
}
