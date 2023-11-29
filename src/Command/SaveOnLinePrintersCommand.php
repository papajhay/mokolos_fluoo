<?php declare(strict_types=1);

namespace App\Command;

use App\Repository\TAProductProviderRepository;
use App\Service\TOptionService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:save-online',
    description: 'Save all TOption and TAProductOption in OnlinePrinters',
    hidden: false
)]
class SaveOnLinePrintersCommand extends Command
{
    public function __construct(
        private TAProductProviderRepository $tAProductProviderRepository
    )
    {
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

        $output->writeln([
            'Save TOption and TAProductOption Online Printers',
        ]);

//        $tAProductProviders = $this->tAProductProviderRepository->findAll();
//        dd($tAProductProviders);

//        foreach ( $tAProductProviders as $tAProductProvider) {
//
//        }

//        $tOption = $this->tOptionService->createIfNotExist(
//            'idOptionSource',
//            $provider,
//            'OptionName',
//            100,
//            $tProduct,
//            TypeOptionEnum::TYPE_OPTION_SELECT,
//            SpecialOptionEnum::SPECIAL_OPTION_STANDARD
//        );


        $io->success('All TOption and TAProductOption OnLinePrinters save very well!');

        return Command::SUCCESS;
    }
}
