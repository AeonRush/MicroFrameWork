<?php

namespace Kernel;

/**
 * Class Kernel
 * @package Kernel
 */
class Kernel {
    /**
     * Router @var
     */
    private $router;

    public function __construct() {
        ErrorHandler::getInstance($this);
        $this->setRouter(new Router($this));
    }

    /**
     * @return Router
     */
    public function getRouter() {
        return $this->router;
    }

    /**
     * @param Router $router
     * @return self
     */
    public function setRouter($router) {
        $this->router = $router;
        return $this;
    }
}