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
    private ?string $tProductHost = null;

    #[ORM\ManyToOne(inversedBy: 'tAProductHostCategories')]
    private ?TProductHost $productHost = null;

    #[ORM\ManyToOne(inversedBy: 'tAProductHostCategories')]
    private ?TCategory $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter sous-objet TProduitHost.
     * @return TProduitHost
     */
    public function getTProductHost()
    {
        if (null === $this->tProductHost) {
            $this->tProductHost = TProductHost::findById([$this->getIdProductHost()]);
        }

        return $this->tProductHost;
    }

    /**
     * Setter du sous-objet TProduitHost.
     * @return TAProduitHostCategorie
     */
    public function setTProductHost($tProductHost)
    {
        $this->tProductHost = $tProductHost;

        return $this;
    }

    public function getProductHost(): ?TProductHost
    {
        return $this->productHost;
    }

    public function setProductHost(?TProductHost $productHost): static
    {
        $this->productHost = $productHost;

        return $this;
    }

    public function getCategory(): ?TCategory
    {
        return $this->category;
    }

    public function setCategory(?TCategory $category): static
    {
        $this->category = $category;

        return $this;
    }
}
