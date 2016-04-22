<?php
namespace CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NewRepoCommand extends ContainerAwareCommand {
    protected function configure(){
        $this
            ->setName('new:repo')
            ->setDescription('Creates a new empty repository')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        // - download CMS (wordpress/etc) if required
        // - new folder in /repos
        // - git init --bare
    }
}
