<?php

use Application\Command\YoutubeCommand;
use Application\Controller\ContactsController;
use Application\Controller\DownloadController;
use Application\Controller\DownloadControllerFactory;
use Application\Controller\HomeController;
use Application\Controller\HomeControllerFactory;
use Application\Controller\VideoController;
use Application\GithubReleases;
use Application\GithubReleasesFactory;
use Laminas\Navigation\Service\DefaultNavigationFactory;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'laminas-cli' => [
        'commands' => [
            'youtube' => YoutubeCommand::class,
        ],
    ],
    'router' => [
        'routes' => [
            'home' => [
                'type' => 'literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => HomeController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'video' => [
                'type' => 'literal',
                'options' => [
                    'route'    => '/video',
                    'defaults' => [
                        'controller' => VideoController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'download' => [
                'type' => 'literal',
                'options' => [
                    'route'    => '/download',
                    'defaults' => [
                        'controller' => DownloadController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'note' => [
                        'type' => 'literal',
                        'options' => [
                            'route' => '/note',
                            'defaults' => [
                                'controller' => DownloadController::class,
                                'action'     => 'note',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'for-release' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/[:release]',
                                    'defaults' => [
                                        'controller' => DownloadController::class,
                                        'action'     => 'note'
                                    ],
                                    'constraints' => [
                                        'release' => '[0-9]+\.[0-9]+\.[0-9]+'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'contacts' => [
                'type' => 'literal',
                'options' => [
                    'route'    => '/contacts',
                    'defaults' => [
                        'controller' => ContactsController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'navigation' => [
         'default' => [
             [
                'label' => 'Home',
                'route' => 'home',
             ],
             [
                'label' => 'Video',
                'route' => 'video',
             ],
             [
                'label' => 'Documentation',
                'route' => 'documentation',
                'pages' => [
                    [
                        'label' => 'Manual',
                        'route' => 'documentation/page',
                    ],
                ],
             ],
             [
                'label' => 'Download',
                'route' => 'download',
                'pages' => [
                    [
                        'label' => 'Release note',
                        'route' => 'download/note',
                    ],
                    [
                        'label' => 'Release note for release version',
                        'route' => 'download/note/for-release',
                    ],
                ],
             ],
             [
                'label' => 'Contacts',
                'route' => 'contacts',
             ],
         ],
     ],
    'service_manager' => [
        'aliases' => [
            'translator' => 'MvcTranslator',
        ],
        'invokables' => [
            YoutubeCommand::class => YoutubeCommand::class,
        ],
        'factories' => [
            'navigation'          => DefaultNavigationFactory::class,
            GithubReleases::class => GithubReleasesFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            ContactsController::class => InvokableFactory::class,
            DownloadController::class => DownloadControllerFactory::class,
            HomeController::class     => HomeControllerFactory::class,
            VideoController::class    => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
