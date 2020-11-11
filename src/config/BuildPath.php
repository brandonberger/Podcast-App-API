<?php
namespace Config;

class BuildPath
{
    public static function setLoadPaths($loader)
    {
        $loader->addPsr4('Config\\', $_SERVER['DOCUMENT_ROOT'].'/src/config/');
        $loader->addPsr4('Models\\', $_SERVER['DOCUMENT_ROOT'].'/src/models/Models/');
        $loader->addPsr4('Controllers\\', $_SERVER['DOCUMENT_ROOT'].'/src/controllers/');

    }
}
