<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools.getlaminas.org for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools.getlaminas.org/blob/master/LICENSE.md New BSD License
 */

return [
    'controllers' => [
        'factories' => [
            'Documentation\Controller' => 'Documentation\DocumentationControllerFactory',
        ],
    ],
    'router' => ['routes' => [
        'documentation' => [
            'type' => 'Literal',
            'options' => [
                'route' => '/documentation',
                'defaults' => [
                    'controller' => 'Documentation\Controller',
                    'action'     => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes' => [
                'page' => [
                    'type' => 'Segment',
                    'options' => [
                        'route' => '/:page',
                        'defaults' => [
                            'action' => 'page',
                        ],
                        'constraints' => [
                            'page' => '[a-zA-Z0-9][a-zA-Z0-9_./-]*',
                        ],
                    ],
                ],
            ],
        ],
    ]],
    'service_manager' => [
        'factories' => [
            'Documentation\Model' => 'Documentation\DocumentationModelFactory',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'markdownpage' => 'Documentation\MarkdownPageHelperFactory',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
