<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TCmsPage;
use App\Repository\TCmsPageRepository;

class TCmsPageService
{
    public function __construct(
        private TCmsPageRepository $tCmsPageRepository
    ) {
    }

    /**
     * Cre un nouvel objet "TCmsPage" et le retourne.
     * @param  string   $idHost Identifiant du site
     * @return TCmsPage Nouvel Objet insere en base
     */
    public function createNew($idHost, $url, $metaTitle, $metaDescription, $title, $content, $statut = 0)
    {
        $tCmsPage = new TCmsPage();
        $tCmsPage->setIdHost($idHost)
                 ->setCmsPagUrl($url)
                 ->setCmsPagMetaTitle($metaTitle)
                 ->setCmsPagMetaDescription($metaDescription)
                 ->setCmsPagTitle($title)
                 ->setCmsPagContent($content)
                 ->setCmsPagStatut($statut);
        $this->tCmsPageRepository->save($tCmsPage);

        return $tCmsPage;
    }
}
