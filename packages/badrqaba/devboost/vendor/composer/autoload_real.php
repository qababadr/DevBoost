<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit9dfa7898dfb38705cfb88aa1373d6b99
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit9dfa7898dfb38705cfb88aa1373d6b99', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit9dfa7898dfb38705cfb88aa1373d6b99', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit9dfa7898dfb38705cfb88aa1373d6b99::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
