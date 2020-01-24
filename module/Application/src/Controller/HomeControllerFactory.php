<?php

namespace Application\Controller;

use Psr\Container\ContainerInterface;

class HomeControllerFactory
{
    public function __invoke(ContainerInterface $container) : HomeController
    {
        return new HomeController($container->get('config'));
    }
}
