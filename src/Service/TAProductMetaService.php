<?php declare(strict_types=1);

namespace App\Service;

use App\Repository\TAProductMetaRepository;

class TAProductMetaService
{
    public function __construct(
        private TAProductMetaRepository $taProductMetaRepository
    ) {
    }

    /**
     *
     * @param type $proMetaIdParent
     * @param type $proMetaIdChild
     */
    public function createNew($idParent, $idChild, $idHost)
    {
    // on créé un nouvel objet
        $productMeta = new TAProductMeta();

    // on met à jour et on enregistre
        $productMeta->setIdParent($idParent)
                    ->setIdChild($idChild)
                    ->setIdHost($idHost);
        $this->taProductMetaRepository->save($productMeta);
    }
}