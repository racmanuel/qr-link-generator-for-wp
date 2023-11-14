<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1c7309253e2bfecc661cda70cc1ec6b5
{
    public static $files = array (
        'ad901de1e5d16b81f427bfe3dc3de508' => __DIR__ . '/..' . '/cmb2/cmb2/init.php',
    );

    public static $prefixLengthsPsr4 = array (
        'c' => 
        array (
            'chillerlan\\Settings\\' => 20,
            'chillerlan\\QRCode\\' => 18,
        ),
        'A' => 
        array (
            'Appsero\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'chillerlan\\Settings\\' => 
        array (
            0 => __DIR__ . '/..' . '/chillerlan/php-settings-container/src',
        ),
        'chillerlan\\QRCode\\' => 
        array (
            0 => __DIR__ . '/..' . '/chillerlan/php-qrcode/src',
        ),
        'Appsero\\' => 
        array (
            0 => __DIR__ . '/..' . '/appsero/client/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1c7309253e2bfecc661cda70cc1ec6b5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1c7309253e2bfecc661cda70cc1ec6b5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1c7309253e2bfecc661cda70cc1ec6b5::$classMap;

        }, null, ClassLoader::class);
    }
}
