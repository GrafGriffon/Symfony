<?php

namespace App\Command;

use App\Repository\CategoryRepository;
use DateTime;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\Persistence\ManagerRegistry;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement as DriverStatement;

#[AsCommand(
    name: 'app:add-count',
    description: 'Add count.',
    hidden: false,
    aliases: ['app:add-cout']
)]
class CreateCountCommand extends Command
{
    protected $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->setHelp('This command add count');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Command start');
        $em = $this->doctrine->getManager('customer');
        $conn = $em->getConnection();
        $sql='INSERT INTO count (count, date_create, date_update) VALUES (:count, :dateCreate, :dateUpdate)';
        /** @var DriverStatement $stmt */
        $stmt=$conn->prepare($sql);
        $generator = Factory::create("en_EN");
        for ($i=1; $i<=1000; $i++){
            $stmt->execute(
                [
                    'count'=>$generator->numberBetween(25, 1000),
                    'dateCreate'=>$generator->date(),
                    'dateUpdate'=>(new DateTime())->format('Y-m-d')
                ]
            );
        }
        return Command::SUCCESS;
    }
}