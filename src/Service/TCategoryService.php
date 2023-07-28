<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TCategory;

use App\Repository\TCategoryRepository;

class TCategoryService
{
    public function __construct(
        private TCategoryRepository $tCategoryRepository
    ) {
    }

    /**
     * Cre un nouvel objet "TCategorie" et le retourne
     * @param string $idHost		Identifiant du site
     * @param int $order			[=0] Order
     * @return TCategory Nouvel Objet insere en base
     */
    public function createNew($idHost, $order = 0)
    {
        $tCategory = new TCategory();
        $tCategory->setIdHost($idHost)
                  ->setOrder($order);
        $this->tCategoryRepository->save($tCategory);

        return $tCategory;
    }
}