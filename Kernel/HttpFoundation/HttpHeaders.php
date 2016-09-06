<?php

namespace HttpFoundation {

    header('X-Powered-By: NanoFW');
    header('X-Powered-By-Version: 0.0.1');
    header('X-Powered-By-Author: https://github.com/AeonRush https://bitbucket.org/AeonRush');
    header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

    /**
     * Class HttpHeaders
     * @package HttpFoundation
     */
    class HttpHeaders {

        /**
         * Перемещено навсегда
         * @param $to
         */
        public static function m301($to) {
            http_response_code(301);
            header('Location: '.$to, true, 301);
            exit;
        }

        /**
         * Запросо ошибочен
         * @param bool $continue   продолжить выполнение скрипта
         */
        function m400($continue = false){
            http_response_code(400);
            if($continue == false) {
                exit;
            }
        }

        /**
         * Записывает код 404 в заголовок ответа
         * @param bool $continue   продолжить выполнение скрипта
         */
        function msg404($continue = false){
            http_response_code(404);
            if($continue == false) {
                exit;
            }
        }

        /**
         * Внутреняя ошибка сервера
         * @param bool $continue   продолжить выполнение скрипта
         */
        function msg500($continue = false){
            http_response_code(500);
            if($continue == false) {
                exit;
            }
        }

        /**
         * Внутреняя ошибка сервера
         * @param bool $continue   продолжить выполнение скрипта
         */
        function msg503($continue = false){
            http_response_code(503);
            if($continue == false) {
                exit;
            }
        }
    }
}

