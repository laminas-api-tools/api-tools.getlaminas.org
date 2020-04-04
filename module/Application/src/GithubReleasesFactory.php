<?php

namespace Application;

use Psr\Container\ContainerInterface;

class GithubReleasesFactory
{
    public function __invoke(ContainerInterface $container): GithubReleases
    {
        $config   = $container->get('config');
        $releases = $config['api-tools-releases'] ?? [];
        return new GithubReleases($releases);
    }
}
