<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools.getlaminas.org for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/LICENSE.md New BSD License
 */

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class HomeController extends AbstractActionController
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function indexAction()
    {
        return new ViewModel([
            'version' => $this->config['apigility']['version'],
            'zip'     => $this->config['links']['zip']
        ]);
    }

    public function installAction()
    {
        $version = $this->params()->fromRoute('version');
        if (empty($version)) {
            $installer = file_get_contents(__DIR__ . '/../../../../bin/install.php');
        } else {
            $installer = file_get_contents(__DIR__ . '/../../../../bin/install.php.dist');
            $installer = str_replace('%VERSION%', $version, $installer);
        }

        return $this->getResponse()->setContent($installer);
    }
}
