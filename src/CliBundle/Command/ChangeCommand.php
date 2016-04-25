<?php

namespace CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ChangeCommand extends ContainerAwareCommand {
    protected function configure(){
        $this
            ->setName('change:branch')
            ->addOption('branch', null, InputOption::VALUE_REQUIRED, 'Branch to change to')
            ->addOption('site', null, InputOption::VALUE_REQUIRED, 'Which site to update')
            ->setDescription('Change branches')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
       // - chdir to the requested site
        //  - use the config/directories.yml file to determine where
        // - git pull
        $container = $this->getApplication()->getKernel()->getContainer();
        $search_path = $container->getParameter('kernel.root_dir');
        $directories = Yaml::parse(file_get_contents($search_path .'/../src/CliBundle/Resources/config/directories.yml'));
        $site = $input->getOption('site');
        $exit = 1;

        for($i = 0; $i < sizeof($directories); $i++){
            preg_match('/'. preg_quote($site) .'\b/', $directories[$i], $matches);

            if(sizeof($matches) > 0){
                if(strlen($matches[0]) > 0){
                    chdir($directories[$i]);
                    
                    $process = new Process("git checkout {$input->getOption('branch')} --quiet &> /dev/null");
                    $process->run();

                    if($process->isSuccessful()){
                        $exit = 0;
                    }
                }
            }
        }

        return $exit;
    }
}
