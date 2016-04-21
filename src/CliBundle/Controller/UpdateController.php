<?php

namespace CliBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializerBuilder;
use CliBundle\Entity\Json;

class UpdateController extends FOSRestController
{
    public function indexAction()
    {

        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
           'command' => 'update:site',
        ));
        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        $json = new Json();
        //$output->setMessage("It worked");
        //$output->setTitle("Success");
        $json->setData($output->fetch());

        $view = $this->view($json, 200);

        return $this->handleView($view);
    }
}
