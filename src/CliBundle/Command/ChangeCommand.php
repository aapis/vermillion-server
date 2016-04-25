<?php

namespace CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ChangeCommand extends ContainerAwareCommand {
    protected function configure(){
        $this
            ->setName('change:branch')
            ->addOption('branch', null, InputOption::VALUE_REQUIRED, 'Branch to change to')
            ->setDescription('Change branches')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $exit = shell_exec("git checkout {$input->getOption('branch')}");
        
        return $exit;
    }
}
