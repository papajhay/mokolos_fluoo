<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\TOption;
use App\Entity\TOptionValue;
use App\Repository\TOptionValueRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TOptionRepository;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class TestController extends AbstractController
{
    private TOptionRepository $optionRepository;

    public function __construct(TOptionRepository $optionRepository)
    {
        $this->optionRepository = $optionRepository;
    }

    /**
     * @throws Exception
     * @throws MappingException
     */
    #[Route('/test', name: 'app_test')]
    public function index(TOptionRepository $optionRepository,TOptionValueRepository $optionValueRepository,ManagerRegistry $doctrine): Response
    {
        /** @var TOption $option */
        $option = $optionRepository->find(2);

        $optionValue = new TOptionValue();
        $optionValue->setLibelle('valeur10')
            ->setIsActif(1)
            ->setTOption($option);

        $arrayData = $optionValue->toArray($doctrine);
        $entity = $optionValueRepository->insert($arrayData);
        dump($entity);
        return new Response();
    }
}
