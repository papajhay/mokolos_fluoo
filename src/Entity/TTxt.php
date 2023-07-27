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
    private ?string $value = null;

    #[ORM\Column]
    private ?int $idHost = null;

//    Todo : relation
//    #[ORM\Column]
//    private ?int $productHost = null;

//    #[ORM\Column]
//    private ?int $idProductHost;

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

    public function getIdHost(): ?int
    {
        return $this->idHost;
    }

    public function setIdHost(int $idHost): static
    {
        $this->idHost = $idHost;

        return $this;
    }

    /**
     * Getter pour l'attribut $idProduitHost
     * @return int(11)
     */
//    public function getIdProductHost()
//    {
//        return $this->idProductHost;
//    }


    /**
     * Setter pour l'attribut $idProduitHost
     * @param int(11) $idProduitHost
     * @return TTxt
     */
//    public function setIdProductHost($idProductHost)
//    {
//        $this->idProductHost = $idProductHost;
//        return $this;
//    }

//     Todo : service
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
    public function getHost()
    {

        if($this->_host === null)
        {
            $this->_host = siteHost::findById($this->idHost);
        }

        return $this->_host;
    }


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

//    Todo : repository
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


    /**
     * Crée un nouvel objet "TTxt" et le retourne
     *
     * @param text $txtValue
     * @param int(11) $idHost
     * @param int(11) $idProduitHost
     *
     * @return TTxt Nouvel Objet inserer un base
     */
//    public static function createNew($txtValue, $idHost, $idProductHost)
//    {
//        $tTxt = new TTxt();
//
//        $tTxt->setValue($Value)
//            ->setIdHost($idHost)
//            ->setIdProductHost($idProductHost)
//            ->save();
//
//        return $tTxt;
//    }
}
