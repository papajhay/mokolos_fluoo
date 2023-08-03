<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TProductHostMoreViewedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TProductHostMoreViewedRepository::class)]
class TProductHostMoreViewed
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idProductHost;
    #[ORM\Column]
    private ?int $counter = null;
    #[ORM\ManyToOne(inversedBy: 'tProductHostMoreVieweds')]
    // private ?int $idHost;
    private ?Hosts $host = null;

    #[ORM\ManyToOne(inversedBy: 'tProductHostMoreVieweds')]
    private ?TProductHost $productHost = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCounter(): ?int
    {
        return $this->counter;
    }

    public function setCounter(int $counter): static
    {
        $this->counter = $counter;

        return $this;
    }

    public function setHost(?Hosts $host): static
    {
        $this->host = $host;

        return $this;
    }

    public function getProductHost(): ?TProductHost
    {
        return $this->productHost;
    }

    public function setProductHost(?TProductHost $productHost): static
    {
        $this->productHost = $productHost;

        return $this;
    }

    //TODO:  repository
    /*
     * Retourne le compteur de visualisation pour un  produit host
     * @param String $idProduitHost     Id du produit host
     * @param String $idHost            Id du site
     * @return int
     */
//    public static function getCountView($idProductHost, $idHost)
//    {
//
//        $nb = TProductHostMoreViewed::findBy(array('id_product_host', 'id_host'), array($idProduitHost, $idHost));
//
//        if($nb !== NULL)
//        {
//            return $nb->getProMorHosVieCounter();
//        }
//        else
//        {
//            return 0;
//        }
//    }

//    Todo: Service
    /*
     * Retourne le compteur de visualisation pour un  produit host
     * @param String		$idProduitHost     Id du produit host
     * @param String		$idHost            Id du site
     * @param string|NULL	$agent			   user agent pour la vérification des Bots ou NULL pour prendre celui de l'utilisateur
     * @return TRUE
     */
//    public static function updateCountView($idProduitHost, $idHost, $agent = NULL)
//    {
        // on récupére le user agent
//        $userAgent = new UserAgent($agent);

        // si on a un bot
//        if($userAgent->isRobot())
//        {
            // on ne fait rien
//            return TRUE;
//        }

        // recherche du nombre actuel
//        $nb = self::findBy(array('id_product_host', 'id_host'), array($idProduitHost, $idHost));

        // si on a déjà visité cette page
//        if($nb !== NULL)
//        {
            // on incrémente le compteur
//            $nb->setProMorHosVieCompteur($nb->getProMorHosVieCompteur() + 1)
//                ->save();
//        }
        // page jamais visité
//        else
//        {
            // on créé un nouvel enregistrement
//            $nb = new self;
//            $nb->setProMorHosVieCounter(1)
//                ->setIdProductHost($idProductHost)
//                ->setIdHost($idHost)
//                ->save();
//        }
//
//        return TRUE;
//    }
}
