<?php

/**
 * Если функции http_response_code нет, определяем её
 * Thanks to http://php.net/manual/ru/function.http-response-code.php#107261
 */
if (!function_exists('http_response_code')) {
    /**
     * Код ответа устанавливается с помощью опционального параметра code
     * @param null $code
     *
     * @return int|null
     */
    function http_response_code($code = NULL) {
        if ($code !== NULL) {
            switch ($code) {
                case 100: $text = 'Continue'; break;
                case 101: $text = 'Switching Protocols'; break;
                case 200: $text = 'OK'; break;
                case 201: $text = 'Created'; break;
                case 202: $text = 'Accepted'; break;
                case 203: $text = 'Non-Authoritative Information'; break;
                case 204: $text = 'No Content'; break;
                case 205: $text = 'Reset Content'; break;
                case 206: $text = 'Partial Content'; break;
                case 300: $text = 'Multiple Choices'; break;
                case 301: $text = 'Moved Permanently'; break;
                case 302: $text = 'Moved Temporarily'; break;
                case 303: $text = 'See Other'; break;
                case 304: $text = 'Not Modified'; break;
                case 305: $text = 'Use Proxy'; break;
                case 400: $text = 'Bad Request'; break;
                case 401: $text = 'Unauthorized'; break;
                case 402: $text = 'Payment Required'; break;
                case 403: $text = 'Forbidden'; break;
                case 404: $text = 'Not Found'; break;
                case 405: $text = 'Method Not Allowed'; break;
                case 406: $text = 'Not Acceptable'; break;
                case 407: $text = 'Proxy Authentication Required'; break;
                case 408: $text = 'Request Time-out'; break;
                case 409: $text = 'Conflict'; break;
                case 410: $text = 'Gone'; break;
                case 411: $text = 'Length Required'; break;
                case 412: $text = 'Precondition Failed'; break;
                case 413: $text = 'Request Entity Too Large'; break;
                case 414: $text = 'Request-URI Too Large'; break;
                case 415: $text = 'Unsupported Media Type'; break;
                case 500: $text = 'Internal Server Error'; break;
                case 501: $text = 'Not Implemented'; break;
                case 502: $text = 'Bad Gateway'; break;
                case 503: $text = 'Service Unavailable'; break;
                case 504: $text = 'Gateway Time-out'; break;
                case 505: $text = 'HTTP Version not supported'; break;
                default:
                    exit('Unknown http status code "'.htmlentities($code).'"');
                    break;
            };
            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
            header($protocol . ' ' . $code . ' ' . $text);
            $GLOBALS['http_response_code'] = $code;
        } else {
            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
        };
        return $code;
    };
};

/**
 * Исправление неверного расчета контрольной суммы CRC32 в PHP
 * @param $v
 * @return int
 */
function crc32_fix($v){
    $v = crc32($v);
    return ($v < 0) ? ($v + 4294967296) : $v;
};
/**
 * Очистка массива от пустых элементов
 * @param $a
 * @return array
 */
function array_clean($a) {
    $c = array();
    foreach ($a as $k=>$v){
        if(!empty($v)) $c[] = $v;
    };
    unset($a);
    return $c;
}

/**
 * Очистка строки
 * @param $str
 * @return string
 */
function strclean(&$str){
    $str = strip_tags($str);
    /// $str = str_replace('№', '_', $str);
    $str = mb_ereg_replace('\W+', ' ',  $str );
    return $str;
};

/**
 * Адекватное отображение JSON в кодировке UTF-8
 * @param $value
 * @return mixed
 */
function json_encode_utf8($value){
    return ( preg_replace_callback (
        '/\\\u([0-9a-fA-F]{4})/',
        create_function('$match', 'return mb_convert_encoding("&#".intval($match[1], 16).";", "UTF-8", "HTML-ENTITIES");'),
        json_encode($value)
    ) );
};

/**
 * Удаление дерева каталогов
 * @param $dir
 * @return bool
 */
function deleteTree($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) { (is_dir("$dir/$file")) ? deleteTree("$dir/$file") : unlink("$dir/$file"); };
    return rmdir($dir);
};
