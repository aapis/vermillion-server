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
            ->setDescription('Deploy updates from the SCM repository')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        // - download CMS (wordpress/etc) if required
        // - new folder in /repos
        // - git init --bare
        return "success";
    }
}
