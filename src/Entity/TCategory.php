<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TCategoryRepository::class)]
class TCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    //  $ordre
    private ?int $order = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'tCategories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hosts $host = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: TAProductHostCategory::class)]
    private Collection $tAProductHostCategories;

    public function __construct()
    {
        $this->tAProductHostCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setOrder(int $order): static
    {
        $this->order = $order;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getHost(): ?Hosts
    {
        return $this->host;
    }

    public function setHost(?Hosts $host): static
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return Collection<int, TAProductHostCategory>
     */
    public function getTAProductHostCategories(): Collection
    {
        return $this->tAProductHostCategories;
    }

    public function addTAProductHostCategory(TAProductHostCategory $tAProductHostCategory): static
    {
        if (!$this->tAProductHostCategories->contains($tAProductHostCategory)) {
            $this->tAProductHostCategories->add($tAProductHostCategory);
            $tAProductHostCategory->setCategory($this);
        }

        return $this;
    }

    public function removeTAProductHostCategory(TAProductHostCategory $tAProductHostCategory): static
    {
        if ($this->tAProductHostCategories->removeElement($tAProductHostCategory)) {
            // set the owning side to null (unless already changed)
            if ($tAProductHostCategory->getCategory() === $this) {
                $tAProductHostCategory->setCategory(null);
            }
        }

        return $this;
    }

    //    Todo: repository
    /*
     * Retourne l'ensemble des categories d'un site
     * @param string $idHost	Identifiant du site
     * @return TCategorie[] Liste des categories du site
     */
    //    public static function findAllByIdHost($idHost)
    //    {
    //        return self::findAllBy(array('id_host'), array($idHost), array('ordre'));
    //    }
}
