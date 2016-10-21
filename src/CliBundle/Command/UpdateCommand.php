<?php

namespace CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Process\Process;

class UpdateCommand extends ContainerAwareCommand {
    protected function configure(){
        $this
            ->setName('vermillion:update-site')
            ->addOption('site', null, InputOption::VALUE_REQUIRED, 'Which site to update')
            ->setDescription('Deploy updates from the SCM repository')
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
            $match_data = $this->_match_data($site, $directories);

            if($match_data[0]){
                chdir($match_data[1]);

                $process = new Process('git pull --quiet &> /dev/null');
                $process->run();

                if($process->isSuccessful()){
                    $exit = 0;
                }
            }else {
                $exit = 4;
            }
        }

        return $exit;
    }

    private function _match_data($site, $pool){
        $match = 0;
        $directory = "";

        for($i = 0; $i < sizeof($pool); $i++){
            if(strpos($site, "+") !== false){
                $site = str_replace("+", " ", $site);
            }

            $match = strpos($pool[$i], $site);

            if($match !== false){
                $directory = $pool[$i];
                break;
            }
        }

        return [($match !== false), $directory];
    }
}
