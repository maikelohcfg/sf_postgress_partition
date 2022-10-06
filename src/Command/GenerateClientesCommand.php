<?php

namespace App\Command;

use App\Entity\Cliente;
use App\Entity\Venta;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name: 'app:generate-clientes',
    description: 'Generate customer random for an specific year'
)]
class GenerateClientesCommand extends Command
{
    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('year', InputArgument::REQUIRED, 'Year')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $year = $input->getArgument('year');
        $io->note(sprintf('Generating customers for year: %s', $year));
        $faker = Factory::create();
        $customersNumber = rand(40, 100);
        $booksNumber     = rand(round($customersNumber/2), round($customersNumber*2));
        $yearInstance = Carbon::create($year);

        for ($x = 0; $x<$customersNumber; $x++){
            $randomDay = rand(1, 365);
            $randomSecond = rand(1, 60 * 60 * 24);
            $currentDate  = $yearInstance->copy()->dayOfYear($randomDay)->seconds($randomSecond);
            $cliente = new Cliente();
            $cliente
                ->setId(Uuid::v4())
                ->setName($faker->name())
                ->setCreated($currentDate)
            ;
            for ($y = 0; $y < $booksNumber; $y++){
                $venta = new Venta();
                $name = $faker->sentence(5);
                $venta
                    ->setExperiencia(substr($name, 0, strlen($name) - 1))
                    ->setPrecio($faker->randomDigitNot(0))
                    ->setCreated($faker->dateTime());
                $cliente->addVentas($venta);
            }
            $this->em->persist($cliente);
            $io->writeln(sprintf("%s : %s",
                $cliente->getName(),
                Carbon::create($cliente->getCreated())->toISOString()
            ));
        }
        $this->em->flush();
        $io->success('Customers generated sucesfully');

        return Command::SUCCESS;
    }
}
