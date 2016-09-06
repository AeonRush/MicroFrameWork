<?php

namespace ACME\Sample\Client;

use Kernel\Controller;
use HttpFoundation\Request;

/**
 * Class TestController
 * @package ACME\Sample\Client
 */
class TestController extends Controller {

    /**
     * Тестовый Method
     * !!! Маршрут ДОЛЖЕН начинатся с @Route
     * @Route("{{name}}.{{extension}}", {"extension" : {"pattern" : "html|json"}, "name" : {"pattern" : ".+"}, "test" : {"test" : 1, "test2" : 2}})
     * @param Request $request
     * @return \HttpFoundation\Response
     */
    public function client(Request $request) {
        return $this->render("ACME:Sample::index.html.php", [
            'test' => [$request->param("extension", "I don't know"), $request->param("test")]
        ]);
    }
}