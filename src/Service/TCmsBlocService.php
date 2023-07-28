<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TCmsBloc;
use App\Repository\TCmsBlocRepository;

class TCmsBlocService
{
    public function __construct(
        private TCmsBlocRepository $tCmsBlocRepository
    ) {
    }

    /**
     * Crer un nouvel objet "TCmsBloc" et le retourner
     * @param int(11) $idCmsBlocType    Identifiant du type de bloc CMS
     * @param text $cmsBloHtml          Contenu HTML du bloc CMS
     * @param int(11) $idCmsDiapo       [=0] Identifiant du diaporama du bloc CMS
     * @return TCmsBloc Nouvel Objet insere en base
     */
    public function createNew($idType, $content, $idCmsDiapo = 0)
    {
        $tCmsBloc = TCmsBloc();
        $tCmsBloc->setIdCmsBlocType($idType)
                 ->setCmsBloHtml($content)
                 ->setIdCmsDiapo($idCmsDiapo);
        $this->tCmsBlocRepository->save($tCmsBloc);

        return $tCmsBloc;
    }
}