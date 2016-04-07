<?php
namespace CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DatabaseSyncCommand extends ContainerAwareCommand {
    protected function configure(){
        $this
            ->setName('sync:db')
            ->setDescription('Update the configuration file')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        
    }
}
