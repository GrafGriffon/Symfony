<?php

namespace App\Command;

use App\Handler\ExternalHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-product',
    description: 'import product.',
    hidden: false,
    aliases: ['app:import-product']
)]
class ImportCommand extends Command
{
protected $handler;
protected $logger;

    public function __construct(
        ExternalHandler $handler,
        LoggerInterface $logger
    )
    {
        $this->handler = $handler;
        $this->logger = $logger;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->setHelp('This command add products');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Command start upload external data');
//        $this->logger->info('I just got the logger');
//        $this->logger->notice('I just got the logger');
//        $this->logger->debug('I just got the logger');
//        $this->logger->error('I just got the logger');
        $this->handler->uploadData();
        $output->writeln(memory_get_peak_usage()/1024/1024);
        return Command::SUCCESS;
    }
}