<?php

namespace Kernel;

/**
 * Class View
 * TODO Work in progress
 * @package Kernel
 */
class View {
    protected $content = "";
    protected $contentType = "";

    /** @var Controller|null */
    protected $controller = null;

    public function __construct($arg, $params = null, Controller $controller = null) {
        $this->controller = $controller;
        $this->render($arg, $params);
    }

    public function render($arg, $params = null) {
        $this->setContentType("text/html");
        if(!is_string($arg)) {
            return $this->renderJSON($arg);
        }
        return $this->renderTemplate($arg, $params);
    }

    public function getContent() {
        return $this->content;
    }

    public function getContentType() {
        return $this->contentType;
    }

    protected function renderJSON($arg) {
        return $this->setContent(json_encode($arg))->setContentType('application/json');
    }

    protected function renderTemplate($arg, $params = null) {

        $request = $this->controller->getRequest();
        $render = function($template, $data) use ($request) {
            extract($data); unset($data);
            include $template;
        };

        $arg = FS::getUserLand(strtr($arg, ['::' => DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR, ':' => DIRECTORY_SEPARATOR]));

        ob_start();
        $render($arg, $params ? $params : []);
        return $this->setContent(ob_get_clean())->setContentType("text/html");
    }

    protected function setContent($content = "") {
        $this->content = $content;
        return $this;
    }

    protected function setContentType($type = "text/html") {
        $this->contentType = $type;
        return $this;
    }
}