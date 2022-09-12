<?php

namespace App\Command;

use DateTime;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Driver\Statement as DriverStatement;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:all-custom',
    description: 'All custom.',
    hidden: false,
    aliases: ['app:all-custom']
)]
class AllCustomCommand extends Command
{
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        parent::__construct();
    }


    protected function configure(): void
    {
        $this->setHelp('This command started all custom');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Command start custom');
        $this->startCommand('app:add-count');
        $this->startCommand('app:add-price');
        $this->startCommand('app:add-product');
        return Command::SUCCESS;
    }

    protected function startCommand($command){
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => $command
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);
        $content = $output->fetch();
    }
}