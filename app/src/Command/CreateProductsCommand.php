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
    name: 'app:add-product',
    description: 'Add product.',
    hidden: false,
    aliases: ['app:add-product']
)]
class CreateProductsCommand extends Command
{
    protected $doctrine;

    private $repository;

    public function __construct(CategoryRepository $repository, ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->repository = $repository;

        parent::__construct();
    }


    protected function configure(): void
    {
        $this->setHelp('This command add products');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Command start');
//        $output->writeln('Username: '.$input->getArgument('username'));
        $em = $this->doctrine->getManager('customer');
        $conn = $em->getConnection();
        $sql='INSERT INTO products (name, date_create, date_update) VALUES (:productName, :dateCreate, :dateUpdate)';
        /** @var DriverStatement $stmt */
        $stmt=$conn->prepare($sql);
        $generator = Factory::create("en_EN");
        for ($i=1; $i<=1000; $i++){
            $stmt->execute(
                [
//                    'article'=>$i,
                    'productName'=>$generator->streetName,
                    'dateCreate'=>$generator->date(),
                    'dateUpdate'=>(new DateTime())->format('Y-m-d')
                ]
            );
        }
//        $users = $conn->fetchAssociative('SELECT * FROM products');
//        $users = $conn->fetchAssociative();
        return Command::SUCCESS;
    }
}