<?php

namespace App\Entity;

use App\Repository\TAHostCmsBlocRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TAHostCmsBlocRepository::class)]
class TAHostCmsBloc
{
    /**
     * Identifiant du site
     * @var string
     */
//    private $idHost;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
//     Todo : relation
    private ?string $tCmsBloc = null;

    #[ORM\Column(length: 255)]
//    Todo : relation
    private ?string $tCmsDiapo = null;

    /**
     * Getter pour l'attribut de l'identifiant du site
     * @return int
     */
//    public function getIdHost()
//    {
//        return $this->idHost;
//    }


    /**
     * Setter pour l'attribut de l'identifiant du site
     * @param int $idHost       Identifiant du site
     * @return TAHostCmsBloc
     */
//    public function setIdHost($idHost)
//    {
//        $this->idHost = $idHost;
//        return $this;
//    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTCmsBloc(): ?string
    {
        return $this->tCmsBloc;
    }

    public function setTCmsBloc(string $tCmsBloc): static
    {
        $this->tCmsBloc = $tCmsBloc;

        return $this;
    }

    public function getTCmsDiapo(): ?string
    {
        return $this->tCmsDiapo;
    }

    public function setTCmsDiapo(string $tCmsDiapo): static
    {
        $this->tCmsDiapo = $tCmsDiapo;

        return $this;
    }
}
