<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7cf1681dafad17d224382c1e3a020436
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '5b6d49eb231faf64eed5b5de9df7aa98' => __DIR__ . '/..' . '/ccxt/ccxt/ccxt.php',
    );

    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'ccxt_async\\' => 11,
            'ccxt\\' => 5,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Src\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ccxt_async\\' => 
        array (
            0 => __DIR__ . '/..' . '/ccxt/ccxt/php/async',
        ),
        'ccxt\\' => 
        array (
            0 => __DIR__ . '/..' . '/ccxt/ccxt/php',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Src\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Console_Table' => __DIR__ . '/..' . '/pear/console_table/Table.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7cf1681dafad17d224382c1e3a020436::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7cf1681dafad17d224382c1e3a020436::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7cf1681dafad17d224382c1e3a020436::$classMap;

        }, null, ClassLoader::class);
    }
}