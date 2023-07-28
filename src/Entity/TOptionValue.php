<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TOptionValueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TOptionValueRepository::class)]
class TOptionValue
{
    /**
     * Statut de cette valeur d'option : inactif.
     */
    // const STATUS_INACTIF = 0;

    /**
     * Statut de cette valeur d'option : actif.
     */
    // const STATUS_ACTIF = 1;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $libelle;

    #[ORM\Column]
    private ?int $isActif = null;

    #[ORM\ManyToOne(inversedBy: 'tOptionValues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TOption $TOption = null;

    #[ORM\OneToMany(mappedBy: 'TOptionValue', targetEntity: TAProductOptionValue::class)]
    private Collection $tAProductOptionValues;

    public function __construct()
    {
        $this->tAProductOptionValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getIsActif(): ?int
    {
        return $this->isActif;
    }

    public function setIsActif(int $isActif): static
    {
        $this->isActif = $isActif;

        return $this;
    }

    public function getTOption(): ?TOption
    {
        return $this->TOption;
    }

    public function setTOption(?TOption $TOption): static
    {
        $this->TOption = $TOption;

        return $this;
    }

    /**
     * @return Collection<int, TAProductOptionValue>
     */
    public function getTAProductOptionValues(): Collection
    {
        return $this->tAProductOptionValues;
    }

    public function addTAProductOptionValue(TAProductOptionValue $tAProductOptionValue): static
    {
        if (!$this->tAProductOptionValues->contains($tAProductOptionValue)) {
            $this->tAProductOptionValues->add($tAProductOptionValue);
            $tAProductOptionValue->setTOptionValue($this);
        }

        return $this;
    }

    public function removeTAProductOtionValue(TAProductOptionValue $tAProductOptionValue): static
    {
        if ($this->tAProductOptionValues->removeElement($tAProductOptionValue)) {
            // set the owning side to null (unless already changed)
            if ($tAProductOptionValue->getTOptionValue() === $this) {
                $tAProductOptionValue->setTOptionValue(null);
            }
        }

        return $this;
    }
}
