<?php

namespace Blog\Framework;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Create view for end-user
 */
class View
{
    protected const TEMPLATING_TWIG_DIRECTORY = './templates_v2';
    protected const TEMPLATING_TWIG_EXTENSION = '.twig';
    protected $templatingEngine;

    /**
     * Constructor. Instanciate templating engine
     */
    public function __construct(Configuration $config)
    {
        $loader = new FilesystemLoader($config->getThemeDirectory());
        $this->templatingEngine = new Environment($loader, [
            'debug' => true
        ]);
    }

    /**
     * Create a view for end user by calling file passed in parameters
     * @param  string     $view       PHP view to call
     * @param  array|null $parameters Parameters to pass to view
     */
    public function render(string $view, array $parameters = null)
    {
        //Print on screen twig template
        $this->templatingEngine->display($view.$this::TEMPLATING_TWIG_EXTENSION, $parameters);
    }
}
