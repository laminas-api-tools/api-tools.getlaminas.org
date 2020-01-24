<?php

namespace Application\Controller;

use Application\GithubReleases;
use Psr\Container\ContainerInterface;

class DownloadControllerFactory
{
    public function __invoke(ContainerInterface $container) : DownloadController
    {
        return new DownloadController($container->get(GithubReleases::class));
    }
}
