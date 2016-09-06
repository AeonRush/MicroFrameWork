<?php

namespace Kernel;

/**
 * Class FS
 * ����� ��� ������ � �������� ��������
 * @package Kernel
 */
final class FS {

    // ����
    private static $ROOT;
    private static $KERNEL;
    private static $USER_LAND;

    /**
     * �������������
     */
    public static function init() {
        self::$ROOT = realpath(dirname(__DIR__));
        self::$KERNEL = realpath(self::$ROOT.DIRECTORY_SEPARATOR.'Kernel');
        self::$USER_LAND = realpath(self::$ROOT.DIRECTORY_SEPARATOR.'UserLand');
    }

    /**
     * ����������� �����
     * @param $path1
     * @param $path2
     * @return string
     */
    public static function join($path1, $path2) {
        return realpath($path1.DIRECTORY_SEPARATOR.$path2);
    }

    /**
     * ��������� ���� Root
     * @param null $path
     * @return string
     */
    public static function getRoot($path = null) {
        return realpath(self::$ROOT.DIRECTORY_SEPARATOR.$path);
    }

    /**
     * ��������� ���� Kernel
     * @param null $path
     * @return string
     */
    public static function getKernel($path = null) {
        return realpath(self::$KERNEL.DIRECTORY_SEPARATOR.$path);
    }

    /**
     * ��������� ���� UserLand
     * @param null $path
     * @return string
     */
    public static function getUserLand($path = null) {
        return realpath(self::$USER_LAND.DIRECTORY_SEPARATOR.$path);
    }

    /**
     * ����� �����
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

// �����������������
FS::init();