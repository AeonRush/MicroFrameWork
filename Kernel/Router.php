<?php

namespace Kernel;

use HttpFoundation\HttpError;
use HttpFoundation\Request;
use HttpFoundation\Response;

/**
 * Class Router
 * TODO Зарефакторить и оставить только V2 + сделать генерацию URL
 * @package Kernel
 */
final class Router {

    private $rules = array();
    private $httpMethod = 'get';
    private $REQUEST_URI = null;

    public function __construct() {
        $this->init();
    }

    protected function init() {
        if (!empty($_POST) && !empty($_FILES)) {
            $this->httpMethod = 'post&files';
        }
        elseif (!empty($_POST)) {
            $this->httpMethod = 'post';
        }
        elseif (!empty($_FILES)) {
            $this->httpMethod = 'files';
        }

        $this->REQUEST_URI = iconv('cp1251', 'utf-8', substr($_SERVER['REQUEST_URI'], (substr($_SERVER['REQUEST_URI'], 0, 1) == '?') ? 2 : 1));

        $files = FS::findFile('routes.php');

        foreach ($files as $file) {
            $file = pathinfo($file[0]);
            $this->rules[substr($file['dirname'], strlen(FS::getUserLand()))] = include(FS::join($file['dirname'], $file['basename']));
        }

        unset($files);

        return $this;
    }

    /**
     * TODO Написать Генерацию URL
     */
    public function routeV2() {
        $request_uri = $this->REQUEST_URI;
        $files = FS::findFile('Controller.php', FS::getUserLand());

        foreach ($files as $file) {
            $file = pathinfo($file[0]);
            $class = strtr(substr($file['dirname'].'\\'.$file['filename'], strlen(FS::getUserLand())), ['/' => '\\']);

            $temp = new $class;

            if(!($temp instanceof Controller)) {
                throw new \ErrorException("Controller MUST BE instance of \\Kernel\\Controller");
            }

            $reflection = new \ReflectionClass($temp);
            unset($temp);

            foreach($reflection->getMethods() as $route) {
                if(preg_match('/@Route\((.+)\)/', $route->getDocComment(), $matches)) {

                    $routeData = json_decode('['.strtr($matches[1], ['\\' => '\\\\']).']', true);

                    $url = str_replace('^', '^([a-z]{2}\-[a-z]{2}[/]{1})?', $routeData[0]);

                    foreach($routeData[1] as $key => $value) {
                        $url = str_replace("{{{$key}}}", '(?P<'.strtr($key, ['{{' => '<', '}}' => '>']).'>'.$value['pattern'].')', $url);
                    }

                    if (preg_match('/' . strtr($url, ['/' => '\/']) . '/', $request_uri, $matches) == true) {

                        $request = [];
                        foreach($routeData[1] as $key => $value) {
                            $request[$key] = $matches[$key] ? $matches[$key] : $routeData[1][$key];
                        }

                        $request = new Request($request);

                        $class = new $class($request);
                        $response = $class->{$route->name}($request);

                        if($response instanceof Response) {
                            $response->getContent();
                            die();
                        }
                    }
                }
            }
            unset($reflection);
        }
        throw new HttpError(404, 'Page not Found');
    }

    public function route($request_uri = null){

        if(!$request_uri) {
            $request_uri = $this->REQUEST_URI;
        }

        foreach ($this->rules as $scope => $routers) {
            foreach ($routers as $template => $params) {
                $url = explode('::', $template);

//            $url[1] = !$url[1] ? 'get' : $url[1];
//            if ($url[1] !== $this->httpMethod) continue;

                $matches = array();
                $url = str_replace('^', '^([a-z]{2}\-[a-z]{2}[/]{1})?', $url[0]);

                if (preg_match('/' . strtr($url, ['/' => '\/']) . '/', $request_uri, $matches) == true) {

                    $query = [];
                    $temp = explode("?", $params);
                    $temp = explode("&", $temp[1]);
                    foreach($temp as $t) {
                        $t = explode("=", $t);
                        $query[$t[0]] = $matches[(int)strtr($t[1], ['$' => '']) + 1];
                    }

                    $request = new Request($query);

                    if(is_callable($params)) {
                        call_user_func($params, $request);
                        die();
                    }

                    $temp = explode("::", $params);
                    $class = strtr($temp[0], ['./' => $scope.'\\', '/' => '\\']);

                    $class = new $class($request);

                    if(!($class instanceof Controller)) {
                        throw new \ErrorException("Controller MUST BE instance of \\Kernel\\Controller");
                    }

                    $temp = explode('?', $temp[1]);
                    $method = $temp[0];

                    $response = $class->$method($request);

                    if($response instanceof Response) {
                        $response->getContent();
                        die();
                    }
                }
            }
        }
        throw new HttpError(404, 'Page not Found');
    }
}