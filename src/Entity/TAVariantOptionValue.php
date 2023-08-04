<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TAVariantOptionValueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAVariantOptionValueRepository::class)]
class TAVariantOptionValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    //private ?int $idHost;
    #[ORM\ManyToOne(inversedBy: 'tAVariantOptionValues')]
    private ?Hosts $host = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?int $varOptValOrder = null;

    #[ORM\ManyToOne(inversedBy: 'tAVariantOptionValues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TProductHost $tProductHost = null;

    #[ORM\ManyToOne(inversedBy: 'tAVariantOptionValues')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TOptionValue $tOptionValue = null;

    public function getId(): ?int
    {
        return $this->id;
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


    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getVarOptValOrder(): ?int
    {
        return $this->varOptValOrder;
    }

    public function setVarOptValOrder(int $varOptValOrder): static
    {
        $this->varOptValOrder = $varOptValOrder;

        return $this;
    }
    public function getTProductHost(): ?TProductHost
    {
        return $this->tProductHost;
    }

    public function setTProductHost(?TProductHost $tProductHost): static
    {
        $this->tProductHost = $tProductHost;

        return $this;
    }

    public function getTOptionValue(): ?TOptionValue
    {
        return $this->tOptionValue;
    }

    public function setTOptionValue(?TOptionValue $tOptionValue): static
    {
        $this->tOptionValue = $tOptionValue;

        return $this;
    }


    // Todo Repository
    /**
     * Renvoi tous les variants lié à un produit host
     * @param TProduitHost $produitHost le produit host
     * @return TAVariantOptionValue[]
     */
//    public static function findAllByProduitHost(TProduitHost $produitHost)
//    {
//        return TAVariantOptionValue::findAllBy(array('id_produit_host', 'id_host'), array($produitHost->getIdProduitHost(), $produitHost->getIdHost()));
//    }


    /**
     * supprime tous les TAVariantOptionValue lié à un produit host
     * @param TProduitHost $produitHost le produit host
     */
//    public static function deleteByProduitHost(TProduitHost $produitHost)
//    {
//     //pour chaque variation
//        foreach(TAVariantOptionValue::findAllByProduitHost($produitHost) AS $variantOptionValue)
//        {
//     //on la supprime
//            $variantOptionValue->delete();
//        }
//    }

    /**
     * purge les lignes dans la base qui n'ont plus de raison d'être
     * @param TLockProcess $lockProcess objet de lockprocess pour mettre à jour les étapes
     */
//    public static function purge(TLockProcess $lockProcess)
//    {
//        $lockProcess->updateStage('Recherche des variant options values sans option value');
//
//     //recherche des produit options values dont le produit n'existe plus
//        $sql = 'SELECT *
//			FROM ' . self::$_SQL_TABLE_NAME . '
//			WHERE id_option_value NOT IN (
//			SELECT id_option_value
//			FROM ' . TOptionValue::$_SQL_TABLE_NAME . ')';
//
//     //pour chaque produit option value
//        foreach(self::findAllSql($sql) AS $variantOptionValue)
//        {
//            $lockProcess->updateStage('Suppression variant option value ' . $variantOptionValue->getIdHost() . ', produitHost ' . $variantOptionValue->getIdProduithost() . ', option value ' . $variantOptionValue->getIdOptionValue());
//
//     //on la supprime
//            $variantOptionValue->delete();
//        }
//    }

    //    Todo  Service
    /**
     * Cré un nouvel objet "TAVariantOptionValue" et le retourne
     * @param mediumint(9) $idProduitHost id du produit host
     * @param int(11) $idOptionValue id de la valeur de l'option
     * @param varchar(5) $idHost id du site
     * @param tinyint(4) $varOptValIsActif 0 : option inactive. 1 : option active 2 : option active uniquement sur la France
     * @param tinyint(4) $varOptValOrdre ordre d'affichage de l'option
     * @return TAVariantOptionValue Nouvel Objet inseré en base
     */
//    public static function createNew($idProductHost, $idOptionValue, $idHost, $IsActive, $varOptValOrder)
//    {
//        $o = new self();
//        $o->setIdProductHost($idProductHost);
//        $o->setIdOptionValue($idOptionValue);
//        $o->setIdHost($idHost);
//        $o->seIsActive($IsActive);
//        $o->setVarOptValOrder($varOptValOrder);
//        $o->save();
//
//        return $o;
//    }
}
