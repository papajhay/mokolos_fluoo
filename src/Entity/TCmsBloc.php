<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TCmsBlocRepository;
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
    /**
     * Crer un nouvel objet "TCmsBloc" et le retourner
     * @param int(11) $idCmsBlocType    Identifiant du type de bloc CMS
     * @param text $cmsBloHtml          Contenu HTML du bloc CMS
     * @param int(11) $idCmsDiapo       [=0] Identifiant du diaporama du bloc CMS
     * @return TCmsBloc Nouvel Objet insere en base
     */
//    public static function createNew($idCmsBlocType, $cmsBloHtml, $idCmsDiapo = 0)
//    {
//        $tCmsBloc = new self();
//        $tCmsBloc->setIdCmsBlocType($idCmsBlocType);
//        $tCmsBloc->setCmsBloHtml($cmsBloHtml);
//        $tCmsBloc->setIdCmsDiapo($idCmsDiapo);
//        $tCmsBloc->save();
//
//        return $tCmsBloc;
//    }


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
}