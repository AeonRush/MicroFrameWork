<?php

namespace Kernel;

/**
 * Class FS
 * Класс для работы с Файловой системой
 * @package Kernel
 */
final class FS {

    // Пути
    private static $ROOT;
    private static $KERNEL;
    private static $USER_LAND;

    /**
     * Инициализация
     */
    public static function init() {
        self::$ROOT = realpath(dirname(__DIR__));
        self::$KERNEL = realpath(self::$ROOT.DIRECTORY_SEPARATOR.'Kernel');
        self::$USER_LAND = realpath(self::$ROOT.DIRECTORY_SEPARATOR.'UserLand');
    }

    /**
     * Объединение путей
     * @param $path1
     * @param $path2
     * @return string
     */
    public static function join($path1, $path2) {
        return realpath($path1.DIRECTORY_SEPARATOR.$path2);
    }

    /**
     * Получение пути Root
     * @param null $path
     * @return string
     */
    public static function getRoot($path = null) {
        return realpath(self::$ROOT.DIRECTORY_SEPARATOR.$path);
    }

    /**
     * Получение пути Kernel
     * @param null $path
     * @return string
     */
    public static function getKernel($path = null) {
        return realpath(self::$KERNEL.DIRECTORY_SEPARATOR.$path);
    }

    /**
     * Получение пути UserLand
     * @param null $path
     * @return string
     */
    public static function getUserLand($path = null) {
        return realpath(self::$USER_LAND.DIRECTORY_SEPARATOR.$path);
    }

    /**
     * Поиск файла
     * @param null $filename
     * @param null $path
     * @return \RegexIterator
     */
    public static function findFile($filename = null, $path = null) {
        $path = !$path ? self::getUserLand() : $path;
        $separator = DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR;
        $filename = strtr($filename,
            [
                '::' => '.+',
                ':' => $separator,
                '/' => $separator,
                '\\' => $separator, '.' => '\.'
            ]
        );

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        return new \RegexIterator($iterator, '/^.+'.$filename.'$/i', \RecursiveRegexIterator::GET_MATCH);
    }

}

// Автоинициализация
FS::init();