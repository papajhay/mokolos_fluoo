<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAProductProviderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAProductProviderRepository::class)]
class TAProductProvider extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tAProductProviders')]
    private ?Provider $provider = null;

    #[ORM\Column]
    private ?int $idSource = null;

    #[ORM\Column(nullable:true)]
    private ?int $idGroup = null;

    #[ORM\Column(length: 255)]
    private ?string $labelSource = null;

    #[ORM\OneToOne(mappedBy: 'tAProductProvider', cascade: ['persist', 'remove'])]
    private ?TProduct $tProduct = null;

    #[ORM\OneToOne(mappedBy: 'tAProductProvider', cascade: ['persist', 'remove'])]
    private ?TProductHost $tProductHost = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): static
    {
        $this->provider = $provider;

        return $this;
    }

    public function getIdSource(): ?int
    {
        return $this->idSource;
    }

    public function setIdSource(int $idSource): static
    {
        $this->idSource = $idSource;

        return $this;
    }

    public function getIdGroup(): ?int
    {
        return $this->idGroup;
    }

    public function setIdGroup(int $idGroup): static
    {
        $this->idGroup = $idGroup;

        return $this;
    }

    public function getLabelSource(): ?string
    {
        return $this->labelSource;
    }

    public function setLabelSource(string $labelSource): static
    {
        $this->labelSource = $labelSource;

        return $this;
    }

    public function getTProduct(): ?TProduct
    {
        return $this->tProduct;
    }

    public function setTProduct(TProduct $tProduct): static
    {
        // set the owning side of the relation if necessary
        if ($tProduct->getTAProductProvider() !== $this) {
            $tProduct->setTAProductProvider($this);
        }

        $this->tProduct = $tProduct;

        return $this;
    }

    public function getTProductHost(): ?TProductHost
    {
        return $this->tProductHost;
    }

    public function setTProductHost(TProductHost $tProductHost): static
    {
        // set the owning side of the relation if necessary
        if ($tProductHost->getTAProductProvider() !== $this) {
            $tProductHost->setTAProductProvider($this);
        }

        $this->tProductHost = $tProductHost;

        return $this;
    }
}
