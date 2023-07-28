<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TCmsPageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TCmsPageRepository::class)]
class TCmsPage
{
    // =================== Constantes ===================
//    const STATUT_INACTIF	 = 0;
//    const STATUT_ACTIF	 = 1;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Identifiant du site
     * @var string
     */
    private $idHost;
    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $metaTitle = null;

    #[ORM\Column(length: 255)]
    private ?string $metaDescription = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $statut = 0;

    #[ORM\Column]
    private ?\DateTimeImmutable $lastUpdate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateHeureLastUpdate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Getter pour l'attribut de l'identifiant du site
     * @return int
     */
    public function getIdHost()
    {
        return $this->idHost;
    }

    public function setIdHost($idHost)
    {
        $this->idHost = $idHost;

        return $this;
    }

    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    public function setMetaTitle(string $metaTitle): static
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(string $metaDescription): static
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getLastUpdate(): ?string
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(string $lastUpdate): static
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getDateHeureLastUpdate(): ?\DateTimeImmutable
    {
        return $this->dateHeureLastUpdate;
    }

    public function setDateHeureLastUpdate(\DateTimeImmutable $dateHeureLastUpdate): static
    {
        $this->dateHeureLastUpdate = $dateHeureLastUpdate;

        return $this;
    }

    /**
     * avant de sauvegarder on met à jour la date de derniére modification
     */
//    protected function _preSave()
//    {
//        parent::_preSave();

        // création d'un nouvel objet date
//        $date = new DateHeure();

        // on met à jour la date de derniére modification
//        $this->setCmsPagLastUpdate($date->format(DateHeure::DATETIMEMYSQL));
//    }

    // =================== Methodes de recherche (find) ===================

//    Todo : repository
    /**
     * Recupere une page CMS pour un site et une url donnee (actives et inactives)
     * @param string $idHost		Identifiant du site
     * @param string $cmsPagUrl		Url de la page du CMS
     * @param NULL|int $cmsPagStatut		[=NULL] Toutes, 1 pour Actif et 0 pour Inactif
     * @return TCmsPage
     */
//    public static function findByIdHostAndCmsPagUrl($idHost, $cmsPagUrl, $cmsPagStatut = NULL)
//    {
//        $aChamp	 = array('id_host', 'cms_pag_url');
//        $aValue	 = array($idHost, $cmsPagUrl);
//
//        if($cmsPagStatut !== NULL)
//        {
//            $aChamp[]	 = 'cms_pag_statut';
//            $aValue[]	 = $cmsPagStatut;
//        }
//
//        return self::findBy($aChamp, $aValue);
//    }


    /**
     * renvoi tous les page cms active d'un site
     * @param string $idHost id du site
     * @return TCmsPage[]
     */
//    public static function findAllActifByIdHost($idHost)
//    {
//        return self::findAllBy(array('cms_pag_statut', 'id_host'), array(1, $idHost));
//    }
    // =================== Autres methodes publiques ===================


    /**
     * Retourne le lien absolu de la page CMS du site (avec reecriture d'Url)
     * @return string
     */
//    public function cmsPagHref()
//    {
//        return System::constructHttpServerFromHost($this->getIdHost()) . $this->getCmsPagUrl();
//    }


    /**
     * Retourne le lien absolu de la page CMS pour la visualisation
     * @return string
     */
//    public function cmsPagHrefApercu()
//    {
//        return System::constructHttpServerFromHost($this->getIdHost()) . 'impression/cms/page/cms_pag_url=' . $this->getCmsPagUrl() . '&amp;apercu=1';
//    }


    /**
     * renvoi le content aprés remplacement des variables
     * @return text
     */
//    public function getContentReplacedVariable()
//    {
//        $template = new Template();
//
//        return $template->replaceVariable($this->getCmsPagContent());
//    }
}
