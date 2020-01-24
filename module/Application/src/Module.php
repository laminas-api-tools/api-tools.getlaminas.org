<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools.getlaminas.org for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/LICENSE.md New BSD License
 */

namespace Application;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
