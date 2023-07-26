<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAProductHostCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAProductHostCategoryRepository::class)]
class TAProductHostCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private ?int $idProductHost;

    #[ORM\Column]
    private ?string $tProductHost = null;

    #[ORM\Column]
    private ?int $idCategory;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter pour l'attribut $idProduitHost  (Identifiant du produit)
     * @return int(11)
     */
    public function getIdProductHost()
    {
        return $this->idProductHost;
    }


    /**
     * Setter pour l'attribut $idProduitHost  (Identifiant du produit)
     * @param int(11) $idProduitHost
     * @return TAProduitHostCategorie
     */
    public function setIdProductHost($idProductHost)
    {
        $this->idProductHost = $idProductHost;
        return $this;
    }

    /**
     * Getter sous-objet TProduitHost
     * @return TProduitHost
     */
    public function getTProductHost()
    {
        if($this->tProductHost===NULL)
        {
            $this->tProductHost = TProductHost::findById(array($this->getIdProductHost()));
        }

        return $this->tProductHost;
    }


    /**
     * Setter du sous-objet TProduitHost
     * @param TProduitHost $tProduitHost
     * @return TAProduitHostCategorie
     */
    public function setTProductHost($tProductHost)
    {
        $this->tProductHost = $tProductHost;
        return $this;
    }

    /**
     * Getter pour l'attribut $idCategorie  (Identifiant de la categorie)
     * @return int(11)
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }


    /**
     * Setter pour l'attribut $idCategorie  (Identifiant de la categorie)
     * @param int(11) $idCategorie
     * @return TAProduitHostCategorie
     */
    public function setIdCategory($idCategory)
    {
        $this->idCategory = $idCategory;
        return $this;
    }
}
