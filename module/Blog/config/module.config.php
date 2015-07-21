<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\BlogPost' => 'Blog\Controller\BlogPostController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'blog' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\BlogPost',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ZfcTwigViewStrategy',
        ),
    ),

    'view_helpers' => array(
        'invokables' => array(
            'showMessages' => 'Blog\View\Helper\ShowMessages',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'blog_entity' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/Blog/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Blog\Entity' => 'blog_entity',
                )
            )
        )
    ),

    'service_manager' => array(
        'aliases' => array(
            // Здесь можно задать алиасы для зарегистрированных сервисов или для других алиасов
        ),
        'factories' => array(
            // Ключ — имя сервиса,
            // Значение — либо имя класса, реализующего интерфейс FactoryInterface,
            // либо экземпляр класса, реализующего FactoryInterface,
            // либо любой PHP коллбэк

        ),
    ),
);