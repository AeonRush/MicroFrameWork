<?php

namespace HttpFoundation;

/**
 * Class HttpCache
 * @package HttpFoundation
 */
final class HttpCache {

    /**
     * Добавляет ETag
     * !!! Если присутствует HTTP_IF_MODIFIED_SINCE и HTTP_IF_NONE_MATCH == etag скрипт возвращает код 304 и ПРЕКРАЩАЕТ своё выполнение
     * @param $etag
     */
    public function etag($etag) {
        $etag = hash('md5', $etag);
        header('Etag: '.$etag);
        if($_SERVER['HTTP_IF_MODIFIED_SINCE'] && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag){
            header('HTTP/1.1 304 Not Modified', true, 304);
            ob_end_clean();
            exit;
        };
    }

    /**
     * Добавляет дату модификации и дату истечения срока действия кэша
     * @param null $time
     */
    public function lastModified($time = NULL) {
        if($time == NULL) return;
        header_remove('Pragma');
        header('Last-modified: '.(gmdate('D, d M Y H:i:s \G\M\T', $time)));
        header('Cache-control: cache, store, public, must-revalidate');
        header('Expires: '.(gmdate('D, d M Y H:i:s', time() + 7 * 24 * 3600).' GMT'));
    }

    /**
     * Убирает все заголовки отвечающие за кэширование
     */
    public function noCache() {
        header('Cache-control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: '.(gmdate('D, d M Y H:i:s', time() - 604800).' GMT'));
    }
};