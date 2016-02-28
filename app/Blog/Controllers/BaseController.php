<?php
/**
 * Base Controller
 *
 * All other controllers should extend this class.
 * Loads the Slim Container to $this->container
 */
namespace Blog\Controllers;

class BaseController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
}
