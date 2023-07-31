<?php declare(strict_types=1);

namespace App\Service;

use App\Repository\TProductHostMoreViewedRepository;

class TProductHostMoreViewedService
{
    public function __construct(
        private TProductHostMoreViewedRepository $tproductHostMoreViewRepository
    ) {
    }

    /*
     * Crée un nouvel objet "TProduitHostMoreViewed" et le retourne
     *
     * @param mediumint(8) unsigned $idProduitHost id du produit host
     * @param mediumint(8) unsigned $proMorHosVieCompteur Nombre de consultation
     * @param varchar(5) $idHost Id du site
     *
     * @return TProduitHostMoreViewed Nouvel Objet inserer un base
     */
    public function createNew($idProductHost, $counter, $idHost)
    {
        $tProductHostMoreViewed = new TProductHostMoreViewed();

        $tProductHostMoreViewed->setIdProductHost($idProductHost)
                               ->setProMorHosVieCounter($counter)
                               ->setIdHost($idHost);
        $this->tproductHostMoreViewRepository->save($tProductHostMoreViewed);

        return $tProductHostMoreViewed;
    }
}