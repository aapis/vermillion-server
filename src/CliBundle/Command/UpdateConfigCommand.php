<?php
namespace CliBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Debug\Exception\ContextErrorException;

class UpdateConfigCommand extends ContainerAwareCommand {
    protected function configure(){
        $this
            ->setName('vermillion:update-config')
            ->setDescription('Update the configuration file')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        // get data from parameters.yml
        $container = $this->getApplication()->getKernel()->getContainer();
        $search_path = $container->getParameter('base_path');
        var_dump($search_path);

        // find and format the contents of the config file
        $directories = $this->_build_directory_list($search_path);
        $formatted = "---\n";
        $formatted .= sprintf("- \"%s\"", implode($directories, "\"\n- \""));

        // find the configuration file
        $fh = fopen('/tmp/vermillion-directories.yml', 'w');
        $result = fwrite($fh, $formatted);
        fclose($fh);

        // since we are returning numeric exit codes we must account for 0 bytes
        // being written
        if(false === $result){
            return 1;
        }else {
            return 0;
        }
    }

    private function _build_directory_list($search_path){
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
