<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TCategoryRepository;
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
//    $ordre
    private ?int $order = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

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

//    Todo: repository
    /**
     * Retourne l'ensemble des categories d'un site
     * @param string $idHost	Identifiant du site
     * @return TCategorie[] Liste des categories du site
     */
//    public static function findAllByIdHost($idHost)
//    {
//        return self::findAllBy(array('id_host'), array($idHost), array('ordre'));
//    }
}
