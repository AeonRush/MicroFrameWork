<?php

namespace HttpFoundation;

use Kernel\View;

/**
 * Class Response
 * TODO Зарефакторить
 * @package HttpFoundation
 */
class Response {
    private $httpCache;
    private $httpHeaders;
    private $statusCode = 200;
    private $view = null;
    private $request = null;

    public function __construct(View $view, Request $request){
        $this->httpCache = new HttpCache();
        $this->httpHeaders = new HttpHeaders();
        $this->view = $view;
        $this->request = $request;
    }

    /**
     * @return HttpCache
     */
    public function getHttpCache() {
        return $this->httpCache;
    }

    public function setStatusCode($code = 200) {
        $this->statusCode = $code === null ? $this->statusCode : $code;
        return $this;
    }

    private function setContentType($type = null) {
        $type = $type ? $type : $this->view->getContentType();
        if(!$this->request->getHttpAccept($type, false)) {
            $type = $this->request->getHttpAccept()[0];
        }
        header("Content-type: $type");
        return $this;
    }

    public function getContent() {
        http_response_code($this->setContentType()->statusCode);
        ob_start();
        echo $this->view->getContent();
        return ob_end_flush();
    }
}