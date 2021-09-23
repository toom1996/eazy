<?php

namespace eazy\composer;

use Composer\Installer\LibraryInstaller;
use Composer\Script\Event;

class Installer extends LibraryInstaller
{
    /**
     * Special method to run tasks defined in `[extra][yii\composer\Installer::postCreateProject]` key in `composer.json`
     *
     * @param  Event  $event
     */
    public static function postCreateProject(Event $event): void
    {
        die('123');
    }
}