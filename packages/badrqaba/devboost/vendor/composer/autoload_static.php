<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9dfa7898dfb38705cfb88aa1373d6b99
{
    public static $prefixLengthsPsr4 = array (
        'B' => 
        array (
            'Badrqaba\\Devboost\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Badrqaba\\Devboost\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9dfa7898dfb38705cfb88aa1373d6b99::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9dfa7898dfb38705cfb88aa1373d6b99::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9dfa7898dfb38705cfb88aa1373d6b99::$classMap;

        }, null, ClassLoader::class);
    }
}
