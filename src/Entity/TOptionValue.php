<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TOptionValueRepository;
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

    #[ORM\Column(type: Type::string)]
    private string $libelle;

    #[ORM\Column]
    private ?int $idOption = null;

    #[ORM\Column]
    private ?int $isActif = null;

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

    public function getIdOption(): ?int
    {
        return $this->idOption;
    }

    public function setIdOption(int $idOption): static
    {
        $this->idOption = $idOption;

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
}
