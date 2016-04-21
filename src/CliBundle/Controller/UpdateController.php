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

class UpdateController extends FOSRestController
{
    public function indexAction($slug) {
        $exitCode = $this->_execute_command($slug);

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

    private function _execute_command($slug) {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
           'command' => 'update:site',
           '--site' => $slug,
        ));
        // You can use NullOutput() if you don't need the output
        $output = new NullOutput();
        $application->run($input, $output);

        return $application->run($input, $output);
    }
}
