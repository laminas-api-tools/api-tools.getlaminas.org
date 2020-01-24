<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools.getlaminas.org for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/LICENSE.md New BSD License
 */

namespace Documentation;

class DocumentationModelFactory
{
    public function __invoke($services)
    {
        $config = [];
        if ($services->has('config')) {
            $config = $this->getConfigViaService($config, $services->get('config'));
        }

        return new DocumentationModel($config);
    }

    protected function getConfigViaService(array $config, $allConfig)
    {
        if (! isset($allConfig['api-tools-documentation'])) {
            return $config;
        }
        return $allConfig['api-tools-documentation'];
    }
}
