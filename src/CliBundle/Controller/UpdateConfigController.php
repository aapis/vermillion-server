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
    const COMMAND = 'config:update';

    public function indexAction() {
        $exitCode = $this->_get_command_exit_code();
        $json = new Json();

        if($exitCode === 0){
            $json->setMessage("It worked");
            $json->setTitle("Success");
            $json->setCode(200);
        }else {
            $json->setMessage("An error occurred");
            $json->setTitle(":(");
            $json->setCode(400);
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

        return $application->run($input, new BufferedOutput());
    }
}
