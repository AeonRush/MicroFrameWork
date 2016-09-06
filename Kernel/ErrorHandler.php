<?php

namespace Kernel;

use HttpFoundation\HttpError;

/**
 * Class ErrorHandler
 * Класс для перехвата ошибок
 * @package Kernel
 */
class ErrorHandler {

    private static $self;
    private $kernel;

    /**
     * Singleton
     * @param Kernel|null $kernel
     * @return ErrorHandler
     */
    public static function getInstance(Kernel $kernel) {
        if(!self::$self) {
            self::$self = new self;
            self::$self->init($kernel);
        }
        return self::$self;
    }

    /**
     * Инициализация
     * @param Kernel $kernel
     */
    private function init(Kernel $kernel) {
        $this->kernel = $kernel;
        @register_shutdown_function([$this, 'shutdownHandler']);
        @set_error_handler([$this, 'errorHandler']);
        @set_exception_handler([$this, 'exceptionHandler']);
    }

    /**
     * Перехват Exceptions
     * @param $exception
     */
    public function exceptionHandler(\Exception $exception) {

        /**
         * HttpError's handle
         */
        if($exception instanceof HttpError) {
            http_response_code($exception->getCode());
            die();
        }
        print "Exception Caught: ". $exception->getMessage() ."\n";
    }

    /**
     * Обработчик shutdown
     */
    public function shutdownHandler() {
        $lastError = error_get_last();
        switch ($lastError['type']) {
            case E_ERROR:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            case E_RECOVERABLE_ERROR:
            case E_CORE_WARNING:
            case E_COMPILE_WARNING:
            case E_PARSE:
                $this->exceptionHandler(new \ErrorException($lastError['message'], 0, $lastError['type'], $lastError['file'], $lastError['line']));
        }
    }

    /**
     * Обрабочик ошибок PHP
     * @see http://php.net/manual/en/function.set-error-handler.php
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @return \ErrorException
     */
    public function errorHandler($errno, $errstr, $errfile, $errline) {
        return new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
