<?php

namespace App\Command;

use App\Service\CarService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CarImportCommand extends Command
{
    public function __construct(
        protected CarService $carService
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('car:import')
            ->addArgument('pathXls', InputArgument::REQUIRED, 'Path required');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pathXls = $input->getArgument('pathXls');
        $this->carService->importCars($pathXls);
        return Command::SUCCESS;
    }

}