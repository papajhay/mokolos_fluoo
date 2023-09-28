<?php declare(strict_types=1);

namespace App\Controller\Test\Realisa;

use App\Entity\TAProductProvider;
use App\Entity\TOption;
use App\Entity\TOptionValue;
use App\Entity\TProduct;
use App\Repository\TAProductProviderRepository;
use App\Repository\TOptionRepository;
use App\Repository\TOptionValueRepository;
use App\Service\Provider\RealisaPrint\BaseRealisaPrint;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Mapping\MappingException;
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
        $optionValueRepository->insert($arrayData);

        // dump($entity);
        return new Response();
    }

    #[Route('/option', name: 'app_testOption')]
    public function getOption(BaseRealisaPrint $baseRealisaPrint): Response
    {
        $optionData = [
            'name' =>  'Hauteur (cm)',
            'type' =>  'float',
            'default' =>  false,
            'values' => false,
            'readonly' => false,
            'quantity' => false,
            'production_time' => false,
            'area' => 1,
            'position' => 111,
        ];

        $result = $baseRealisaPrint->getOptionDetail($optionData);

        return new Response();
    }

    #[Route('/apiShowVariable', name: 'app_testShowVariable')]
    public function apiShowVariable(BaseRealisaPrint $baseRealisaPrint, TAProductProviderRepository $productProviderRepository): Response
    {
        // $parametre = $baseRealisaPrint->_parametersForApi();
        $parametre = [
            'country' => 'FR',
            'VARTICLE_16818_' => [
                'name' =>  'Hauteur (cm)',
                'type' =>  'float',
                'default' =>  false,
                'values' => false,
                'readonly' => false,
                'quantity' => false,
                'production_time' => false,
                'area' => 1,
                'position' => 111,
            ],
        ];

        $productprovider = $productProviderRepository->findOneBy(['labelSource' => 'Affiche', 'idSource' => 231, 'idGroup' => 838]);

        $data = $baseRealisaPrint->_apiShowVariables($productprovider, $parametre);

        return $this->json($data);
    }

    #[Route('/apiSaveConfiguration', name: 'app_testSaveConfiguration')]
    public function apiSaveConfiguration(BaseRealisaPrint $baseRealisaPrint, TAProductProviderRepository $productProviderRepository): Response
    {
        $parametre = [
            'country' => 'FR',
            'VARTICLE_16818_' => [
                'name' =>  'Hauteur (cm)',
                'type' =>  'float',
                'default' =>  false,
                'values' => false,
                'readonly' => false,
                'quantity' => false,
                'production_time' => false,
                'area' => 1,
                'position' => 111,
            ],
        ];

        $productprovider = $productProviderRepository->findOneBy(['labelSource' => 'Affiche', 'idSource' => 231, 'idGroup' => 838]);

        $data = $baseRealisaPrint->_apiSaveConfiguration($productprovider, $parametre);

        return $this->json($data);
    }

    #[Route('/product', name: 'api_test', methods: ['get'])]
    public function getProduct(BaseRealisaPrint $baseRealisaPrint): Response
    {
        $data = $baseRealisaPrint->_apiProduct();

        return $this->json($data);
    }

    #[Route('/price', name: 'get_price', methods: ['get'])]
    public function getPrice(BaseRealisaPrint $baseRealisaPrint): Response
    {
        $price = $baseRealisaPrint->_apiGetPrice(11667, 2);

        return $this->json($price);
    }

    #[Route('/configurations', name: 'api_configurations', methods: ['get'])]
    public function getConfigurations(BaseRealisaPrint $baseRealisaPrint): Response
    {
        $product = new TProduct();
        $productProvider = new TAProductProvider();
        $productProvider->setIdSource(231);
        $product->setTAProductProvider($productProvider);

        $data = $baseRealisaPrint->_apiConfigurations($product);

        return $this->json($data);
    }
}
