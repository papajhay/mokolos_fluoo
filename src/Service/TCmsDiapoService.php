<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TCmsDiapo;
use App\Repository\TCmsDiapoRepository;

class TCmsDiapoService
{
    public function __construct(
        private TCmsDiapoRepository $tCmsDiapoRepository
    ) {
    }

    /**
     * Creer un nouvel objet "TCmsDiapo" et le retourner.
     * @param  string    $repUrl Url d'acces au repertoire du diaporama
     * @param  int       $height [=0] Hauteur en px
     * @param  int       $width  [=0] Largeur en px
     * @param  int       $name   [=''] Nom du diaporama pour l'admin
     * @return TCmsDiapo Nouvel objet insere en base
     */
    public function createNew($repUrl, $height = 0, $width = 0, $name = '')
    {
        $tCmsDiapo = new TCmsDiapo();
        $tCmsDiapo->setRepUrl($repUrl)
                  ->setHeight($height)
                  ->setWidth($width)
                  ->setName($name);
        $this->tCmsDiapoRepository->save($tCmsDiapo);

        return $tCmsDiapo;
    }
}
