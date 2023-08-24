<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\ProviderRepository;
use App\Service\Provider\RealisaPrint\BaseRealisaPrint;
use App\Service\TAProductProviderService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:save-product',
    description: 'Save all product in RealisaPrint',
    hidden: false
)]
class SaveProductRealisaPrintCommand extends Command
{
    public function __construct(
        private BaseRealisaPrint $baseRealisaPrint,
        private TAProductProviderService $productProviderService,
        private ProviderRepository $providerRepository
    ){
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        do{
            try{
                $relance = false;

                $provider = $this->providerRepository->findOneBy(['name'=>"REALISAPRINT"]);

                $data = $this->baseRealisaPrint->_apiProduct();
                $dataCount = count($data["products"]);

                $progressBar = new ProgressBar($output, $dataCount);


                foreach ($data["products"] as $idSource=>$libelle) {
                    $this->productProviderService->save($provider, $idSource, $libelle);
                    $progressBar->advance();
                }
                $progressBar->finish();

            }catch (\Throwable ){
                //wait seconds
                sleep(5);
               $relance= true;
            }
        }
        while($relance);

        $io->success('All Products RealisaPrint save very well!');

        return Command::SUCCESS;
    }
}
