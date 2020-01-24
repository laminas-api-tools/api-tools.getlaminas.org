<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools.getlaminas.org for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/LICENSE.md New BSD License
 */

namespace Documentation;

use Laminas\ServiceManager\AbstractPluginManager;

class DocumentationControllerFactory
{
    public function __invoke($services)
    {
        if ($services instanceof AbstractPluginManager) {
            $services = $services->getServiceLocator() ?: $services;
        }
        return new DocumentationController($services->get('Documentation\Model'));
    }
}
