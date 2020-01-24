<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools.getlaminas.org for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/LICENSE.md New BSD License
 */

namespace Application\Controller;

use Application\GithubReleases;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class DownloadController extends AbstractActionController
{
    /**
     * @var GithubReleases
     */
    protected $releases;

    public function __construct(/*GithubReleases*/ $releases)
    {
        $this->releases = $releases;
    }

    protected function getAside()
    {
        $aside = [
            '' => [
                'Download' => $this->url()->fromRoute('download'),
                'Changelog' => $this->url()->fromRoute('download/note')
            ],
            'Previous Releases' => [],
        ];
        foreach ($this->releases as $release) {
            $aside['Previous Releases'][$release['name']] = $this->url()->fromRoute('download/note/for-release', ['release' => $release['name']]);
        }
        $aside['Previous Releases']['All releases'] = 'https://github.com/laminas-api-tools/api-tools-skeleton/releases';
        return $aside;
    }

    public function indexAction()
    {
        return new ViewModel([
            'aside'   => $this->getAside(),
            'current' => $this->url()->fromRoute('download'),
            'version' => $this->releases->current()['name'],
        ]);
    }

    public function noteAction()
    {
        $version   = $this->params()->fromRoute('release', $this->releases->current()['name']);
        $changelog = 'No changelog found';

        foreach ($this->releases as $release) {
            if ($release['name'] === $version) {
                $changelog = $release['changelog'];
                break;
            }
        }

        return new ViewModel([
            'aside'     => $this->getAside(),
            'current'   => $this->url()->fromRoute('download/note/for-release', ['release' => $version]),
            'version'   => $version,
            'changelog' => $changelog,
        ]);
    }
}
