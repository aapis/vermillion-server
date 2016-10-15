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

        $input = $input->getOption('name');
        $base_path = $this->getContainer()->getParameter("base_path");

        if($input){
            chdir($base_path);

            $process = new Process("mkdir -p {$input} &> /dev/null");
            $process->run();

            if($process->isSuccessful()){
                $exit = 0;
            }
        } else {
            $exit = 2;
        }

        return $exit;
    }
}
