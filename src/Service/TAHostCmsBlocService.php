<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TAHostCmsBloc;
use App\Repository\TAHostCmsBlocRepository;
use App\Repository\TCmsBlocRepository;
class TAHostCmsBlocService
{
    public function __construct(
        private TAHostCmsBlocRepository $taHostCmsBlocRepository
    ) {
    }
    /**
     * Creer un nouvel objet "TAHostCmsBloc" et le retourner
     * @param string $idHost        Identifiant du site
     * @param int $idCmsBloc        Identifiant du bloc CMS
     * @return TAHostCmsBloc Nouvel objet insere en base
     */
    public function createNew($idHost, $id)
    {
        $taHostCmsBloc = new TAHostCmsBloc();
        $taHostCmsBloc->setIdHost($idHost)
                      ->setIdCmsBloc($id);
        $this->taHostCmsBlocRepository->save($taHostCmsBloc);

        return $taHostCmsBloc;
    }

}