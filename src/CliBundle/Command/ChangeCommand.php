<?php

namespace CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\Request;

class ChangeCommand extends ContainerAwareCommand {
    protected function configure(){
        $this
            ->setName('vermillion:change-branch')
            ->addOption('branch', null, InputOption::VALUE_REQUIRED, 'Branch to change to')
            ->addOption('site', null, InputOption::VALUE_REQUIRED, 'Which site to update')
            ->setDescription('Change branches')
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
        } else {
            for($i = 0; $i < sizeof($directories); $i++){
                preg_match('/'. preg_quote($site) .'\b/', $directories[$i], $matches);

                if(sizeof($matches) > 0){
                    if(strlen($matches[0]) > 0){
                        chdir($directories[$i]);

                        $checkout = new Process("git checkout {$input->getOption('branch')} --quiet &> /dev/null");
                        $checkout->run();

                        if($checkout->isSuccessful()){
                            $pull = new Process("git pull --quiet &> /dev/null");
                            $pull->run();

                            if($pull->isSuccessful()){
                                $exit = 0;
                            }
                        }else {
                            $exit = 5;
                        }
                    }
                }else {
                    $exit = 4;
                }
            }
        }

        return $exit;
    }
}
