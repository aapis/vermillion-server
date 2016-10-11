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

class CreateController extends FOSRestController
{
    const COMMAND = 'vermillion:create';

    public function indexAction($slug) {
        $verify = $this->get('request_authorization');
        if(!$verify->authenticated()){
            $error = new Json();
            $error->setMessage($verify->getMessage());
            $error->setCode(500);

            $error_view = $this->view($error, $error->getCode());
            return $this->handleView($error_view);
        }

        $json = new Json();
        $exitCode = $this->_get_command_exit_code($slug);

        // since we can't trap exceptions thrown in shell commands,
        // we check the return value and set the appropriate error message here
        switch($exitCode){
            case 2:
                $json->setMessage("Configuration file not found, please run vermillion:update-config");
                $json->setTitle("Error");
                $json->setCode(400);
                break;
            case 3:
                $json->setMessage("There are no sites configured on this server.");
                $json->setTitle("Error");
                $json->setCode(400);
                break;
            case 0:
                $json->setMessage("It worked");
                $json->setTitle("Success");
                $json->setCode(200);
                break;
            case 1:
            case 4:
            default:
                $json->setMessage("Site not found in manifest");
                $json->setTitle("Error");
                $json->setCode(500);
                break;
        }

        $view = $this->view($json, $json->getCode());

        return $this->handleView($view);
    }

    private function _get_command_exit_code($slug) {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
           'command' => self::COMMAND,
           '--site' => $slug,
        ));

        // FOR TESTING ONLY
        // $output = new BufferedOutput();
        // $application->run($input, $output);
        // var_dump($output->fetch());

        return $application->run($input, new BufferedOutput());
    }
}
