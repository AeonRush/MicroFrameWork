<?php

namespace HttpFoundation;

/**
 * Class Request
 * TODO Зарефакторить
 * @package HttpFoundation
 */
class Request {

    private $urlMatches;
    private $httpAccept = [];

    public function __construct($matches = null){
        $this->urlMatches = $matches;

        $temp = explode(',', $_SERVER['HTTP_ACCEPT']);
        foreach($temp as $t) {
            $this->httpAccept[] = trim($t);
        }

        unset($temp);
    }

    public function param($key, $default = null) {
        return $this->urlMatches[$key] ? $this->urlMatches[$key] : $default;
    }

    public function params() {
        return $this->urlMatches;
    }

    public function getHttpAccept($type = null, $strict = true) {

        if($type === null) {
            return $this->httpAccept;
        }

        if($strict === false && in_array('*/*', $this->httpAccept)){
            return true;
        }

        if(in_array($type, $this->httpAccept)){
            return true;
        }

        $result = [];
        foreach($this->httpAccept as $accept) {
            if(in_array($type, explode('/', $accept))) {
                $result[] = $accept;
            }
        }

        if(!empty($result)) {
            return $result;
        }

        foreach($this->httpAccept as $accept) {
            if(preg_match('/' . strtr($type, ['/' => '\/']) . '/', $accept)) {
                $result[] = $accept;
            }
        }

        if(!empty($result)) {
            return $result;
        }

        return false;

    }

}