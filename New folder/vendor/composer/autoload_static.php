<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc2ff4367333ccc1034010e57cf93c0ab
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc2ff4367333ccc1034010e57cf93c0ab::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc2ff4367333ccc1034010e57cf93c0ab::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc2ff4367333ccc1034010e57cf93c0ab::$classMap;

        }, null, ClassLoader::class);
    }
}
