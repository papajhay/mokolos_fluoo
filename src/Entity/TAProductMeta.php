<?php

namespace App\Entity;

use App\Repository\TAProductMetaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAProductMetaRepository::class)]
class TAProductMeta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idParent = null;

    #[ORM\Column]
    private ?int $idChild = null;

    /**
     * Identifiant du site
     * @var string
     */
//    private $idHost;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdParent(): ?int
    {
        return $this->idParent;
    }

    public function setIdParent(int $idParent): static
    {
        $this->idParent = $idParent;

        return $this;
    }

    public function getIdChild(): ?int
    {
        return $this->idChild;
    }

    public function setIdChild(int $idChild): static
    {
        $this->idChild = $idChild;

        return $this;
    }

    /**
     * Getter pour l'attribut de l'identifiant du site
     * @return int
     */
//    public function getIdHost()
//    {
//        return $this->idHost;
//    }

//    public function setIdHost($idHost)
//    {
//        $this->idHost = $idHost;
//        return $this;
//    }

//     Todo : service
    /**
     *
     * @param type $proMetaIdParent
     * @param type $proMetaIdChild
     */

//    static public function createNew($proMetaIdParent, $proMetaIdChild, $idHost)
//    {
        // on créé un nouvel objet
//        $produitMeta = new TAProduitMeta();

        // on met à jour et on enregistre
//        $produitMeta->setProMetaIdParent($proMetaIdParent)
//            ->setProMetaIdChild($proMetaIdChild)
//            ->setIdHost($idHost)
//            ->save();
//    }

    /**
     * renvoi si un produit est un meta produit à partir de son id produit host
     * @param int $idProduitHost
     * @param string $idHost id du site
     * @return bool
     */
//    static public function isMeta($idProduitHost, $idHost)
//    {
//        return self::existBy(array('pro_meta_id_parent', 'id_host'), array($idProduitHost, $idHost));
//    }


    /**
     * renvoi si un produit est un meta child à partir de son id produit host
     * @param int $idProduitHost
     * @param string $idHost id du site
     * @return bool
     */
//    static public function isMetaChild($idProduitHost, $idHost)
//    {
//        return self::existBy(array('pro_meta_id_child', 'id_host'), array($idProduitHost, $idHost));
//    }


    /**
     * renvoi un seul numéro de meta parent id par rapport à un id produit host child
     * @param int $idProduitHostChild id du meta enfant
     * @param string $idHost id du site
     * @return int|NULL l'id ou NULL si ce produit n'a aucun parent
     */
//    static public function metaParentIdByChildId($idProduitHostChild, $idHost)
//    {
//        $produitMeta = self::findBy(array('pro_meta_id_child', 'id_host'), array($idProduitHostChild, $idHost));
//
//        // si on a pas trouvé
//        if($produitMeta == NULL)
//        {
//            return NULL;
//        }
//        // on a trouvé
//        else
//        {
//            return $produitMeta->getProMetaIdParent();
//        }
//    }
}
