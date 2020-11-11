<?php
namespace Config;

class BuildConfig
{
    private $loader;
    private $db_connection;
    private $build_paths;
    private $error_config;

    public function __construct($loader, $db_connection = false, $build_paths = true)
    {
        $this->loader        = $loader;
        $this->db_connection = $db_connection;
        $this->build_paths   = $build_paths;

        setlocale(LC_MONETARY, 'en_US.UTF-8');

        define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

        if ($this->build_paths) {
            BuildPath::setLoadPaths($this->loader);
        }

        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        $this->error_config = new ErrorConfig();
    }
}
