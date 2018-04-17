<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitee63e4fcc1838e2055ffec604dee009f
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'setasign\\Fpdi\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'setasign\\Fpdi\\' => 
        array (
            0 => __DIR__ . '/..' . '/setasign/fpdi/src',
        ),
    );

    public static $classMap = array (
        'FPDF' => __DIR__ . '/..' . '/setasign/fpdf/fpdf.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitee63e4fcc1838e2055ffec604dee009f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitee63e4fcc1838e2055ffec604dee009f::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitee63e4fcc1838e2055ffec604dee009f::$classMap;

        }, null, ClassLoader::class);
    }
}
