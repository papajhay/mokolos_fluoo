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

    #[ORM\OneToMany(mappedBy: 'tOptionValue', targetEntity: TAOptionValueProvider::class)]
    private Collection $tAOptionValueProviders;

    #[ORM\OneToMany(mappedBy: 'tOptionValue', targetEntity: TAProductOptionValueProvider::class, orphanRemoval: true)]
    private Collection $tAProductOptionValueProviders;

    #[ORM\OneToMany(mappedBy: 'tOptionValue', targetEntity: TAVariantOptionValue::class, orphanRemoval: true)]
    private Collection $tAVariantOptionValues;

    #[ORM\OneToMany(mappedBy: 'tOptionValue', targetEntity: TCombinaison::class, orphanRemoval: true)]
    private Collection $tCombinaisons;

    #[ORM\OneToMany(mappedBy: 'optionValue', targetEntity: TTechnicalSheet::class)]
    private Collection $tTechnicalSheets;

    public function __construct()
    {
        $this->tAProductOptionValues = new ArrayCollection();
        $this->tAOptionValueProviders = new ArrayCollection();
        $this->tAProductOptionValueProviders = new ArrayCollection();
        $this->tAVariantOptionValues = new ArrayCollection();
        $this->tCombinaisons = new ArrayCollection();
        $this->tTechnicalSheets = new ArrayCollection();
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

    /**
     * @return Collection<int, TAOptionValueProvider>
     */
    public function getTAOptionValueProviders(): Collection
    {
        return $this->tAOptionValueProviders;
    }

    public function addTAOptionValueProvider(TAOptionValueProvider $tAOptionValueProvider): static
    {
        if (!$this->tAOptionValueProviders->contains($tAOptionValueProvider)) {
            $this->tAOptionValueProviders->add($tAOptionValueProvider);
            $tAOptionValueProvider->setTOptionValue($this);
        }

        return $this;
    }

    public function removeTAOptionValueProvider(TAOptionValueProvider $tAOptionValueProvider): static
    {
        if ($this->tAOptionValueProviders->removeElement($tAOptionValueProvider)) {
            // set the owning side to null (unless already changed)
            if ($tAOptionValueProvider->getTOptionValue() === $this) {
                $tAOptionValueProvider->setTOptionValue(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TAProductOptionValueProvider>
     */
    public function getTAProductOptionValueProviders(): Collection
    {
        return $this->tAProductOptionValueProviders;
    }

    public function addTAProductOptionValueProvider(TAProductOptionValueProvider $tAProductOptionValueProvider): static
    {
        if (!$this->tAProductOptionValueProviders->contains($tAProductOptionValueProvider)) {
            $this->tAProductOptionValueProviders->add($tAProductOptionValueProvider);
            $tAProductOptionValueProvider->setTOptionValue($this);
        }

        return $this;
    }

    public function removeTAProductOptionValueProvider(TAProductOptionValueProvider $tAProductOptionValueProvider): static
    {
        if ($this->tAProductOptionValueProviders->removeElement($tAProductOptionValueProvider)) {
            // set the owning side to null (unless already changed)
            if ($tAProductOptionValueProvider->getTOptionValue() === $this) {
                $tAProductOptionValueProvider->setTOptionValue(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TAVariantOptionValue>
     */
    public function getTAVariantOptionValues(): Collection
    {
        return $this->tAVariantOptionValues;
    }

    public function addTAVariantOptionValue(TAVariantOptionValue $tAVariantOptionValue): static
    {
        if (!$this->tAVariantOptionValues->contains($tAVariantOptionValue)) {
            $this->tAVariantOptionValues->add($tAVariantOptionValue);
            $tAVariantOptionValue->setTOptionValue($this);
        }

        return $this;
    }

    public function removeTAVariantOptionValue(TAVariantOptionValue $tAVariantOptionValue): static
    {
        if ($this->tAVariantOptionValues->removeElement($tAVariantOptionValue)) {
            // set the owning side to null (unless already changed)
            if ($tAVariantOptionValue->getTOptionValue() === $this) {
                $tAVariantOptionValue->setTOptionValue(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TCombinaison>
     */
    public function getTCombinaisons(): Collection
    {
        return $this->tCombinaisons;
    }

    public function addTCombinaison(TCombinaison $tCombinaison): static
    {
        if (!$this->tCombinaisons->contains($tCombinaison)) {
            $this->tCombinaisons->add($tCombinaison);
            $tCombinaison->setTOptionValue($this);
        }

        return $this;
    }

    public function removeTCombinaison(TCombinaison $tCombinaison): static
    {
        if ($this->tCombinaisons->removeElement($tCombinaison)) {
            // set the owning side to null (unless already changed)
            if ($tCombinaison->getTOptionValue() === $this) {
                $tCombinaison->setTOptionValue(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TTechnicalSheet>
     */
    public function getTTechnicalSheets(): Collection
    {
        return $this->tTechnicalSheets;
    }

    public function addTTechnicalSheet(TTechnicalSheet $tTechnicalSheet): static
    {
        if (!$this->tTechnicalSheets->contains($tTechnicalSheet)) {
            $this->tTechnicalSheets->add($tTechnicalSheet);
            $tTechnicalSheet->setOptionValue($this);
        }

        return $this;
    }

    public function removeTTechnicalSheet(TTechnicalSheet $tTechnicalSheet): static
    {
        if ($this->tTechnicalSheets->removeElement($tTechnicalSheet)) {
            // set the owning side to null (unless already changed)
            if ($tTechnicalSheet->getOptionValue() === $this) {
                $tTechnicalSheet->setOptionValue(null);
            }
        }

        return $this;
    }
}
