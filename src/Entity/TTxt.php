<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TTxtRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TTxtRepository::class)]
class TTxt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    //private $txtValue;
    private ?string $value = null;

    #[ORM\ManyToOne(inversedBy: 'tTxts')]
    #[ORM\JoinColumn(nullable: false)]
    //private ?SiteHost $_host = null;
    private ?Hosts $host = null;

    //TODO relation
    #[ORM\Column]
    //private ?int $productHost = null;
    private ?int $idProductHost;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getHost(): ?Hosts
    {
        return $this->host;
    }

    public function setHost(?Hosts $host): static
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Getter pour l'attribut $idProduitHost
     * @return int|null (11)
     */
    public function getIdProductHost(): ?int
    {
        return $this->idProductHost;
    }

    /**
     * Setter pour l'attribut $idProduitHost
     * @param int(11) $idProduitHost
     * @return TTxt
     */
    public function setIdProductHost($idProductHost): static
    {
        $this->idProductHost = $idProductHost;

        return $this;
    }
    //TODO repository
    /**
     * Retourne tout les Txts d'un site
     * @param siteHost $host
     * @return TTxt[]
     */
//    public static function findAllByHost(siteHost $host)
//    {
//        return self::findAllBy(array('id_host'), array($host->getHostId()));
//    }

    /**
     * Retourne le txt lié à un site et un produit
     * @param siteHost $host
     * @param TProduitHost $produit
     * @return TTxt
     */
//    public static function findByHostAndProductHost(siteHost $host, TProductHost $product)
//    {
//        return self::findBy(array('id_host', 'id_product_host'), array($host->getHostId(), $product->getIdProductHost()));
//    }

    // TODO service
    /**
     * Retourne le tProduitHost
     * @return TProduitHost
     */
//    public function getProductHost()
//    {
//
//        if($this->productHost === null)
//        {
//            $this->productHost = TProductHost::findById(array($this->idProduitHost, $this->getHost()->getMasterHost()));
//        }
//
//        return $this->productHost;
//    }

    /**
     * Retourne le siteHost
     * @return siteHost
     */
//    public function getHost()
//    {
//
//        if($this->_host === null)
//        {
//            $this->_host = siteHost::findById($this->idHost);
//        }
//
//        return $this->_host;
//    }


    /**
     * aprés enregistré on supprime notre objet si on a pas de texte
     */
//    protected function _postSave()
//    {
//        parent::_postSave();

        // si on a pas de texte
//        if(trim($this->getTxtValue()) == '')
//        {
            // on supprime notre objet
//            $this->delete();
//        }
//    }

//

}
