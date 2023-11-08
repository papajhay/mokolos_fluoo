<?php declare(strict_types=1);

namespace App\Command;

use App\Entity\Provider;
use App\Entity\TOption;
use App\Enum\StatusEnum;
use App\Repository\HostsRepository;
use App\Repository\TAProductProviderRepository;
use App\Service\Provider\RealisaPrint\BaseRealisaPrint;
use App\Service\Provider\TAOptionValueProviderService;
use App\Service\TAProductOptionService;
use App\Service\TOptionService;
use App\Service\TOptionValueService;
use App\Service\TProductService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:save-configuration',
    description: 'Add a short description for your command',
)]
class SaveConfigurationBaseRealisaCommand extends Command
{
    public function __construct(
        private BaseRealisaPrint $baseRealisaPrint,
        private TAProductProviderRepository $tAProviderRepository,
        private TOptionService $tOptionService,
        private TProductService $tProductService,
        private HostsRepository $hostsRepository,
        private TOptionValueService $optionValueService,
        private TAOptionValueProviderService $optionValueProviderService,
        private TAProductOptionService $tAProductOptionService,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('host', InputArgument::REQUIRED)
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $productProviders = $this->tAProviderRepository->findAll();
        $hostName = $input->getArgument('host');
        $hostFull = $this->hostsRepository->findOneBy(['name' => $hostName]);
        $host = $hostFull->getName();

        foreach ($productProviders as $productProvider) {
            $idSource =  $productProvider->getIdSource();
            $configs = $this->baseRealisaPrint->_apiConfigurations($idSource);
            $provider = $this->entityManager->getRepository(Provider::class)->findOneBy(['id' => $productProvider->getProvider()->getId()]);
            $tProduct = $this->tProductService->createOrGetTProduct($productProvider, 3, $provider);

            foreach ($configs['variables'] as $key => $optionData) {
                $detailData = $this->baseRealisaPrint->getOptionDetail($optionData);
                $option = $this->tOptionService->createIfNotExist($key, $productProvider->getProvider(), $optionData['name'], $detailData['order'], $tProduct, $detailData['typeOption']->value, $detailData['optSpecialOption']);
                $this->storeTOptionValuesAndTOptionValueProvider($option, $optionData['values'], $productProvider->getProvider(), $key);

                $this->tAProductOptionService->createIfNotExist($tProduct, $option, $host, $detailData['defaultValue'], StatusEnum::STATUS_ACTIVE, '', '');
            }
        }

        $io->success('Configuration save very well!');

        return Command::SUCCESS;
    }

    private function storeTOptionValuesAndTOptionValueProvider(TOption $tOption, mixed $values, Provider $provider, string $key): void
    {
        if (is_array($values)) {
            foreach ($values as $elementId => $nameOptionValue) {
                $tOptionValue = $this->optionValueService->createTOptionValue($nameOptionValue, $tOption);

                $this->optionValueProviderService->createNewTAOptionValueProvider($tOptionValue, $provider, trim($key), $nameOptionValue, $tOption, '', $elementId);
            }
        }
    }
}
