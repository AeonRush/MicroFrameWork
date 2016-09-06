<?php

namespace HttpFoundation;

/**
 * Class HttpError
 * @package HttpFoundation
 */
class HttpError extends \Exception {
    public function __construct($code = 404, $message = '') {
        parent::__construct($message, $code);
    }
}