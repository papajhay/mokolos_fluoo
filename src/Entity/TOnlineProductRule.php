<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TOnlineProductRuleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TOnlineProductRuleRepository::class)]
class TOnlineProductRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    // old: $onlProRulProdIndex
    private ?string $productIndex = '[]';

    #[ORM\Column(length: 255)]
    // old: $onlProRulHideRow
    private ?string $hideRow ='[]';

    #[ORM\Column(length: 255)]
    // old: $onlProRulIfEveryIsSelected
    private ?string $ifEveryIsSelected = '[]';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductIndex(): ?string
    {
        return $this->productIndex;
    }

    public function setProductIndex(string $productIndex): static
    {
        $this->productIndex = $productIndex;

        return $this;
    }

    public function getHideRow(): ?string
    {
        return $this->hideRow;
    }

    public function setHideRow(string $hideRow): static
    {
        $this->hideRow = $hideRow;

        return $this;
    }

    public function getIfEveryIsSelected(): ?string
    {
        return $this->ifEveryIsSelected;
    }

    public function setIfEveryIsSelected(string $ifEveryIsSelected): static
    {
        $this->ifEveryIsSelected = $ifEveryIsSelected;

        return $this;
    }
}
