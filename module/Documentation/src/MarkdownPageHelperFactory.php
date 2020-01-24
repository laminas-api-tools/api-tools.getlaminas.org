<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools.getlaminas.org for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/LICENSE.md New BSD License
 */

namespace Documentation;

use Laminas\ServiceManager\AbstractPluginManager;

class MarkdownPageHelperFactory
{
    public function __invoke($helpers)
    {
        if (! $helpers instanceof AbstractPluginManager) {
            $helpers = $helpers->get('ViewHelperManager');
        }
        return new MarkdownPageHelper($helpers->get('url'));
    }
}
