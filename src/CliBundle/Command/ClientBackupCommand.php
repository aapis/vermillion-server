<?php
namespace CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClientBackupCommand extends ContainerAwareCommand {
    protected function configure(){
        $this
            ->setName('backup:client')
            ->setDescription('Update the configuration file')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){

    }
}
