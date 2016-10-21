<?php

namespace CliBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializerBuilder;
use CliBundle\Entity\Json;
use Symfony\Component\Yaml\Yaml;

class ConfigurationController extends FOSRestController {
    public function indexAction($slug = null) {
        $verify = $this->get('request_authorization');
        if(!$verify->authenticated()){
            $error = new Json();
            $error->setMessage($verify->getMessage());
            $error->setCode(500);

            $error_view = $this->view($error, $error->getCode());
            return $this->handleView($error_view);
        }

        $json = new Json();
        $configData = $this->_get_config_data($slug);

        $json->setMessage(json_encode($configData));
        $json->setTitle("Success");
        $json->setCode(200);

        $view = $this->view($json, $json->getCode());

        return $this->handleView($view);
    }

    private function _get_config_data($slug) {
        $file = @file_get_contents('/tmp/vermillion-directories.yml');
        $base_path = $this->container->getParameter("base_path");
        $directories = Yaml::parse($file);

        if(sizeof($directories) > 0){
            for($i = 0; $i < sizeof($directories); $i++){
                $directories[$i] = str_replace($base_path, "", $directories[$i]);

                // directory is nested below base_path
                $parts = explode("/", $directories[$i]);

                if(sizeof($parts) > 0){
                    $directories[$i] = $parts[sizeof($parts) - 1];
                }
            }
        }

        return $directories;
    }
}
