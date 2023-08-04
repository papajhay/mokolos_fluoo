<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TProductHostAclRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TProductHostAclRepository::class)]
class TProductHostAcl
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $idHost = null;

    //private $idProduitHost;
    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\ManyToOne(inversedBy: 'tProductHostAcl')]
    private ?TProductHost $tProductHost = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdHost(): ?string
    {
        return $this->idHost;
    }

    public function setIdHost(string $idHost): static
    {
        $this->idHost = $idHost;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    //Todo : repository
    /**
     * renvoi tous les acl lié à un master host. la clef du tableau est de la forme 12345-ligbe
     * @param string $idHost
     * @return TProduitHostAcl
     */
//    public static function findAllForMasterHost($idHost)
//    {
//        $return = array();

        // jointure pour récupéré les master host
//        $joinList = array(array('table' => siteHost::$_SQL_TABLE_NAME, 'alias' => 'h', 'joinCondition' => 'h.host_id = t.id_host'));

        // récupération des acls
//        $allAcl = self::findAllBy(array('master_host'), array($idHost), array(), 0, $joinList);

        // pour chaque acl
//        foreach($allAcl AS $acl)
//        {
            // on la met dans notre tableau de retour avec la bonne clef
//            $return[$acl->getIdProduitHost() . '-' . $acl->getIdHost()] = $acl;
//        }
//
//        return $return;
//    }


    /**
     * renvoi tous les acl lié à un produit host
     * @param int $idProduitHost
     * @return TProduitHostAcl
     */
//    public static function findAllByIdProduitHost($idProduitHost)
//    {
//        return self::findAllBy(array('id_produit_host'), array($idProduitHost));
//    }

    /**
     * supprime tous les produits acl qui ne sont plus relié à aucun produit host
     * @param TLockProcess $lockProcess		Objet lockProcess pour les etapes
     */
//    static public function purge(TLockProcess $lockProcess)
//    {
//        $lockProcess->updateStage('Recherche des produit host acl sans produit host');

        // récupération des produit ne correspondant plus à rien
//        $sql = 'SELECT *
//			FROM ' . self::$_SQL_TABLE_NAME . '
//			WHERE id_produit_host NOT IN (
//			SELECT id_produit_host
//			FROM ' . TProduitHost::$_SQL_TABLE_NAME . '
//			GROUP BY id_produit_host)';

        // pour chaque produit host acl
//        foreach(self::findAllSql($sql) AS $produitAcl)
//        {
//            $lockProcess->updateStage('Suppression produt acl ' . $produitAcl->getIdProduitHost() . ' ' . $produitAcl->getIdHost());

            // on supprime le produit host acl
//            $produitAcl->delete();
//        }
//    }

public function getTProductHost(): ?TProductHost
{
    return $this->tProductHost;
}

public function setTProductHost(?TProductHost $tProductHost): static
{
    $this->tProductHost = $tProductHost;

    return $this;
}
}
