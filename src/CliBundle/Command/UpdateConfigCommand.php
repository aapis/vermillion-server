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
            ->setName('vermillion:update-config')
            ->setDescription('Update the configuration file')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $container = $this->getApplication()->getKernel()->getContainer();
        $search_path = $container->getParameter('kernel.root_dir');

        // find and format the contents of the config file
        $directories = $this->_build_directory_list($search_path);
        $formatted = "---\n";
        $formatted .= sprintf("- \"%s\"", implode($directories, "\"\n- \""));

        // find the configuration file
        try {
            $configDirectories = array($search_path.'/../src/CliBundle/Resources/config');
            $locator = new FileLocator($configDirectories);
            $file = $locator->locate('directories.yml', null, false);

            $fh = fopen($file[0], 'w');
            $result = fwrite($fh, $formatted);
            fclose($fh);
        } catch(\InvalidArgumentException $e) {
            $new_file = $search_path .'/../src/CliBundle/Resources/config/directories.yml';

            $fh = fopen($new_file, 'w');
            $result = fwrite($fh, $formatted);
            fclose($fh);
        }

        // since we are returning numeric exit codes we must account for 0 bytes
        // being written
        if(false === $result){
            return 1;
        }else {
            return 0;
        }
    }

    private function _build_directory_list($search_path){
        $search_path = "{$search_path}/../../";
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
