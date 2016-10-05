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

class UpdateConfigController extends FOSRestController
{
    const COMMAND = 'vermillion:update-config';

    public function indexAction() {
        $verify = $this->get('request_authorization');
        if(!$verify->authenticated()){
            $error = new Json();
            $error->setMessage($verify->getMessage());
            $error->setCode(500);

            $error_view = $this->view($error, $error->getCode());
            return $this->handleView($error_view);
        }

        $exitCode = $this->_get_command_exit_code();
        $json = new Json();

        // since we can't trap exceptions thrown in shell commands,
        // we check the return value and set the appropriate error message here
        switch($exitCode){
            case 0:
                $json->setMessage("It worked");
                $json->setTitle("Success");
                $json->setCode(200);
                break;
            case 1:
            default:
                $json->setMessage("An error occurred while looking for directories");
                $json->setTitle("Error");
                $json->setCode(500);
                break;
        }

        $view = $this->view($json, $json->getCode());

        return $this->handleView($view);
    }

    private function _get_command_exit_code() {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
           'command' => self::COMMAND,
        ));

        // $output = new BufferedOutput();
        // $application->run($input, $output);
        // var_dump($output->fetch());

        return $application->run($input, new BufferedOutput());
    }
}
