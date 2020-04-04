<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools.getlaminas.org for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/LICENSE.md New BSD License
 */

namespace Documentation;

class DocumentationModel
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param sting $page
     * @return bool
     */
    public function hasPage($page)
    {
        if (
            ! isset($this->config['path'])
            || ! is_dir($this->config['path'])
        ) {
            return false;
        }

        $path = $this->getPagePath($page);
        return file_exists($path);
    }

    /**
     * @param string $page
     * @return string
     */
    public function getPageContents($page)
    {
        if (! $this->hasPage($page)) {
            return '';
        }

        $path = $this->getPagePath($page);
        return file_get_contents($path);
    }

    /**
     * @param string $page
     * @return string
     */
    protected function getPagePath($page)
    {
        $dir  = dirname($page);
        $base = basename($page, '.md');
        return sprintf(
            '%s/%s%s.md',
            $this->config['path'],
            (empty($dir) ? '' : $dir . '/'),
            $base
        );
    }
}
