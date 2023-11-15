<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAProductProviderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAProductProviderRepository::class)]
class TAProductProvider extends BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tAProductProviders')]
    private ?Provider $provider;

    #[ORM\Column(length: 255)]
    private ?string $idSource;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $idGroup = null;

    #[ORM\Column(length: 255)]
    private ?string $labelSource = null;

    #[ORM\OneToOne(mappedBy: 'tAProductProvider', cascade: ['persist', 'remove'])]
    private ?TProductHost $tProductHost = null;

    #[ORM\ManyToMany(targetEntity: TProduct::class, inversedBy: 'tAProductProvider')]
    private Collection $tProducts;

    public function __construct()
    {
        $this->tProducts = new ArrayCollection();
    }

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

    public function getLabelSource(): ?string
    {
        return $this->labelSource;
    }

    public function setLabelSource(string $labelSource): static
    {
        $this->labelSource = $labelSource;

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

    /**
     * @return Collection<int, TProduct>
     */
    public function getTProducts(): Collection
    {
        return $this->tProducts;
    }

    public function addTProduct(TProduct $tProduct): static
    {
        if (!$this->tProducts->contains($tProduct)) {
            $this->tProducts->add($tProduct);
            $tProduct->addTAProductProvider($this);
        }

        return $this;
    }

    public function removeTProduct(TProduct $tProduct): static
    {
        if ($this->tProducts->removeElement($tProduct)) {
            $tProduct->removeTAProductProvider($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, TProduct>
     */
    public function getTProduct(): Collection
    {
        return $this->tProduct;
    }

    public function getIdSource(): ?string
    {
        return $this->idSource;
    }

    public function setIdSource(?string $idSource):static
    {
        $this->idSource = $idSource;

        return $this;
    }

    public function getIdGroup(): ?int
    {
        return $this->idGroup;
    }

    public function setIdGroup(?int $idGroup): static
    {
        $this->idGroup = $idGroup;

        return $this;
    }
}
