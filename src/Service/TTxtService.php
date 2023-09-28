<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TTxt;
use App\Repository\TTxtRepository;

class TTxtService
{
    public function __construct(
        private TTxtRepository $ttxtRepository
    ) {
    }

    /**
     * Crée un nouvel objet "TTxt" et le retourne.
     *
     * @param int(11) $idHost
     *
     * @return TTxt Nouvel Objet inserer un base
     */
    public function createNew($value, $idHost, $idProductHost)
    {
        $tTxt = new TTxt();

        $tTxt->setValue($value)
            ->setIdHost($idHost)
            ->setIdProductHost($idProductHost);
        $this->ttxtRepository->save($tTxt);

        return $tTxt;
    }
}
