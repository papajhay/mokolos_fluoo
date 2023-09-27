<?php declare(strict_types=1);

namespace App\Entity;

use App\Enum\StatusEnum;
use App\Repository\TAProductOptionValueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAProductOptionValueRepository::class)]
class TAProductOptionValue
{
//    Deplacés vers StatusEnum
    /**
     * Statut de cette valeur d'option : inactif.
     */
//    public const STATUS_INACTIF = 0;

    /**
     * Statut de cette valeur d'option : actif.
     */
//    public const STATUS_ACTIF = 1;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "integer", enumType: StatusEnum::class)]
    private StatusEnum $status = StatusEnum::STATUS_ACTIVE;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?int $order = null;

    #[ORM\Column]
    // date de la derniére fois ou l'on a vu ce produit option. NULL pour une date inconnu
    private ?\DateTime $dateLastSeen = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $localised = [];

    #[ORM\Column]
    // objet dateheure de la dernier vue de notre objet. FALSE si inconnu
    private ?\DateTime $datetimeLastSeen = null;

    #[ORM\ManyToOne(inversedBy: 'tAProductOptionValues')]
    private ?TOptionValue $tOptionValue = null;

    ///??????
    #[ORM\ManyToOne(targetEntity: TAProductOption::class, inversedBy: 'tAProductOptionValues')]
    private ?TAProductOption $tAProductOption = null;

    #[ORM\ManyToOne(targetEntity: Hosts::class, inversedBy: 'tAProductOptionValues')]
    // old: $idHost
    private ?Hosts $host = null;

    #[ORM\ManyToOne(targetEntity: TProduct::class, inversedBy: 'tAProductOptionValues')]
    private ?TProduct $tProduct = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDateLastSeen(): ?\DateTime
    {
        return $this->dateLastSeen;
    }

    public function setDateLastSeen(\DateTimeInterface $dateLastSeen): static
    {
        $this->dateLastSeen = $dateLastSeen;

        return $this;
    }

    public function getLocalised(): array
    {
        return $this->localised;
    }

    public function setLocalised(array $localised): static
    {
        $this->localised = $localised;

        return $this;
    }

    public function getDatetimeLastSeen(): ?\DateTimeInterface
    {
        return $this->datetimeLastSeen;
    }

    public function setDatetimeLastSeen(\DateTimeInterface $datetimeLastSeen): static
    {
        $this->datetimeLastSeen = $datetimeLastSeen;

        return $this;
    }

    public function getTOptionValue(): ?TOptionValue
    {
        return $this->tOptionValue;
    }

    public function setTOptionValue(?TOptionValue $TOptionValue): static
    {
        $this->tOptionValue = $TOptionValue;

        return $this;
    }

    public function getTAProductOption(): ?TAProductOption
    {
        return $this->tAProductOption;
    }

    public function setTAProductOption(?TAProductOption $tAProductOption): static
    {
        $this->tAProductOption = $tAProductOption;

        return $this;
    }

    public function getHost(): ?hosts
    {
        return $this->host;
    }

    public function setHost(?hosts $host): self
    {
        $this->host = $host;

        return $this;
    }

    public function getStatus(): StatusEnum
    {
        return $this->status;
    }

    public function setStatus(StatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(?int $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getTProduct(): ?TProduct
    {
        return $this->tProduct;
    }

    public function setTProduct(?TProduct $tProduct): self
    {
        $this->tProduct = $tProduct;

        return $this;
    }
}
