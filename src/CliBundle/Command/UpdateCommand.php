<?php

namespace CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class UpdateCommand extends ContainerAwareCommand {
    protected function configure(){
        $this
            ->setName('vermillion:update-site')
            ->addOption('site', null, InputOption::VALUE_REQUIRED, 'Which site to update')
            ->setDescription('Deploy updates from the SCM repository')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        // - chdir to the requested site
        //  - use the config/directories.yml file to determine where
        // - git pull
        $search_path = getcwd();
        $directories = Yaml::parse(file_get_contents($search_path .'/../src/CliBundle/Resources/config/directories.yml'));
        $site = $input->getOption('site');
        $exit = 1;

        for($i = 0; $i < sizeof($directories); $i++){
            preg_match('/'. preg_quote($site) .'\b/', $directories[$i], $matches);

            if(sizeof($matches) > 0){
                if(strlen($matches[0]) > 0){
                    chdir($directories[$i]);
                    
                    $process = new Process('git pull --quiet &> /dev/null');
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
