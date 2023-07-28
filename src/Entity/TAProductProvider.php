<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAProductProviderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAProductProviderRepository::class)]
class TAProductProvider
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'provider')]
    private ?TProduct $product = null;

    #[ORM\ManyToOne(inversedBy: 'tAProductProviders')]
    private ?Provider $provider = null;

    #[ORM\Column]
    private ?int $idSource = null;

    #[ORM\Column]
    private ?int $idGroup = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleSource = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?TProduct
    {
        return $this->product;
    }

    public function setProduct(?TProduct $product): static
    {
        $this->product = $product;

        return $this;
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

    public function getLibelleSource(): ?string
    {
        return $this->libelleSource;
    }

    public function setLibelleSource(string $libelleSource): static
    {
        $this->libelleSource = $libelleSource;

        return $this;
    }
}
