<?php

namespace CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Process\Process;

class CreateCommand extends ContainerAwareCommand {
    protected function configure(){
        $this
            ->setName('vermillion:create')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'Name of the directory this will create')
            ->setDescription('Creates a new directory on the server')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $exit = 1;

        if(!$file = @file_get_contents('/tmp/vermillion-directories.yml')){
            return 2;
        }

        $directories = Yaml::parse($file);
        $site = $input->getOption('site');

        if(sizeof($directories) === 0){
            return 3;
        }else {
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
                } else {
                    $exit = 4;
                }
            }
        }

        return $exit;
    }
}
