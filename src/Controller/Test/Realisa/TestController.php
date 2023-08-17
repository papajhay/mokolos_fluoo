<?php declare(strict_types=1);

namespace App\Controller\Test\Realisa;

use App\Entity\TOption;
use App\Entity\TOptionValue;
use App\Repository\TOptionRepository;
use App\Repository\TOptionValueRepository;
use App\Service\Provider\RealisaPrint\BaseRealisaPrint;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function index(TOptionRepository $optionRepository, TOptionValueRepository $optionValueRepository, PersistenceManagerRegistry $doctrine): Response
    {
        /** @var TOption $option */
        $option = $optionRepository->find(2);

        $optionValue = new TOptionValue();
        $optionValue->setLibelle('valeur10')
            ->setIsActif(1)
            ->setTOption($option);

        $arrayData = $optionValue->toArray($doctrine);
        $entity = $optionValueRepository->insert($arrayData);

        return new Response();
    }
    #[Route('/product', name: 'api_test', methods:['get'])]
    public function getProduct(BaseRealisaPrint $baseRealisaPrint): Response
    {
        $data=$baseRealisaPrint->_apiProduct();
        return $this->json($data);
    }

    #[Route('/price', name: 'get_price', methods:['get'])]
    public function getPrice(BaseRealisaPrint $baseRealisaPrint): Response
    {
       $price = $baseRealisaPrint->_apiGetPrice(11667, 2 );
        return $this->json($price);
    }

}
