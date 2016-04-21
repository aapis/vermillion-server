<?php
namespace CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends ContainerAwareCommand {
    protected function configure(){
        $this
            ->setName('update:site')
            ->addOption('site', null, InputOption::VALUE_REQUIRED, 'Which site to update')
            ->setDescription('Deploy updates from the SCM repository')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        // - chdir to the requested site
        //  - use the config/directories.yml file to determine where
        // - git pull
        var_dump($input->getOption('site'));
        return 0;
    }
}
