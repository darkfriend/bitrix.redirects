<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6d2985df50e2afdbed07ec5052d98851
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Darkfriend\\HLHelpers' => __DIR__ . '/..' . '/darkfriend/hlhelpers/HLHelpers.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit6d2985df50e2afdbed07ec5052d98851::$classMap;

        }, null, ClassLoader::class);
    }
}
