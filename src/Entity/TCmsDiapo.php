<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TCmsDiapoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TCmsDiapoRepository::class)]
class TCmsDiapo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $height = null;

    #[ORM\Column(length: 255)]
    private ?string $repUrl = null;

    #[ORM\Column]
    private ?int $width = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'tcmsDiapo', targetEntity: TAHostCmsBloc::class, orphanRemoval: true)]
    private Collection $tAHostCmsBlocs;

    #[ORM\OneToMany(mappedBy: 'tCmsDiapo', targetEntity: TCmsBloc::class, orphanRemoval: true)]
    private Collection $tCmsBlocs;


    public function __construct()
    {
        $this->tAHostCmsBlocs = new ArrayCollection();
        $this->tCmsBlocs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getRepUrl(): ?string
    {
        return $this->repUrl;
    }

    public function setRepUrl(string $repUrl): static
    {
        $this->repUrl = $repUrl;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    // =================== Methodes de recherche (find) ===================
//    Todo : repository
    /**
     * Recuperer la liste des images d'un diaporama CMS a partir des chemins d'acces
     * @param string $cmsDiaRepPathFull     Chemin complet d'acces au repertoire
     * @param string $cmsDiaRepUrlFull      Url complete d'acces au repertoire
     * @param int $cmsDiaHeight             Hauteur du diaporama
     * @param int $cmsDiaWidth              Largeur du diaporama
     * @return array Tableau de la liste des images du repertoire du diaporama : array(alt, height, name, url, width)
     */

//    private static function _findCmsDiaImgListFromPath($cmsDiaRepPathFull, $cmsDiaRepUrlFull, $cmsDiaHeight, $cmsDiaWidth)
//    {
        // initialiser la liste des images du repertoire du diaporama
//        $aCmsDiaImg = array();

        // lister les images du diaporama
//        $cmsDiaRepertoire = new Repertoire();
//        $cmsDiaRepertoire->scan($cmsDiaRepPathFull);

        // pour chaque image du repertoire du diaporama
//        foreach($cmsDiaRepertoire->getContenuFichiers() as $cmsDiaFichier)
//        {
//            $aCmsDiaImg[] = array(
//                "alt"	 => $cmsDiaFichier->getNomFichierSansExtention(),
//                "height" => $cmsDiaHeight,
//                "name"	 => $cmsDiaFichier->getNomFichier(),
//                "url"	 => $cmsDiaRepUrlFull . $cmsDiaFichier->getNomFichier(),
//                "width"	 => $cmsDiaWidth
//            );
//        }
//
//        return $aCmsDiaImg;
//    }

    /**
     * Recuperer la liste des images du diaporama CMS
     * @param string $idHost        Identifiant du site
     * @return array Tableau de la liste des images du repertoire du diaporama : array(alt, height, name, url, width)
     */

//    public function findCmsDiaImgListForHost($idHost)
//    {
        // recuperer les attributs du diaporama
//        $cmsDiaHeight	 = intval($this->getCmsDiaHeight());
//        $cmsDiaWidth	 = intval($this->getCmsDiaHeight());
//
//        $cmsDiaRepPathFull	 = $this->_cmsDiaRepPathFullForHost($idHost);
//        $cmsDiaRepUrlFull	 = $this->_cmsDiaRepUrlFullForHost($idHost);
//
//        return self::_findCmsDiaImgListFromPath($cmsDiaRepPathFull, $cmsDiaRepUrlFull, $cmsDiaHeight, $cmsDiaWidth);
//    }

    /**
     * Recuperer la liste des images du diaporama CMS des produits
     * @param string $idHost            Identifiant du site
     * @param Categories $categorie     Objet de la categorie du produit
     * @return array Tableau de la liste des grandes et petites images des repertoires du diaporama : array(alt, height, name, url, width)
     */
//    public static function findCmsDiaImgListForHostProduct($idHost, Categories $categorie)
//    {
        // initialiser le tableau
//        $aCmsDiaImgProduct = array();

        // initialiser les attributs du diaporama
//        $cmsDiaRepPathFull	 = self::createCmsDiaRepPathFullForBlocType(TCmsBloc::ID_CMS_BLOC_TYPE_PRODUCT, $idHost);
//        $cmsDiaRepUrlFull	 = self::createCmsDiaRepUrlFullForBlocType(TCmsBloc::ID_CMS_BLOC_TYPE_PRODUCT, $idHost);

        // recuperer toutes les categories parentes
//        $aCategoriesParent = $categorie->findAllCategories(FALSE);

        // pour chaque categorie parente
//        foreach($aCategoriesParent as $categorieParent)
//        {
            // initialiser le chemin d'acces au dossier des images du diaporama
//            $cmsDiaProductRepPath = new Repertoire();
//            $cmsDiaProductRepPath->setCheminComplet($cmsDiaRepPathFull . $categorieParent->getCategoriesId() . DIRECTORY_SEPARATOR);

            // si le dossier contient au moins une image
//            if($cmsDiaProductRepPath->countFichier() > 0)
//            {
                // quitter la boucle
//                break;
//            }
//        }
//
//        $cmsDiaRepPathFull	 .= $categorieParent->getCategoriesId() . DIRECTORY_SEPARATOR;
//        $cmsDiaRepUrlFull	 .= $categorieParent->getCategoriesId() . DIRECTORY_SEPARATOR;

//        $aCmsDiaImgProduct["small"]	 = self::_findCmsDiaImgListFromPath($cmsDiaRepPathFull . "small" . DIRECTORY_SEPARATOR, $cmsDiaRepUrlFull . "small" . DIRECTORY_SEPARATOR, 185, 285);
//        $aCmsDiaImgProduct["large"]	 = self::_findCmsDiaImgListFromPath($cmsDiaRepPathFull, $cmsDiaRepUrlFull, 555, 855);
//
//        return $aCmsDiaImgProduct;
//    }

    /**
     * Recuperer la liste des images du diaporama CMS des produits
     * @param TProduitHost $tProduitHost        Objet du produit host de Fusion
     * @param bool $allowWebp		[=TRUE] indique si on accepte le webp. Mettre FALSE pour la gestion des images depuis l'admin
     * @return array Tableau de la liste des images du repertoire du diaporama : array(alt, height, name, url, width)
     */

//    public function findCmsDiaImgListForHostProductFusion(TProduitHost $tProduitHost, $allowWebp = TRUE)
//    {
        // initialiser les attributs du diaporama
//        $cmsDiaRepPathFull	 = $this->imageFullPath($tProduitHost->getIdHost(), $allowWebp);
//        $cmsDiaRepUrlFull	 = $this->imageFullUrl($tProduitHost->getIdHost(), $allowWebp);
//
//        $cmsDiaRepPathFull	 .= $tProduitHost->idParentOrIdProduitHost() . DIRECTORY_SEPARATOR;
//        $cmsDiaRepUrlFull	 .= $tProduitHost->idParentOrIdProduitHost() . DIRECTORY_SEPARATOR;
//
//        return self::_findCmsDiaImgListFromPath($cmsDiaRepPathFull, $cmsDiaRepUrlFull, $this->getCmsDiaHeight(), $this->getCmsDiaWidth());
//    }

    // ===================== Methodes d'acces au repertoire d'images =====================
    /**
     * Obtenir le chemin complet d'acces au repertoire du diaporama du bloc CMS
     * @param string $idHost        Identifiant du site
     * @return string
     */

//    private function _cmsDiaRepPathFullForHost($idHost)
//    {
//        return self::_createCmsDiaRepPathRootForHost($idHost) . $this->getCmsDiaRepUrl();
//    }

    /**
     * Obtenir l'url complete d'acces au repertoire du diaporama du bloc CMS
     * @param type $idHost
     * @return type
     */

//    private function _cmsDiaRepUrlFullForHost($idHost)
//    {
//        return self::_createCmsDiaRepUrlRootForHost($idHost) . $this->getCmsDiaRepUrl();
//    }
  
    /**
     * Creer la racine du chemin d'acces au repertoire du diaporama du bloc CMS
     * @param string $idHost        Identifiant du site
     * @param bool $allowWebp [=TRUE] indique si on accepte le webp. Mettre FALSE pour l'envoi des images depuis l'admin
     * @return string
     */
//    private static function _createCmsDiaRepPathRootForHost($idHost, $allowWebp = TRUE)
//    {
        // si on est en prod
//        if(defined('SUB_DOMAIN') && (SUB_DOMAIN == 'www' || SUB_DOMAIN == 'static'))
//        {
            // mode prod
//            $mode = 'www';
//        }
        // on est en dev
//        else
//        {
            // mode dev
//            $mode = 'dev';
//        }

        // si on est sur lgi
//        if($idHost === 'lgi')
//        {
//            $homeName = 'lesgrand';
//        }
//        else
//        {
//            $homeName = 'limprime';
//        }

        // si on veux du webp et qu'on l'accepte
//        if($allowWebp && System::getAcceptWebp())
//        {
            // si on est dans l'admin
//            if(System::isAdminContext())
//            {
                // repertoire des images en webp
//                $imgDir = PATH_ASSETS_SITE_IMG_WEBP;
//            }
            // sur le site
//            else
//            {
                // repertoire des images en webp
//                $imgDir = PATH_ASSETS_IMG_WEBP;
//            }
//        }
        // pas de webp
//        else
//        {
            // si on est dans l'admin
//            if(System::isAdminContext())
//            {
                // repertoire des images classique
//                $imgDir = PATH_ASSETS_SITE_IMG;
//            }
            // sur le site
//            else
//            {
                // repertoire des images classique
//                $imgDir = PATH_ASSETS_IMG;
//            }
//        }

        // on renvoi le chemin complet
//        return '/home/' . $homeName . '/' . $mode . '/' . $imgDir . '_specs/' . $idHost . '/';
//    }

    /**
     * Creer la racine de l'url d'acces au repertoire du diaporama du bloc CMS
     * @param string $idHost        Identifiant du site
     * @param bool $allowWebp		[=TRUE] indique si on accepte le webp. Mettre FALSE pour la gestion des images depuis l'admin
     * @return string
     */
//    private static function _createCmsDiaRepUrlRootForHost($idHost, $allowWebp = TRUE)
//    {
//        if(defined('HTTP_PROTOCOL') && defined('SUB_DOMAIN') && defined('SERVER_IMAGE_NAME'))
//        {
            // recuperer le site
//            $siteHost = siteHost::findById(array($idHost));

            // construire l'url pour prendre en compte le CDN des images
//            $serverUrl = HTTP_PROTOCOL . '://' . SERVER_IMAGE_NAME . '.' . $siteHost->getDomaine() . '/';
//        }
//        else
//        {
//            $serverUrl = System::constructHttpServerFromHost($idHost);
//        }

        // si on veux du webp et qu'on l'accepte
//        if($allowWebp && System::getAcceptWebp())
//        {
            // si on est dans l'admin
//            if(System::isAdminContext())
//            {
                // repertoire des images en webp
//                $imgDir = PATH_ASSETS_SITE_IMG_WEBP;
//            }
            // sur le site
//            else
//            {
                // repertoire des images en webp
//                $imgDir = PATH_ASSETS_IMG_WEBP;
//            }
//        }
        // pas de webp
//        else
//        {
            // si on est dans l'admin
//            if(System::isAdminContext())
//            {
                // repertoire des images classique
//                $imgDir = PATH_ASSETS_SITE_IMG;
//            }
            // sur le site
//            else
//            {
                // repertoire des images classique
//                $imgDir = PATH_ASSETS_IMG;
//            }
//        }

//        return $serverUrl . $imgDir . '_specs/' . $idHost . '/';
//    }


    /**
     * Creer le chemin complet d'acces au repertoire du diaporama du bloc CMS
     * @param int $idBlocType       Identifiant du type de bloc CMS
     * @param string $idHost        Identifiant du site
     * @param bool $allowWebp		[=TRUE] indique si on accepte le webp. Mettre FALSE pour la gestion des images depuis l'admin
     * @return string
     */
//    public static function createCmsDiaRepPathFullForBlocType($idBlocType, $idHost, $allowWebp = TRUE)
//    {
//        return self::_createCmsDiaRepPathRootForHost($idHost, $allowWebp) . TCmsBloc::cmsDiaUrlFromBlocType($idBlocType);
//    }

    /**
     * Creer le chemin complet d'acces au repertoire du diaporama du bloc CMS
     * @param string $idHost        Identifiant du site
     * @param bool $allowWebp		[=TRUE] indique si on accepte le webp. Mettre FALSE pour la gestion des images depuis l'admin
     * @return string
     */
//    public function imageFullPath($idHost, $allowWebp = TRUE)
//    {
//        return self::_createCmsDiaRepPathRootForHost($idHost, $allowWebp) . $this->getCmsDiaRepUrl();
//    }

    /**
     * Creer l'url complete d'acces au repertoire du diaporama du bloc CMS
     * @param int $idBlocType       Identifiant du type de bloc CMS
     * @param string $idHost        Identifiant du site
     * @param bool $allowWebp		[=TRUE] indique si on accepte le webp. Mettre FALSE pour la gestion des images depuis l'admin
     * @return string
     */
//    public static function createCmsDiaRepUrlFullForBlocType($idBlocType, $idHost, $allowWebp = TRUE)
//    {
//        return self::_createCmsDiaRepUrlRootForHost($idHost, $allowWebp) . TCmsBloc::cmsDiaUrlFromBlocType($idBlocType);
//    }

    /**
     * Creer l'url complete d'acces au repertoire du diaporama du bloc CMS
     * @param string $idHost        Identifiant du site
     * @param bool $allowWebp		[=TRUE] indique si on accepte le webp. Mettre FALSE pour la gestion des images depuis l'admin
     * @return string
     */
//    public function imageFullUrl($idHost, $allowWebp = TRUE)
//    {
//        return self::_createCmsDiaRepUrlRootForHost($idHost, $allowWebp) . $this->getCmsDiaRepUrl();
//    }

    /**
     * Uploader un fichier image du diaporama CMS
     * @param array $cmsDiaImgList              Tableau contenant les attributs du fichier : array(error, name, size, tmp_name, type)
     * @param string $cmsDiaRepPathFull         Le chemin d'acces au repertoire du diaporama CMS
     * @return Fichier|FALSE L'objet Fichier en cas de succés et FALSE en cas de probléme
     */
//    public static function uploadAndMoveFrom($cmsDiaImgList, $cmsDiaRepPathFull)
//    {
        // si l'upload a echoue
//        if($cmsDiaImgList['error'] !== UPLOAD_ERR_OK)
//        {
            // on quitte la fonction
//            return FALSE;
//        }

        // création de l'objet fichier
//        $cmsDiaImgFichier = new Fichier();
//        $cmsDiaImgFichier->setCheminComplet($cmsDiaImgList['tmp_name']);

        // creer le chemin complet avec le nouveau nom de l'image du diaporama
//        $cmsDiaImgPathFull = $cmsDiaRepPathFull . $cmsDiaImgList['name'];

        // si on a une erreur pendant le deplacement du fichier dans le dossier du diaporama d'images
//        if(!$cmsDiaImgFichier->moveUploaded($cmsDiaImgPathFull, TRUE, TRUE, 0775))
//        {
            // on quitte la fonction
//            return FALSE;
//        }

        // tout est bon on renvoi le fichier
//        return $cmsDiaImgFichier;
//    }

/**
 * @return Collection<int, TAHostCmsBloc>
 */
public function getTAHostCmsBlocs(): Collection
{
    return $this->tAHostCmsBlocs;
}

public function addTAHostCmsBloc(TAHostCmsBloc $tAHostCmsBloc): static
{
    if (!$this->tAHostCmsBlocs->contains($tAHostCmsBloc)) {
        $this->tAHostCmsBlocs->add($tAHostCmsBloc);
        $tAHostCmsBloc->setTcmsDiapo($this);
    }

    return $this;
}

public function removeTAHostCmsBloc(TAHostCmsBloc $tAHostCmsBloc): static
{
    if ($this->tAHostCmsBlocs->removeElement($tAHostCmsBloc)) {
        // set the owning side to null (unless already changed)
        if ($tAHostCmsBloc->getTcmsDiapo() === $this) {
            $tAHostCmsBloc->setTcmsDiapo(null);
        }
    }

    return $this;
}

/**
 * @return Collection<int, TCmsBloc>
 */
public function getTCmsBlocs(): Collection
{
    return $this->tCmsBlocs;
}

public function addTCmsBloc(TCmsBloc $tCmsBloc): static
{
    if (!$this->tCmsBlocs->contains($tCmsBloc)) {
        $this->tCmsBlocs->add($tCmsBloc);
        $tCmsBloc->setTCmsDiapo($this);
    }

    return $this;
}

public function removeTCmsBloc(TCmsBloc $tCmsBloc): static
{
    if ($this->tCmsBlocs->removeElement($tCmsBloc)) {
        // set the owning side to null (unless already changed)
        if ($tCmsBloc->getTCmsDiapo() === $this) {
            $tCmsBloc->setTCmsDiapo(null);
        }
    }

    return $this;
}

}
