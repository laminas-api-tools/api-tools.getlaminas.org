<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools.getlaminas.org for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/LICENSE.md New BSD License
 */

namespace Documentation;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class DocumentationController extends AbstractActionController
{
    /**
     * @var DocumentationModel
     */
    protected $model;

    public function __construct(DocumentationModel $model)
    {
        $this->model = $model;
    }

    public function indexAction()
    {
        $view = new ViewModel([
            'model' => $this->model,
        ]);
        $view->setTemplate('documentation/index');
        return $view;
    }

    public function pageAction()
    {
        $page = $this->getEvent()->getRouteMatch()->getParam('page', false);
        if (! $page || ! $this->model->hasPage($page)) {
            $response = $this->getResponse();
            $response->setStatusCode(404);
            $view = new ViewModel();
            $view->setTemplate('error/404');
            return $view;
        }

        $view = new ViewModel([
            'model' => $this->model,
            'page'  => $page,
        ]);
        $view->setTemplate('documentation/page');
        return $view;
    }
}
