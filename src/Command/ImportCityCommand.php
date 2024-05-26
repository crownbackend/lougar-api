<?php

namespace App\Command;

use App\Entity\City;
use App\Helpers\ImportCSV;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsCommand(
    name: 'app:import-city',
    description: 'Add a short description for your command',
)]
class ImportCityCommand extends Command
{
    public function __construct(private ImportCSV $importCSV,
                                private ParameterBagInterface $parameterBag,
                                private EntityManagerInterface $entityManager,
                                private SluggerInterface $slugger)
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

        $path = $this->parameterBag->get('kernel.project_dir') . '/data/city.csv';
        $data = $this->importCSV->importCsv($path);
        $batchSize = 200;
        $i = 0;
        foreach ($data as $cityData) {
            $city = new City();
            $city->setCreatedAt(new \DateTimeImmutable());
            $city->setIsActif(true);
            $city->setName($cityData['name']);
            $city->setslug($this->slugger->slug($cityData['slug']));
            $city->setLatitude($cityData['gps_lat']);
            $city->setLongitude($cityData['gps_lng']);
            if($cityData['zip_code']) {
                $city->setPostalCode($cityData['zip_code']);
            } else {
                $city->setPostalCode('99');
            }
            $this->entityManager->persist($city);

            ++$i;
            $io->info($cityData["name"]);
            if (($i % $batchSize) === 0) {
                $io->info($i. ' batch size');
                $this->entityManager->flush(); // Executes all updates.
                $this->entityManager->clear(); // Detaches all objects from Doctrine!
            }
        }
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
