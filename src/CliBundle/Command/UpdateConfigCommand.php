<?php
namespace CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\FileLocator;

class UpdateConfigCommand extends ContainerAwareCommand {
    protected function configure(){
        $this
            ->setName('config:update')
            ->setDescription('Update the configuration file')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $output->writeln('<comment>WARNING: This file is automatically updated by executing the `update_config` function</comment>');

        // find and format the contents of the config file
        $directories = $this->_build_directory_list();
        $formatted = "---\n";
        $formatted .= sprintf(" - \"%s\"", implode($directories, "\"\n - \""));

        // find the configuration file
        $configDirectories = array(__DIR__.'/../Resources/config');
        $locator = new FileLocator($configDirectories);
        $file = $locator->locate('directories.yml', null, false);

        $fh = fopen($file[0], 'w');
        $result = fwrite($fh, $formatted);
        fclose($fh);

        if(!$result){
            $output->writeln('<error>Config file could not be updated</error>');
        }else {
            $output->writeln(sprintf("<info>Config file updated:</info>\n%s", $file[0]));
        }
    }

    private function _build_directory_list(){
        $search_path = getenv('BASE_PATH');
        $paths = array();

        chdir($search_path);

        foreach(glob($search_path .'*', GLOB_ONLYDIR) as $file){
            $is_wp = false;

            if(file_exists($file .'/wp-config.php')){
                $is_wp = true;
            }

            if(file_exists($file .'/.git')){
                $paths[] = $file;
            }else {
                if($is_wp){
                    chdir($file .'/wp-content/themes/');

                    foreach(glob('*', GLOB_ONLYDIR) as $theme){
                        if(file_exists($theme .'/.git')){
                            $paths[] = sprintf('%s/wp-content/themes/%s', $file, $theme);
                        }
                    }
                }
            }
        }

        return $paths;
    }
}
