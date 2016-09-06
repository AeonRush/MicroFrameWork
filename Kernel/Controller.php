<?php

namespace Kernel;

use HttpFoundation\Request;
use HttpFoundation\Response;

/**
 * Class Controller
 * TODO Work in progress
 * @package Kernel
 */
class Controller {

    protected $request;

    public function __construct(Request $request = null) {
        $this->request = $request;
    }

    public function getRequest() {
        return $this->request;
    }

    public function render($arg, $params = []) {
        return new Response(new View($arg, $params, $this), $this->getRequest(), $this);
    }
}